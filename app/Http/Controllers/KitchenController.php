<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KitchenController extends Controller
{
    public function index(): View
    {
        $aPreparer = Commande::with(['lignes.plat'])
            ->whereIn('statut', ['confirmee', 'en_attente'])
            ->orderBy('created_at', 'asc')
            ->get();

        $enPreparation = Commande::with(['lignes.plat'])
            ->where('statut', 'en_preparation')
            ->orderBy('created_at', 'asc')
            ->get();

        $pretes = Commande::with(['lignes.plat', 'livreur'])
            ->where('statut', 'prete')
            ->latest()
            ->take(15)
            ->get();

        return view('cuisine.index', compact('aPreparer', 'enPreparation', 'pretes'));
    }

    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $commande = Commande::findOrFail($id);
        $nouveauStatut = $request->input('statut');

        if (in_array($nouveauStatut, ['confirmee', 'en_preparation', 'prete'], true)) {
            $commande->update(['statut' => $nouveauStatut]);
            return back()->with('success', "Commande #{$commande->code_commande} mise à jour : {$commande->statut_label}.");
        }

        return back()->with('error', 'Action non autorisée.');
    }

    public function apiPoll(): JsonResponse
    {
        $countNouvelles = Commande::whereIn('statut', ['en_attente', 'confirmee'])->count();
        $countEnPrep = Commande::where('statut', 'en_preparation')->count();

        return response()->json([
            'count_nouvelles' => $countNouvelles,
            'count_en_preparation' => $countEnPrep,
        ]);
    }
}
