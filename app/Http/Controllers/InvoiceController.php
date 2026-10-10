<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function show(string $numero): View
    {
        $facture = Facture::with(['commande.lignes', 'commande.zoneLivraison'])
            ->where('numero_facture', $numero)
            ->firstOrFail();

        return view('pages.facture', compact('facture'));
    }
}
