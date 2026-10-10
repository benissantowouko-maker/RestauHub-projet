<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Facture {{ $facture->numero_facture }} — Café Paris Lomé</title>

    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])

    <style>
        body { font-family: 'Instrument Sans', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .invoice-box { border: none !important; box-shadow: none !important; }
        }
    </style>
</head>
<body class="bg-stone-100 text-slate-800 p-4 sm:p-8 min-h-screen">

    <div class="max-w-3xl mx-auto space-y-4">
        <!-- Action bar (hidden on print) -->
        <div class="no-print flex items-center justify-between bg-white p-4 rounded-2xl shadow-sm border border-stone-200">
            <a href="javascript:history.back()" class="text-xs font-bold text-slate-600 hover:text-red-800 transition flex items-center gap-1">
                ← Retour
            </a>
            <div class="flex items-center gap-3">
                <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-red-800 hover:bg-red-900 text-white text-xs font-bold shadow transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    <span>Imprimer / Télécharger en PDF</span>
                </button>
            </div>
        </div>

        <!-- Official Invoice Document -->
        <div class="invoice-box bg-white p-8 sm:p-12 rounded-3xl shadow-sm border border-stone-200 space-y-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row items-start justify-between gap-6 border-b border-stone-200 pb-8">
                <div>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-red-900 flex items-center justify-center text-amber-400 font-bold text-xl shadow">
                            CP
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-red-950 tracking-tight">CAFÉ PARIS LOMÉ</h1>
                            <p class="text-xs text-stone-500 uppercase tracking-wider font-semibold">Brasserie & Gastronomie</p>
                        </div>
                    </div>
                    <address class="not-italic text-xs text-slate-500 mt-4 space-y-1">
                        <p>Boulevard de la Marina, Angle Rue de la Paix</p>
                        <p>Lomé — République Togolaise</p>
                        <p>Tél : +228 90 00 12 34 / +228 98 00 12 34</p>
                        <p>RCCM : TG-LOM-2020-B-1284 | NIF : 1001458920</p>
                    </address>
                </div>

                <div class="text-left sm:text-right space-y-1">
                    <span class="inline-block px-3 py-1 rounded bg-red-50 text-red-800 text-xs font-black uppercase tracking-wider">
                        FACTURE OFFICIELLE
                    </span>
                    <h2 class="text-xl font-black text-slate-900 mt-2 font-mono">{{ $facture->numero_facture }}</h2>
                    <p class="text-xs text-slate-500">Date d'émission : <strong>{{ $facture->date_emission->format('d/m/Y H:i') }}</strong></p>
                    <p class="text-xs text-slate-500">Commande associée : <strong>#{{ $facture->commande->code_commande }}</strong></p>
                </div>
            </div>

            <!-- Client Info & Payment Status -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 bg-stone-50 p-5 rounded-2xl border border-stone-200">
                <div>
                    <h3 class="text-xs font-bold uppercase text-slate-400 mb-2">Facturé à :</h3>
                    <p class="text-sm font-bold text-slate-900">{{ $facture->commande->nom_client }}</p>
                    <p class="text-xs text-slate-600 mt-0.5">Tél : {{ $facture->commande->telephone_client }}</p>
                    @if($facture->commande->email_client)
                        <p class="text-xs text-slate-600">Email : {{ $facture->commande->email_client }}</p>
                    @endif
                    <p class="text-xs text-slate-600 mt-1">Lieu de livraison : {{ $facture->commande->adresse_livraison ?? 'Lomé, Togo' }}</p>
                </div>

                <div class="sm:text-right">
                    <h3 class="text-xs font-bold uppercase text-slate-400 mb-2">Règlement :</h3>
                    <p class="text-xs font-bold text-slate-800">Mode : {{ $facture->commande->mode_paiement_label }}</p>
                    @if($facture->commande->reference_paiement)
                        <p class="text-xs text-slate-500 font-mono">Réf. transaction : {{ $facture->commande->reference_paiement }}</p>
                    @endif
                    <div class="mt-3">
                        @if($facture->commande->statut_paiement === 'paye')
                            <span class="inline-block px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold border border-emerald-300">
                                ✓ PAYÉ & VALIDÉ
                            </span>
                        @else
                            <span class="inline-block px-3 py-1 rounded-full bg-amber-100 text-amber-900 text-xs font-bold border border-amber-300">
                                À RÉGLER À LA LIVRAISON (ESPÈCES)
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b-2 border-stone-300 text-slate-400 uppercase tracking-wider">
                            <th class="py-3 font-bold">Désignation de l'article</th>
                            <th class="py-3 text-center font-bold">Quantité</th>
                            <th class="py-3 text-right font-bold">Prix unitaire (FCFA)</th>
                            <th class="py-3 text-right font-bold">Sous-total (FCFA)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-200">
                        @foreach($facture->commande->lignes as $ligne)
                            <tr>
                                <td class="py-3.5 pr-4">
                                    <p class="font-bold text-slate-900">{{ $ligne->nom_produit }}</p>
                                    @if($ligne->instructions_speciales)
                                        <p class="text-[11px] text-slate-400 italic">Précision : {{ $ligne->instructions_speciales }}</p>
                                    @endif
                                </td>
                                <td class="py-3.5 text-center font-bold text-slate-700">{{ $ligne->quantite }}</td>
                                <td class="py-3.5 text-right font-medium text-slate-600">{{ $ligne->prix_unitaire_formate }}</td>
                                <td class="py-3.5 text-right font-bold text-slate-900">{{ $ligne->sous_total_formate }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Financial Totals -->
            <div class="border-t-2 border-stone-300 pt-4 flex justify-end">
                <div class="w-full sm:w-72 space-y-2 text-xs">
                    <div class="flex justify-between text-slate-600">
                        <span>Sous-total commande :</span>
                        <span class="font-bold text-slate-900">{{ $facture->commande->sous_total_formate }}</span>
                    </div>

                    <div class="flex justify-between text-slate-600">
                        <span>Frais de livraison ({{ $facture->commande->quartier ?? 'Lomé' }}) :</span>
                        <span class="font-bold text-slate-900">{{ $facture->commande->frais_livraison_formate }}</span>
                    </div>

                    <div class="flex justify-between text-base font-black text-slate-900 border-t-2 border-stone-800 pt-3">
                        <span>MONTANT TOTAL :</span>
                        <span class="text-red-900">{{ $facture->montant_formate }}</span>
                    </div>
                </div>
            </div>

            <!-- Footer Notes -->
            <div class="border-t border-stone-200 pt-6 text-[11px] text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p>Merci pour votre confiance. Café Paris Lomé vous remercie et vous souhaite une très agréable dégustation !</p>
                <div class="p-2 border border-dashed border-stone-300 rounded font-mono text-[10px] text-center">
                    Document certifié conforme • Café Paris Lomé
                </div>
            </div>
        </div>
    </div>

</body>
</html>
