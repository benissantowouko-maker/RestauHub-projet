@extends('layouts.admin')

@section('title', 'Tableau de Bord')
@section('page_title', 'Tableau de Bord — Vue d’ensemble')

@section('content')
<div class="space-y-6">

    <!-- KPI Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Chiffre d'Affaires du Jour -->
        <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800 shadow-sm relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">CA Aujourd'hui</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center">
                    <i data-lucide="wallet" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="text-2xl font-black text-white">{{ number_format($revenusAujourdhui, 0, ',', ' ') }} FCFA</span>
                <span class="text-[11px] text-emerald-400 block mt-0.5">Semaine : {{ number_format($revenusSemaine, 0, ',', ' ') }} FCFA</span>
            </div>
        </div>

        <!-- Commandes en Attente (Alerte prioritaire) -->
        <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800 shadow-sm relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">À Valider / En Attente</span>
                <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-400 flex items-center justify-center">
                    <i data-lucide="bell-ring" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-3xl font-black {{ $commandesEnAttente > 0 ? 'text-amber-400 animate-pulse' : 'text-white' }}">
                    {{ $commandesEnAttente }}
                </span>
                <a href="{{ route('admin.commandes.index', ['statut' => 'en_attente']) }}" class="text-xs font-bold text-amber-400 hover:underline">
                    Traiter →
                </a>
            </div>
        </div>

        <!-- Commandes Aujourd'hui -->
        <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800 shadow-sm relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Commandes du Jour</span>
                <div class="w-8 h-8 rounded-lg bg-red-500/10 text-red-400 flex items-center justify-center">
                    <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="text-3xl font-black text-white">{{ $commandesAujourdhui }}</span>
                <span class="text-[11px] text-slate-400 block mt-0.5">{{ $commandesLivreesAujourdhui }} livrée(s) aujourd'hui</span>
            </div>
        </div>

        <!-- Panier Moyen -->
        <div class="p-5 rounded-2xl bg-slate-900 border border-slate-800 shadow-sm relative overflow-hidden group">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Panier Moyen</span>
                <div class="w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-400 flex items-center justify-center">
                    <i data-lucide="receipt" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="text-2xl font-black text-white">{{ number_format($panierMoyen, 0, ',', ' ') }} FCFA</span>
                <span class="text-[11px] text-slate-400 block mt-0.5">Par commande client</span>
            </div>
        </div>
    </div>

    <!-- Secondary Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <a href="{{ route('admin.reservations.index') }}" class="p-4 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-between hover:border-slate-700 transition">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-900/50 text-purple-400 flex items-center justify-center font-bold">
                    <i data-lucide="calendar" class="w-5 h-5"></i>
                </div>
                <div>
                    <span class="text-xs text-slate-400 font-bold uppercase">Réservations Aujourd'hui</span>
                    <p class="text-sm font-bold text-white">{{ $reservationsAujourdhui }} réservation(s) de table</p>
                </div>
            </div>
            <span class="text-xs font-bold text-purple-400">Consulter →</span>
        </a>

        <a href="{{ route('admin.messages.index') }}" class="p-4 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-between hover:border-slate-700 transition">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-900/50 text-blue-400 flex items-center justify-center font-bold">
                    <i data-lucide="mail" class="w-5 h-5"></i>
                </div>
                <div>
                    <span class="text-xs text-slate-400 font-bold uppercase">Messages Entrants</span>
                    <p class="text-sm font-bold text-white">{{ $messagesNonLus }} message(s) non lu(s)</p>
                </div>
            </div>
            <span class="text-xs font-bold text-blue-400">Lire →</span>
        </a>
    </div>

    <!-- Main Content: Recent Orders & Top Selling Dishes -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- Recent Orders Table -->
        <div class="lg:col-span-8 bg-slate-900 rounded-3xl border border-slate-800 p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                <div>
                    <h2 class="text-base font-bold text-white flex items-center gap-2">
                        <i data-lucide="activity" class="w-4 h-4 text-amber-400"></i>
                        <span>Dernières Commandes Clients</span>
                    </h2>
                    <p class="text-xs text-slate-400">Mises à jour des statuts en direct</p>
                </div>
                <a href="{{ route('admin.commandes.index') }}" class="text-xs font-bold text-amber-400 hover:underline">
                    Toutes les commandes →
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-slate-400 border-b border-slate-800 uppercase tracking-wider text-[11px]">
                            <th class="py-3 px-2">Code</th>
                            <th class="py-3 px-2">Client / Contact</th>
                            <th class="py-3 px-2">Quartier</th>
                            <th class="py-3 px-2">Montant</th>
                            <th class="py-3 px-2">Statut</th>
                            <th class="py-3 px-2 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @forelse($recentOrders as $order)
                            <tr class="hover:bg-slate-800/40 transition">
                                <td class="py-3.5 px-2 font-mono font-bold text-white">
                                    <a href="{{ route('admin.commandes.show', $order->id) }}" class="hover:text-amber-400">
                                        #{{ $order->code_commande }}
                                    </a>
                                </td>
                                <td class="py-3.5 px-2">
                                    <p class="font-bold text-slate-200">{{ $order->nom_client }}</p>
                                    <p class="text-[11px] text-slate-400">{{ $order->telephone_client }}</p>
                                </td>
                                <td class="py-3.5 px-2 text-slate-300">
                                    {{ $order->quartier ?? 'Lomé' }}
                                </td>
                                <td class="py-3.5 px-2 font-bold text-amber-400">
                                    {{ $order->montant_total_formate }}
                                </td>
                                <td class="py-3.5 px-2">
                                    <span class="inline-block px-2.5 py-0.5 rounded text-[10px] font-bold border {{ $order->statut_badge_class }}">
                                        {{ $order->statut_label }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-2 text-right">
                                    <a href="{{ route('admin.commandes.show', $order->id) }}"
                                       class="px-2.5 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs inline-flex items-center gap-1">
                                        <span>Gérer</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-500">
                                    Aucune commande enregistrée pour le moment.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Selling Dishes Sidebar -->
        <div class="lg:col-span-4 bg-slate-900 rounded-3xl border border-slate-800 p-6 space-y-4">
            <div class="border-b border-slate-800 pb-4">
                <h2 class="text-base font-bold text-white flex items-center gap-2">
                    <i data-lucide="flame" class="w-4 h-4 text-amber-400"></i>
                    <span>Plats les plus Vendus</span>
                </h2>
                <p class="text-xs text-slate-400">Palmarès des commandes</p>
            </div>

            <div class="space-y-3">
                @forelse($topPlats as $index => $top)
                    <div class="p-3 rounded-2xl bg-slate-800/60 border border-slate-750 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 rounded-lg bg-slate-700 text-amber-400 font-bold text-xs flex items-center justify-center">
                                #{{ $index + 1 }}
                            </span>
                            <div>
                                <p class="font-bold text-white truncate max-w-[150px]">{{ $top->nom_produit }}</p>
                                <span class="text-[11px] text-slate-400">{{ $top->total_vendu }} portion(s) vendue(s)</span>
                            </div>
                        </div>
                        <span class="font-bold text-amber-400">{{ number_format($top->total_ca, 0, ',', ' ') }} F</span>
                    </div>
                @empty
                    <p class="text-xs text-slate-500 text-center py-6">Données en cours de cumul.</p>
                @endforelse
            </div>

            <div class="pt-2 border-t border-slate-800">
                <a href="{{ route('admin.revenus.index') }}" class="w-full py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-center block text-xs font-bold text-slate-300 transition">
                    Voir les statistiques financières complètes →
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
