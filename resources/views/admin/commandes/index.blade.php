@extends('layouts.admin')

@section('title', 'Gestion des Commandes')
@section('page_title', 'Gestion & Traitement des Commandes')

@section('content')
<div class="space-y-6">

    <!-- Filters & Search Toolbar -->
    <div class="bg-slate-900 rounded-2xl border border-slate-800 p-4 space-y-4">
        <!-- Status Tabs -->
        <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar pb-1 text-xs">
            @php
                $statuts = [
                    'tous' => 'Toutes',
                    'en_attente' => 'En attente',
                    'confirmee' => 'Confirmées',
                    'en_preparation' => 'En cuisine',
                    'prete' => 'Prêtes',
                    'en_livraison' => 'En livraison',
                    'livree' => 'Livrées',
                    'annulee' => 'Annulées',
                ];
            @endphp

            @foreach($statuts as $key => $label)
                <a href="{{ route('admin.commandes.index', array_merge(request()->except('statut', 'page'), ['statut' => $key])) }}"
                   class="px-3.5 py-2 rounded-xl font-bold whitespace-nowrap transition flex items-center gap-1.5 {{ $statut === $key ? 'bg-red-800 text-white shadow' : 'bg-slate-800 text-slate-300 hover:bg-slate-700' }}">
                    <span>{{ $label }}</span>
                    @if(isset($counts[$key]) && $counts[$key] > 0)
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $key === 'en_attente' ? 'bg-amber-400 text-slate-950 font-black' : 'bg-slate-900 text-slate-300' }}">
                            {{ $counts[$key] }}
                        </span>
                    @endif
                </a>
            @endforeach
        </div>

        <!-- Secondary Period & Search Bar -->
        <form method="GET" action="{{ route('admin.commandes.index') }}" class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2 border-t border-slate-800">
            <input type="hidden" name="statut" value="{{ $statut }}">

            <!-- Search input -->
            <div class="relative w-full sm:w-80">
                <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Rechercher code, nom client, tél..."
                       class="w-full pl-10 pr-4 py-2 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white placeholder-slate-400 focus:border-red-800 outline-none">
            </div>

            <!-- Period filter -->
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <select name="periode" onchange="this.form.submit()" class="w-full sm:w-auto px-3.5 py-2 rounded-xl bg-slate-800 border border-slate-700 text-xs text-slate-200 focus:border-red-800 outline-none">
                    <option value="toutes" {{ $periode === 'toutes' ? 'selected' : '' }}>Toutes les périodes</option>
                    <option value="aujourdhui" {{ $periode === 'aujourdhui' ? 'selected' : '' }}>Aujourd'hui</option>
                    <option value="semaine" {{ $periode === 'semaine' ? 'selected' : '' }}>Cette semaine</option>
                    <option value="mois" {{ $periode === 'mois' ? 'selected' : '' }}>Ce mois</option>
                </select>

                @if($search || $periode !== 'toutes' || $statut !== 'tous')
                    <a href="{{ route('admin.commandes.index') }}" class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white" title="Réinitialiser filtres">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-slate-900 rounded-3xl border border-slate-800 p-6 space-y-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-400 border-b border-slate-800 uppercase tracking-wider text-[11px]">
                        <th class="py-3 px-3">N° Commande</th>
                        <th class="py-3 px-3">Client & Contact</th>
                        <th class="py-3 px-3">Date & Heure</th>
                        <th class="py-3 px-3">Quartier Lomé</th>
                        <th class="py-3 px-3">Montant</th>
                        <th class="py-3 px-3">Paiement</th>
                        <th class="py-3 px-3">Coursier Assigné</th>
                        <th class="py-3 px-3">Statut Actuel</th>
                        <th class="py-3 px-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($commandes as $cmd)
                        <tr class="hover:bg-slate-800/40 transition">
                            <td class="py-3.5 px-3 font-mono font-bold text-white">
                                <a href="{{ route('admin.commandes.show', $cmd->id) }}" class="hover:text-amber-400">
                                    #{{ $cmd->code_commande }}
                                </a>
                            </td>
                            <td class="py-3.5 px-3">
                                <p class="font-bold text-white">{{ $cmd->nom_client }}</p>
                                <a href="tel:{{ $cmd->telephone_client }}" class="text-[11px] text-slate-400 hover:text-amber-400">
                                    {{ $cmd->telephone_client }}
                                </a>
                            </td>
                            <td class="py-3.5 px-3 text-slate-400">
                                {{ $cmd->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-3.5 px-3 text-slate-300">
                                {{ $cmd->quartier ?? 'Lomé' }}
                            </td>
                            <td class="py-3.5 px-3 font-bold text-amber-400">
                                {{ $cmd->montant_total_formate }}
                            </td>
                            <td class="py-3.5 px-3">
                                <p class="text-slate-200">{{ $cmd->mode_paiement_label }}</p>
                                <span class="text-[10px] font-semibold {{ $cmd->statut_paiement === 'paye' ? 'text-emerald-400' : 'text-amber-400' }}">
                                    {{ strtoupper($cmd->statut_paiement) }}
                                </span>
                            </td>
                            <td class="py-3.5 px-3">
                                <form method="POST" action="{{ route('admin.commandes.assign_driver', $cmd->id) }}">
                                    @csrf
                                    <select name="livreur_id" onchange="this.form.submit()" class="px-2 py-1 rounded-lg bg-slate-800 border border-slate-700 text-[11px] text-slate-200 outline-none">
                                        <option value="">-- Assigner --</option>
                                        @foreach($livreurs as $l)
                                            <option value="{{ $l->id }}" {{ $cmd->livreur_id === $l->id ? 'selected' : '' }}>
                                                {{ $l->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                            <td class="py-3.5 px-3">
                                <!-- Quick Status Changer -->
                                <form method="POST" action="{{ route('admin.commandes.update_status', $cmd->id) }}">
                                    @csrf
                                    <select name="statut" onchange="this.form.submit()"
                                            class="px-2.5 py-1 rounded-lg text-[11px] font-bold border outline-none {{ $cmd->statut_badge_class }}">
                                        <option value="en_attente" {{ $cmd->statut === 'en_attente' ? 'selected' : '' }}>En attente</option>
                                        <option value="confirmee" {{ $cmd->statut === 'confirmee' ? 'selected' : '' }}>Confirmée</option>
                                        <option value="en_preparation" {{ $cmd->statut === 'en_preparation' ? 'selected' : '' }}>En préparation</option>
                                        <option value="prete" {{ $cmd->statut === 'prete' ? 'selected' : '' }}>Prête</option>
                                        <option value="en_livraison" {{ $cmd->statut === 'en_livraison' ? 'selected' : '' }}>En livraison</option>
                                        <option value="livree" {{ $cmd->statut === 'livree' ? 'selected' : '' }}>Livrée</option>
                                        <option value="annulee" {{ $cmd->statut === 'annulee' ? 'selected' : '' }}>Annulée</option>
                                    </select>
                                </form>
                            </td>
                            <td class="py-3.5 px-3 text-right space-x-1">
                                <a href="{{ route('admin.commandes.show', $cmd->id) }}"
                                   class="p-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 inline-block transition" title="Détail complet">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>

                                @if($cmd->facture)
                                    <a href="{{ route('facture.show', $cmd->facture->numero_facture) }}" target="_blank"
                                       class="p-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-amber-400 inline-block transition" title="Facture RAF">
                                        <i data-lucide="file-text" class="w-4 h-4"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center text-slate-500">
                                Aucune commande ne correspond aux critères sélectionnés.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-4 border-t border-slate-800">
            {{ $commandes->links() }}
        </div>
    </div>

</div>
@endsection
