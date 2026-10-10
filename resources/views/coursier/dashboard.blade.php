@extends('layouts.coursier')

@section('title', 'Missions de Livraison — Café Paris Lomé')

@section('content')
<div class="space-y-5" x-data="{ tab: 'pretes' }">

    <!-- KPI Summary Pill -->
    <div class="grid grid-cols-2 gap-3">
        <div class="p-4 rounded-2xl bg-slate-800/80 border border-slate-700/60 shadow">
            <span class="text-[11px] font-bold uppercase text-slate-400 block">Livrées aujourd'hui</span>
            <span class="text-2xl font-black text-white mt-1 block">{{ $livraisonsAujourdhui }}</span>
            <span class="text-[10px] text-emerald-400 font-semibold">Courses effectuées</span>
        </div>

        <div class="p-4 rounded-2xl bg-slate-800/80 border border-slate-700/60 shadow">
            <span class="text-[11px] font-bold uppercase text-slate-400 block">Espèces encaissées</span>
            <span class="text-xl font-black text-amber-400 mt-1 block">{{ number_format($encaissementsAujourdhui, 0, ',', ' ') }} FCFA</span>
            <span class="text-[10px] text-slate-400 font-medium">À reverser en caisse</span>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex items-center p-1 rounded-xl bg-slate-800/80 border border-slate-700 text-xs font-bold">
        <button type="button" @click="tab = 'pretes'"
                class="flex-1 py-2.5 rounded-lg transition flex items-center justify-center gap-1.5"
                :class="tab === 'pretes' ? 'bg-indigo-600 text-white shadow' : 'text-slate-400 hover:text-white'">
            <span>Prêtes en cuisine</span>
            <span class="px-1.5 py-0.2 rounded-full bg-slate-900 text-white text-[10px] font-extrabold">{{ $commandesPretes->count() }}</span>
        </button>

        <button type="button" @click="tab = 'en_cours'"
                class="flex-1 py-2.5 rounded-lg transition flex items-center justify-center gap-1.5"
                :class="tab === 'en_cours' ? 'bg-indigo-600 text-white shadow' : 'text-slate-400 hover:text-white'">
            <span>En route</span>
            <span class="px-1.5 py-0.2 rounded-full bg-amber-400 text-slate-950 text-[10px] font-extrabold">{{ $commandesEnCours->count() }}</span>
        </button>

        <button type="button" @click="tab = 'historique'"
                class="flex-1 py-2.5 rounded-lg transition flex items-center justify-center gap-1.5"
                :class="tab === 'historique' ? 'bg-indigo-600 text-white shadow' : 'text-slate-400 hover:text-white'">
            <span>Historique</span>
        </button>
    </div>

    <!-- TAB 1: Commandes Prêtes en Cuisine -->
    <div x-show="tab === 'pretes'" class="space-y-3">
        @if($commandesPretes->isEmpty())
            <div class="p-8 rounded-2xl bg-slate-800/40 border border-slate-800 text-center text-slate-400 space-y-2">
                <i data-lucide="coffee" class="w-10 h-10 mx-auto text-slate-600"></i>
                <p class="text-sm font-bold text-slate-300">Aucune commande en attente de départ</p>
                <p class="text-xs text-slate-500">Dès qu'un plat est marqué « Prêt » en cuisine, il apparaîtra ici instantanément.</p>
            </div>
        @else
            @foreach($commandesPretes as $cmd)
                <div class="p-4 rounded-2xl bg-slate-800 border border-slate-700/80 shadow-md space-y-3 hover:border-indigo-500/50 transition">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded bg-purple-900/60 text-purple-300 border border-purple-700/50">
                                Prête en cuisine
                            </span>
                            <h3 class="text-base font-bold text-white mt-1">#{{ $cmd->code_commande }}</h3>
                            <p class="text-xs text-slate-400">{{ $cmd->nom_client }} • {{ $cmd->telephone_client }}</p>
                        </div>
                        <span class="text-sm font-black text-amber-400">{{ $cmd->montant_total_formate }}</span>
                    </div>

                    <div class="p-3 rounded-xl bg-slate-900/80 border border-slate-750 text-xs space-y-1">
                        <p class="text-slate-300 flex items-center gap-1.5 font-medium">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-indigo-400 shrink-0"></i>
                            <span>{{ $cmd->adresse_livraison ?? 'Lomé' }} ({{ $cmd->quartier ?? 'Centre' }})</span>
                        </p>
                        @if($cmd->note_livreur)
                            <p class="text-amber-300 font-semibold text-[11px] flex items-start gap-1">
                                <i data-lucide="info" class="w-3 h-3 shrink-0 mt-0.5"></i>
                                <span>Note : {{ $cmd->note_livreur }}</span>
                            </p>
                        @endif
                    </div>

                    <div class="flex items-center gap-2 pt-1">
                        <a href="{{ route('coursier.show', $cmd->id) }}" class="flex-1 py-2.5 rounded-xl bg-slate-700 hover:bg-slate-600 text-white text-xs font-bold text-center transition">
                            Voir le détail & GPS
                        </a>

                        @if(!$cmd->livreur_id || $cmd->livreur_id === auth()->id())
                            <form method="POST" action="{{ route('coursier.start', $cmd->id) }}" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-extrabold shadow transition flex items-center justify-center gap-1.5">
                                    <i data-lucide="bike" class="w-3.5 h-3.5"></i>
                                    <span>Partir livrer</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <!-- TAB 2: Livraisons En Route -->
    <div x-show="tab === 'en_cours'" class="space-y-3" x-cloak>
        @if($commandesEnCours->isEmpty())
            <div class="p-8 rounded-2xl bg-slate-800/40 border border-slate-800 text-center text-slate-400 space-y-2">
                <i data-lucide="navigation" class="w-10 h-10 mx-auto text-slate-600"></i>
                <p class="text-sm font-bold text-slate-300">Aucune livraison en cours de trajet</p>
                <p class="text-xs text-slate-500">Sélectionnez une commande dans l'onglet « Prêtes » pour démarrer la course.</p>
            </div>
        @else
            @foreach($commandesEnCours as $cmd)
                <div class="p-4 rounded-2xl bg-slate-800 border-2 border-indigo-500 shadow-xl space-y-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded bg-indigo-900 text-indigo-300 border border-indigo-500">
                                En cours d'acheminement
                            </span>
                            <h3 class="text-lg font-bold text-white mt-1">#{{ $cmd->code_commande }} — {{ $cmd->nom_client }}</h3>
                            <p class="text-xs text-slate-400">{{ $cmd->quartier ?? 'Lomé' }}</p>
                        </div>
                        <a href="tel:{{ $cmd->telephone_client }}" class="p-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white shadow-lg shadow-emerald-950/40" title="Appeler le client">
                            <i data-lucide="phone-call" class="w-5 h-5"></i>
                        </a>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-900 border border-slate-700 text-xs space-y-2">
                        <p class="text-slate-200">
                            <strong>Adresse :</strong> {{ $cmd->adresse_livraison }}
                        </p>
                        @if($cmd->note_livreur)
                            <div class="p-2 rounded-lg bg-amber-950/80 border border-amber-600/50 text-amber-300 font-semibold text-xs">
                                📌 <strong>Note client :</strong> {{ $cmd->note_livreur }}
                            </div>
                        @endif

                        <div class="pt-2 border-t border-slate-800 flex justify-between items-center text-xs">
                            <span class="text-slate-400">Règlement :</span>
                            @if($cmd->mode_paiement === 'especes_livraison')
                                <span class="text-amber-400 font-bold bg-amber-950 px-2 py-0.5 rounded border border-amber-600/50">
                                    ENCAISSER : {{ $cmd->montant_total_formate }}
                                </span>
                            @else
                                <span class="text-emerald-400 font-bold">Déjà payé par {{ strtoupper($cmd->mode_paiement) }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <a href="{{ route('coursier.show', $cmd->id) }}" class="flex-1 py-3 rounded-xl bg-slate-700 hover:bg-slate-600 text-white text-xs font-bold text-center transition">
                            Carte GPS
                        </a>

                        <form method="POST" action="{{ route('coursier.complete', $cmd->id) }}" class="flex-1"
                              onsubmit="return confirm('Confirmez-vous que le colis a bien été remis au client ?')">
                            @csrf
                            <button type="submit" class="w-full py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-black shadow-lg shadow-emerald-950/40 transition flex items-center justify-center gap-1.5">
                                <i data-lucide="check-circle" class="w-4 h-4"></i>
                                <span>Livré & Clôturer</span>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <!-- TAB 3: Historique -->
    <div x-show="tab === 'historique'" class="space-y-3" x-cloak>
        @if($commandesHistorique->isEmpty())
            <div class="p-8 rounded-2xl bg-slate-800/40 border border-slate-800 text-center text-slate-400">
                <p class="text-xs">Aucune livraison enregistrée dans votre historique.</p>
            </div>
        @else
            @foreach($commandesHistorique as $h)
                <div class="p-3.5 rounded-xl bg-slate-800/80 border border-slate-750 flex items-center justify-between text-xs">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-white">#{{ $h->code_commande }}</span>
                            <span class="text-[10px] px-2 py-0.2 rounded bg-emerald-950 text-emerald-400 border border-emerald-800">Livrée</span>
                        </div>
                        <p class="text-slate-400 text-[11px] mt-0.5">{{ $h->nom_client }} • {{ $h->date_livraison?->format('H:i') ?? $h->updated_at->format('H:i') }}</p>
                    </div>
                    <span class="font-bold text-amber-400">{{ $h->montant_total_formate }}</span>
                </div>
            @endforeach
        @endif
    </div>

</div>
@endsection
