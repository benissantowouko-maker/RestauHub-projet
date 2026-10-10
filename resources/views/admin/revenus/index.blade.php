@extends('layouts.admin')

@section('title', 'Statistiques de Revenus')
@section('page_title', 'Statistiques Financières & Chiffre d’Affaires')

@section('content')
<div class="space-y-6">

    <!-- KPI Totals Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800 shadow">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">CA Aujourd'hui</span>
            <span class="text-2xl font-black text-white mt-1 block">{{ number_format($caAujourdhui, 0, ',', ' ') }} FCFA</span>
            <span class="text-[11px] text-emerald-400">Journée en cours</span>
        </div>

        <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800 shadow">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">CA Cette Semaine</span>
            <span class="text-2xl font-black text-amber-400 mt-1 block">{{ number_format($caSemaine, 0, ',', ' ') }} FCFA</span>
            <span class="text-[11px] text-slate-400">Depuis lundi</span>
        </div>

        <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800 shadow">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">CA Ce Mois</span>
            <span class="text-2xl font-black text-white mt-1 block">{{ number_format($caMois, 0, ',', ' ') }} FCFA</span>
            <span class="text-[11px] text-slate-400">{{ now()->translatedFormat('F Y') }}</span>
        </div>

        <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800 shadow">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Panier Moyen</span>
            <span class="text-2xl font-black text-indigo-400 mt-1 block">{{ number_format($panierMoyen, 0, ',', ' ') }} FCFA</span>
            <span class="text-[11px] text-slate-400">{{ $totalCommandes }} commande(s) totale(s)</span>
        </div>
    </div>

    <!-- 7-Day Trend & Payment Mode Distribution -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- 7 Days Trend Visual Bars -->
        <div class="lg:col-span-8 bg-slate-900 rounded-3xl border border-slate-800 p-6 space-y-4">
            <div class="border-b border-slate-800 pb-3">
                <h3 class="font-bold text-white text-base flex items-center gap-2">
                    <i data-lucide="trending-up" class="w-4 h-4 text-emerald-400"></i>
                    <span>Tendance des Ventes (7 Derniers Jours)</span>
                </h3>
                <p class="text-xs text-slate-400">Évolution du chiffre d'affaires quotidien</p>
            </div>

            <div class="space-y-3 pt-2">
                @php
                    $maxCa = $ventes7Jours->max('ca') ?: 1;
                @endphp

                @forelse($ventes7Jours as $v)
                    @php
                        $pct = min(100, round(($v->ca / $maxCa) * 100));
                    @endphp
                    <div class="space-y-1">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-slate-300">{{ \Carbon\Carbon::parse($v->date)->translatedFormat('l d/m') }}</span>
                            <span class="font-mono font-bold text-amber-400">{{ number_format($v->ca, 0, ',', ' ') }} FCFA ({{ $v->total_commandes }} cmd)</span>
                        </div>
                        <div class="w-full bg-slate-800 h-3 rounded-full overflow-hidden">
                            <div class="bg-gradient-to-r from-red-700 to-amber-500 h-3 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-500 py-6 text-center">Aucune vente enregistrée sur la période.</p>
                @endforelse
            </div>
        </div>

        <!-- Payment Mode Breakdown -->
        <div class="lg:col-span-4 bg-slate-900 rounded-3xl border border-slate-800 p-6 space-y-4">
            <div class="border-b border-slate-800 pb-3">
                <h3 class="font-bold text-white text-base flex items-center gap-2">
                    <i data-lucide="pie-chart" class="w-4 h-4 text-amber-400"></i>
                    <span>Canaux de Paiement</span>
                </h3>
                <p class="text-xs text-slate-400">Répartition du chiffre d'affaires</p>
            </div>

            <div class="space-y-3">
                @foreach($parModePaiement as $mode)
                    <div class="p-3.5 rounded-2xl bg-slate-800/80 border border-slate-750 flex items-center justify-between">
                        <div>
                            <p class="font-bold text-white text-xs">
                                {{ match($mode->mode_paiement) {
                                    'especes_livraison' => '💵 Espèces à la livraison',
                                    't_money' => '📱 T-Money (Togocom)',
                                    'flooz' => '⚡ Flooz (Moov Togo)',
                                    default => ucfirst($mode->mode_paiement)
                                } }}
                            </p>
                            <span class="text-[11px] text-slate-400">{{ $mode->count }} transaction(s)</span>
                        </div>
                        <span class="font-mono font-bold text-amber-400 text-xs">{{ number_format($mode->total, 0, ',', ' ') }} F</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Top 10 Best Sellers Table -->
    <div class="bg-slate-900 rounded-3xl border border-slate-800 p-6 space-y-4">
        <div class="border-b border-slate-800 pb-3">
            <h3 class="font-bold text-white text-base flex items-center gap-2">
                <i data-lucide="award" class="w-4 h-4 text-amber-400"></i>
                <span>Top 10 des Plats les plus Rentables</span>
            </h3>
            <p class="text-xs text-slate-400">Volumes vendus et volume financier généré</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-400 border-b border-slate-800 uppercase tracking-wider text-[11px]">
                        <th class="py-3 px-3">Rang</th>
                        <th class="py-3 px-3">Désignation du Plat</th>
                        <th class="py-3 px-3 text-center">Quantités Vendues</th>
                        <th class="py-3 px-3 text-right">Chiffre d'Affaires Généré</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($topPlats as $idx => $p)
                        <tr class="hover:bg-slate-800/40 transition">
                            <td class="py-3 px-3 font-bold text-amber-400">#{{ $idx + 1 }}</td>
                            <td class="py-3 px-3 font-bold text-white">{{ $p->nom_produit }}</td>
                            <td class="py-3 px-3 text-center text-slate-300 font-semibold">{{ $p->total_vendu }} portion(s)</td>
                            <td class="py-3 px-3 text-right font-mono font-bold text-amber-400 text-sm">
                                {{ number_format($p->total_ca, 0, ',', ' ') }} FCFA
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-500">Aucune donnée de vente cumulée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
