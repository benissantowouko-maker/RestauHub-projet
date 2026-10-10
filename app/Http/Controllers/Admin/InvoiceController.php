<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facture;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function index(Request $request): View
    {
        $periode = $request->get('periode', 'toutes');
        $search = $request->get('search');

        $query = Facture::with(['commande.lignes', 'commande.client']);

        if ($periode && $periode !== 'toutes') {
            match ($periode) {
                'aujourdhui' => $query->whereDate('date_emission', Carbon::today()),
                '7jours' => $query->where('date_emission', '>=', Carbon::now()->subDays(7)),
                'mois' => $query->whereMonth('date_emission', Carbon::now()->month)->whereYear('date_emission', Carbon::now()->year),
                default => null,
            };
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('numero_facture', 'like', "%{$search}%")
                  ->orWhereHas('commande', function ($sub) use ($search) {
                      $sub->where('nom_client', 'like', "%{$search}%")
                          ->orWhere('code_commande', 'like', "%{$search}%");
                  });
            });
        }

        $factures = $query->latest('date_emission')->paginate(15)->withQueryString();

        $totalFacture = Facture::sum('montant_fcfa');
        $facturesAujourdhuiCount = Facture::aujourdhui()->count();

        return view('admin.factures.index', compact('factures', 'periode', 'search', 'totalFacture', 'facturesAujourdhuiCount'));
    }

    public function show(int $id): View
    {
        $facture = Facture::with(['commande.lignes', 'commande.zoneLivraison'])->findOrFail($id);
        return view('pages.facture', compact('facture'));
    }

    public function markSent(int $id): RedirectResponse
    {
        $facture = Facture::findOrFail($id);
        $facture->update(['statut_envoi' => 'envoyee']);

        return back()->with('success', "Facture #{$facture->numero_facture} marquée comme envoyée par email.");
    }
}
