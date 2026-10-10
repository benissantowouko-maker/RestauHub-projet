<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $statut = $request->get('statut', 'tous');
        $periode = $request->get('periode', 'toutes');
        $search = $request->get('search');

        $query = Commande::with(['lignes', 'zoneLivraison', 'livreur', 'facture']);

        if ($statut && $statut !== 'tous') {
            $query->where('statut', $statut);
        }

        if ($periode && $periode !== 'toutes') {
            match ($periode) {
                'aujourdhui' => $query->whereDate('created_at', Carbon::today()),
                'semaine' => $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]),
                'mois' => $query->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year),
                default => null,
            };
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('code_commande', 'like', "%{$search}%")
                  ->orWhere('nom_client', 'like', "%{$search}%")
                  ->orWhere('telephone_client', 'like', "%{$search}%");
            });
        }

        $commandes = $query->latest()->paginate(15)->withQueryString();
        $livreurs = User::where('role', 'livreur')->where('actif', true)->get();

        // Count per status for tabs
        $counts = [
            'tous' => Commande::count(),
            'en_attente' => Commande::where('statut', 'en_attente')->count(),
            'confirmee' => Commande::where('statut', 'confirmee')->count(),
            'en_preparation' => Commande::where('statut', 'en_preparation')->count(),
            'prete' => Commande::where('statut', 'prete')->count(),
            'en_livraison' => Commande::where('statut', 'en_livraison')->count(),
            'livree' => Commande::where('statut', 'livree')->count(),
            'annulee' => Commande::where('statut', 'annulee')->count(),
        ];

        return view('admin.commandes.index', compact('commandes', 'livreurs', 'statut', 'periode', 'search', 'counts'));
    }

    public function show(int $id): View
    {
        $commande = Commande::with(['lignes.plat', 'zoneLivraison', 'livreur', 'facture'])->findOrFail($id);
        $livreurs = User::where('role', 'livreur')->where('actif', true)->get();

        return view('admin.commandes.show', compact('commande', 'livreurs'));
    }

    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $commande = Commande::findOrFail($id);
        $statut = $request->validate([
            'statut' => 'required|in:en_attente,confirmee,en_preparation,prete,en_livraison,livree,annulee',
        ])['statut'];

        $updateData = ['statut' => $statut];

        if ($statut === 'en_livraison' && ! $commande->date_recuperation_livreur) {
            $updateData['date_recuperation_livreur'] = Carbon::now();
        }

        if ($statut === 'livree') {
            $updateData['date_livraison'] = Carbon::now();
            $updateData['statut_paiement'] = 'paye';
        }

        $commande->update($updateData);

        return back()->with('success', "Le statut de la commande #{$commande->code_commande} est maintenant : {$commande->statut_label}.");
    }

    public function assignDriver(Request $request, int $id): RedirectResponse
    {
        $commande = Commande::findOrFail($id);
        $validated = $request->validate([
            'livreur_id' => 'required|exists:users,id',
        ]);

        $commande->update([
            'livreur_id' => $validated['livreur_id'],
            'date_assignation_livreur' => Carbon::now(),
        ]);

        $livreur = User::find($validated['livreur_id']);

        return back()->with('success', "Le coursier {$livreur->name} a été assigné à la commande #{$commande->code_commande}.");
    }
}
