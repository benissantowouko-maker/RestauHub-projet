<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DeliveryController extends Controller
{
    public function index(): View
    {
        $livreur = Auth::user();

        // Orders ready in kitchen waiting for assignment or already assigned to this courier
        $commandesPretes = Commande::with(['lignes', 'zoneLivraison'])
            ->where('statut', 'prete')
            ->where(function ($q) use ($livreur) {
                $q->whereNull('livreur_id')->orWhere('livreur_id', $livreur->id);
            })
            ->latest()
            ->get();

        // Orders currently in transit with this courier
        $commandesEnCours = Commande::with(['lignes', 'zoneLivraison'])
            ->where('livreur_id', $livreur->id)
            ->where('statut', 'en_livraison')
            ->latest()
            ->get();

        // History of completed deliveries by this courier
        $commandesHistorique = Commande::with(['lignes', 'zoneLivraison', 'facture'])
            ->where('livreur_id', $livreur->id)
            ->where('statut', 'livree')
            ->latest()
            ->take(20)
            ->get();

        // Today's stats for this courier
        $livraisonsAujourdhui = Commande::where('livreur_id', $livreur->id)
            ->where('statut', 'livree')
            ->whereDate('date_livraison', Carbon::today())
            ->count();

        $encaissementsAujourdhui = Commande::where('livreur_id', $livreur->id)
            ->where('statut', 'livree')
            ->where('mode_paiement', 'especes_livraison')
            ->whereDate('date_livraison', Carbon::today())
            ->sum('montant_total');

        return view('coursier.dashboard', compact(
            'livreur',
            'commandesPretes',
            'commandesEnCours',
            'commandesHistorique',
            'livraisonsAujourdhui',
            'encaissementsAujourdhui'
        ));
    }

    public function show(int $id): View
    {
        $commande = Commande::with(['lignes.plat', 'zoneLivraison', 'facture'])->findOrFail($id);
        return view('coursier.show', compact('commande'));
    }

    public function claim(int $id): RedirectResponse
    {
        $commande = Commande::findOrFail($id);
        $livreur = Auth::user();

        if ($commande->livreur_id && $commande->livreur_id !== $livreur->id) {
            return back()->with('error', 'Cette livraison a déjà été prise en charge par un autre coursier.');
        }

        $commande->update([
            'livreur_id' => $livreur->id,
            'date_assignation_livreur' => Carbon::now(),
        ]);

        return back()->with('success', "Vous avez pris en charge la livraison #{$commande->code_commande}.");
    }

    public function startDelivery(int $id): RedirectResponse
    {
        $commande = Commande::findOrFail($id);
        $livreur = Auth::user();

        $commande->update([
            'livreur_id' => $livreur->id,
            'statut' => 'en_livraison',
            'date_recuperation_livreur' => Carbon::now(),
        ]);

        return back()->with('success', "Statut mis à jour : En cours de livraison vers {$commande->nom_client}.");
    }

    public function completeDelivery(int $id): RedirectResponse
    {
        $commande = Commande::findOrFail($id);

        $commande->update([
            'statut' => 'livree',
            'statut_paiement' => 'paye',
            'date_livraison' => Carbon::now(),
        ]);

        return redirect()->route('coursier.dashboard')
            ->with('success', "Livraison #{$commande->code_commande} validée avec succès ! Montant encaissé ou validé.");
    }

    public function apiPoll(): JsonResponse
    {
        $livreur = Auth::user();

        $nouveautesPretes = Commande::where('statut', 'prete')
            ->where(function ($q) use ($livreur) {
                $q->whereNull('livreur_id')->orWhere('livreur_id', $livreur->id);
            })
            ->count();

        $enCoursCount = Commande::where('livreur_id', $livreur->id)
            ->where('statut', 'en_livraison')
            ->count();

        return response()->json([
            'count_pretes' => $nouveautesPretes,
            'count_en_cours' => $enCoursCount,
        ]);
    }
}
