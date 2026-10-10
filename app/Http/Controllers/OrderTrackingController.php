<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class OrderTrackingController extends Controller
{
    public function show(string $token): View
    {
        $commande = Commande::with(['lignes.plat', 'zoneLivraison', 'livreur', 'facture'])
            ->where('tracking_token', $token)
            ->orWhere('code_commande', $token)
            ->firstOrFail();

        return view('pages.suivi-commande', compact('commande'));
    }

    public function status(string $token): JsonResponse
    {
        $commande = Commande::with(['livreur'])
            ->where('tracking_token', $token)
            ->orWhere('code_commande', $token)
            ->firstOrFail();

        return response()->json([
            'id' => $commande->id,
            'code_commande' => $commande->code_commande,
            'statut' => $commande->statut,
            'statut_label' => $commande->statut_label,
            'statut_badge' => $commande->statut_badge_class,
            'step' => $commande->step_progress,
            'updated_at' => $commande->updated_at->diffForHumans(),
            'livreur' => $commande->livreur ? [
                'nom' => $commande->livreur->name,
                'telephone' => $commande->livreur->telephone,
            ] : null,
        ]);
    }
}
