@extends('layouts.app')

@section('title', 'Mon Compte Client — Café Paris Lomé')

@section('content')
<div class="py-12 bg-[#FAF7F2]">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- User Header Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-red-900 text-amber-300 font-bold text-2xl flex items-center justify-center shadow-md">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-xl sm:text-2xl font-serif font-bold text-slate-900">{{ $user->name }}</h1>
                        <span class="text-xs font-semibold px-2 py-0.5 rounded bg-stone-100 text-slate-600 uppercase">{{ $user->role }}</span>
                    </div>
                    <p class="text-xs text-slate-500 mt-1 flex items-center gap-3">
                        <span><i data-lucide="mail" class="w-3.5 h-3.5 inline mr-1"></i>{{ $user->email }}</span>
                        <span><i data-lucide="phone" class="w-3.5 h-3.5 inline mr-1"></i>{{ $user->telephone ?? 'Non renseigné' }}</span>
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('menu.index') }}" class="px-5 py-2.5 rounded-xl bg-red-800 hover:bg-red-900 text-white text-xs font-bold shadow transition flex items-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>Nouvelle commande</span>
                </a>
            </div>
        </div>

        <!-- Orders History -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-stone-100 pb-4">
                <div>
                    <h2 class="text-lg font-serif font-bold text-slate-900">Historique de vos Commandes</h2>
                    <p class="text-xs text-slate-500">Retrouvez le suivi en temps réel et téléchargez vos factures</p>
                </div>
                <span class="text-xs font-bold text-red-900 bg-red-50 px-3 py-1 rounded-full">
                    {{ $commandes->total() }} commande(s)
                </span>
            </div>

            @if($commandes->isEmpty())
                <div class="py-12 text-center text-slate-400 space-y-3">
                    <i data-lucide="receipt" class="w-12 h-12 mx-auto text-stone-300"></i>
                    <p class="text-sm font-bold text-slate-700">Vous n'avez pas encore passé de commande.</p>
                    <a href="{{ route('menu.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-red-800 text-white text-xs font-bold shadow">
                        Commander dès maintenant
                    </a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($commandes as $cmd)
                        <div class="p-5 rounded-2xl bg-stone-50 border border-stone-200 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:border-red-200 transition">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-sm text-slate-900">#{{ $cmd->code_commande }}</span>
                                    <span class="px-2.5 py-0.5 rounded text-[11px] font-bold border {{ $cmd->statut_badge_class }}">
                                        {{ $cmd->statut_label }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-500">
                                    Passée le {{ $cmd->created_at->format('d/m/Y à H:i') }} • {{ $cmd->lignes->sum('quantite') }} article(s)
                                </p>
                                <p class="text-xs text-slate-600 line-clamp-1">
                                    {{ $cmd->lignes->pluck('nom_produit')->implode(', ') }}
                                </p>
                            </div>

                            <div class="flex items-center justify-between md:justify-end gap-4 border-t md:border-t-0 pt-3 md:pt-0 border-stone-200">
                                <div class="text-right">
                                    <span class="text-xs text-slate-500 block">Total</span>
                                    <span class="text-sm font-extrabold text-red-900">{{ $cmd->montant_total_formate }}</span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <a href="{{ route('suivi.commande', $cmd->tracking_token) }}"
                                       class="px-3.5 py-2 rounded-xl bg-white border border-stone-300 hover:border-red-800 text-slate-800 text-xs font-bold shadow-sm transition flex items-center gap-1.5">
                                        <i data-lucide="eye" class="w-3.5 h-3.5 text-red-800"></i>
                                        <span>Suivi</span>
                                    </a>

                                    @if($cmd->facture)
                                        <a href="{{ route('facture.show', $cmd->facture->numero_facture) }}" target="_blank"
                                           class="p-2 rounded-xl bg-white border border-stone-300 hover:bg-stone-100 text-slate-700 transition"
                                           title="Facture PDF">
                                            <i data-lucide="file-text" class="w-4 h-4 text-red-800"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $commandes->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
