@extends('layouts.admin')

@section('title', 'Gestion des Factures RAF')
@section('page_title', 'Facturation Officielle — Format RAF-2026-XXXX')

@section('content')
<div class="space-y-6">

    <!-- KPI Summary & Filter Bar -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800 shadow">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Facturé</span>
            <span class="text-2xl font-black text-amber-400 mt-1 block">{{ number_format($totalFacture, 0, ',', ' ') }} FCFA</span>
            <span class="text-[11px] text-slate-400">Toutes factures confondues</span>
        </div>

        <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800 shadow">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Factures du Jour</span>
            <span class="text-2xl font-black text-white mt-1 block">{{ $facturesAujourdhuiCount }}</span>
            <span class="text-[11px] text-emerald-400">Émises aujourd'hui</span>
        </div>

        <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800 shadow flex flex-col justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Conformité Fiscale</span>
            <span class="text-xs text-slate-300 font-semibold">Numérotation séquentielle certifiée Togo</span>
            <span class="text-[10px] text-slate-500 font-mono">Format : RAF-AAAA-XXXX</span>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-slate-900 rounded-2xl border border-slate-800 p-4">
        <form method="GET" action="{{ route('admin.factures.index') }}" class="flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="relative w-full sm:w-80">
                <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Rechercher n° facture (RAF-...), nom client..."
                       class="w-full pl-10 pr-4 py-2 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white placeholder-slate-400 focus:border-red-800 outline-none">
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <select name="periode" onchange="this.form.submit()" class="w-full sm:w-auto px-3.5 py-2 rounded-xl bg-slate-800 border border-slate-700 text-xs text-slate-200 outline-none">
                    <option value="toutes" {{ $periode === 'toutes' ? 'selected' : '' }}>Toutes les factures</option>
                    <option value="aujourdhui" {{ $periode === 'aujourdhui' ? 'selected' : '' }}>Aujourd'hui</option>
                    <option value="7jours" {{ $periode === '7jours' ? 'selected' : '' }}>7 derniers jours</option>
                    <option value="mois" {{ $periode === 'mois' ? 'selected' : '' }}>Ce mois</option>
                </select>

                @if($search || $periode !== 'toutes')
                    <a href="{{ route('admin.factures.index') }}" class="p-2 rounded-xl bg-slate-800 text-slate-400 hover:text-white">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Factures Table -->
    <div class="bg-slate-900 rounded-3xl border border-slate-800 p-6 space-y-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-400 border-b border-slate-800 uppercase tracking-wider text-[11px]">
                        <th class="py-3 px-3">Numéro Facture</th>
                        <th class="py-3 px-3">Date Émission</th>
                        <th class="py-3 px-3">N° Commande</th>
                        <th class="py-3 px-3">Client</th>
                        <th class="py-3 px-3">Montant Total</th>
                        <th class="py-3 px-3">Statut Envoi</th>
                        <th class="py-3 px-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($factures as $f)
                        <tr class="hover:bg-slate-800/40 transition">
                            <td class="py-3.5 px-3 font-mono font-bold text-amber-400">
                                <a href="{{ route('facture.show', $f->numero_facture) }}" target="_blank" class="hover:underline">
                                    {{ $f->numero_facture }}
                                </a>
                            </td>
                            <td class="py-3.5 px-3 text-slate-400">
                                {{ $f->date_emission->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-3.5 px-3">
                                <a href="{{ route('admin.commandes.show', $f->commande->id) }}" class="font-mono text-white hover:text-amber-400">
                                    #{{ $f->commande->code_commande }}
                                </a>
                            </td>
                            <td class="py-3.5 px-3">
                                <p class="font-bold text-white">{{ $f->commande->nom_client }}</p>
                                <p class="text-[11px] text-slate-400">{{ $f->commande->telephone_client }}</p>
                            </td>
                            <td class="py-3.5 px-3 font-extrabold text-white text-sm">
                                {{ $f->montant_formate }}
                            </td>
                            <td class="py-3.5 px-3">
                                <span class="px-2.5 py-0.5 rounded text-[11px] font-bold border {{ $f->statut_badge_class }}">
                                    {{ $f->statut_label }}
                                </span>
                            </td>
                            <td class="py-3.5 px-3 text-right space-x-1">
                                <a href="{{ route('facture.show', $f->numero_facture) }}" target="_blank"
                                   class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 inline-flex items-center gap-1 font-bold text-xs"
                                   title="Imprimer / PDF">
                                    <i data-lucide="printer" class="w-3.5 h-3.5 text-amber-400"></i>
                                    <span>Imprimer / PDF</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-500">
                                Aucune facture ne correspond aux critères de recherche.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-4 border-t border-slate-800">
            {{ $factures->links() }}
        </div>
    </div>

</div>
@endsection
