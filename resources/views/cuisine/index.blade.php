<!DOCTYPE html>
<html lang="fr" class="h-full bg-slate-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Écran Cuisine (KDS) — Café Paris Lomé</title>

    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full text-slate-100 flex flex-col font-sans antialiased selection:bg-amber-500 selection:text-slate-950"
      x-data="{
          startPolling() {
              setInterval(() => window.location.reload(), 15000);
          }
      }"
      x-init="startPolling()">

    <!-- Header KDS -->
    <header class="bg-slate-900 border-b border-slate-800 px-6 py-4 flex items-center justify-between shadow-lg shrink-0">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-amber-500 to-amber-700 flex items-center justify-center text-slate-950 font-bold shadow-md">
                <i data-lucide="chef-hat" class="w-6 h-6"></i>
            </div>
            <div>
                <h1 class="text-lg font-bold text-white flex items-center gap-2">
                    <span>Écran Cuisine & KDS</span>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                </h1>
                <p class="text-xs text-amber-400 font-semibold">Café Paris Lomé • Poste Chef de Brigade</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <span class="text-xs text-slate-400 hidden sm:inline">Actualisation automatique toutes les 15s</span>
            <button onclick="window.location.reload()" class="p-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 transition">
                <i data-lucide="refresh-cw" class="w-4 h-4"></i>
            </button>
            <a href="{{ route('home') }}" class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-semibold text-slate-300">
                Site
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="p-2.5 rounded-xl bg-slate-800 hover:bg-rose-950 text-slate-400 hover:text-rose-300 transition">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </header>

    <!-- Main KDS 3 Columns Board -->
    <main class="flex-1 overflow-x-auto p-6 grid grid-cols-1 md:grid-cols-3 gap-6 min-h-0 bg-slate-950">

        <!-- Column 1: À Préparer (En attente & Confirmées) -->
        <div class="flex flex-col bg-slate-900/60 rounded-3xl border border-slate-800 p-4 overflow-hidden">
            <div class="flex items-center justify-between pb-4 border-b border-slate-800 mb-4 shrink-0">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                    <h2 class="font-bold text-white text-base">À Préparer</h2>
                </div>
                <span class="px-2.5 py-0.5 rounded-full bg-blue-900/50 text-blue-300 text-xs font-bold border border-blue-700/50">
                    {{ $aPreparer->count() }}
                </span>
            </div>

            <div class="flex-1 overflow-y-auto space-y-4 pr-1">
                @forelse($aPreparer as $cmd)
                    <div class="p-4 rounded-2xl bg-slate-850 bg-slate-800/90 border border-slate-700 space-y-3 shadow-md hover:border-blue-500/50 transition">
                        <div class="flex items-start justify-between">
                            <div>
                                <span class="text-xs font-bold font-mono text-blue-400">#{{ $cmd->code_commande }}</span>
                                <h3 class="text-sm font-bold text-white">{{ $cmd->nom_client }}</h3>
                            </div>
                            <span class="text-[11px] font-semibold text-slate-400 flex items-center gap-1">
                                <i data-lucide="clock" class="w-3 h-3 text-amber-400"></i>
                                {{ $cmd->created_at->diffForHumans(null, true) }}
                            </span>
                        </div>

                        <!-- Dishes list -->
                        <div class="space-y-1.5 text-xs divide-y divide-slate-700/50">
                            @foreach($cmd->lignes as $l)
                                <div class="pt-1.5 flex items-start gap-2">
                                    <span class="w-6 h-6 rounded bg-slate-700 text-amber-400 font-bold flex items-center justify-center shrink-0 text-xs">
                                        {{ $l->quantite }}x
                                    </span>
                                    <div class="flex-1">
                                        <p class="font-bold text-slate-100">{{ $l->nom_produit }}</p>
                                        @if($l->instructions_speciales)
                                            <p class="text-[11px] text-amber-300 italic">📌 {{ $l->instructions_speciales }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Action -->
                        <form method="POST" action="{{ route('cuisine.update_status', $cmd->id) }}" class="pt-2">
                            @csrf
                            <input type="hidden" name="statut" value="en_preparation">
                            <button type="submit" class="w-full py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs shadow transition flex items-center justify-center gap-2">
                                <i data-lucide="flame" class="w-4 h-4 text-amber-300"></i>
                                <span>Lancer la préparation</span>
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="h-full flex flex-col items-center justify-center text-slate-500 py-12 text-center">
                        <i data-lucide="check" class="w-8 h-8 text-slate-700 mb-2"></i>
                        <p class="text-xs">Aucune commande en attente de préparation.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Column 2: En cours de Préparation (Sur le feu) -->
        <div class="flex flex-col bg-slate-900/60 rounded-3xl border border-slate-800 p-4 overflow-hidden">
            <div class="flex items-center justify-between pb-4 border-b border-slate-800 mb-4 shrink-0">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-amber-500 animate-pulse"></span>
                    <h2 class="font-bold text-white text-base">Sur le Feu / En Cuisine</h2>
                </div>
                <span class="px-2.5 py-0.5 rounded-full bg-amber-900/50 text-amber-300 text-xs font-bold border border-amber-700/50">
                    {{ $enPreparation->count() }}
                </span>
            </div>

            <div class="flex-1 overflow-y-auto space-y-4 pr-1">
                @forelse($enPreparation as $cmd)
                    <div class="p-4 rounded-2xl bg-slate-800 border-2 border-amber-500/80 space-y-3 shadow-xl">
                        <div class="flex items-start justify-between">
                            <div>
                                <span class="text-xs font-bold font-mono text-amber-400">#{{ $cmd->code_commande }}</span>
                                <h3 class="text-sm font-bold text-white">{{ $cmd->nom_client }}</h3>
                            </div>
                            <span class="text-[11px] font-bold text-amber-400 bg-amber-950/60 px-2 py-0.5 rounded border border-amber-700/50">
                                Cuisson en cours
                            </span>
                        </div>

                        <!-- Dishes list -->
                        <div class="space-y-1.5 text-xs divide-y divide-slate-700/50">
                            @foreach($cmd->lignes as $l)
                                <div class="pt-1.5 flex items-start gap-2">
                                    <span class="w-6 h-6 rounded bg-amber-500 text-slate-950 font-black flex items-center justify-center shrink-0 text-xs">
                                        {{ $l->quantite }}x
                                    </span>
                                    <div class="flex-1">
                                        <p class="font-bold text-white">{{ $l->nom_produit }}</p>
                                        @if($l->instructions_speciales)
                                            <p class="text-[11px] text-amber-300 italic">📌 {{ $l->instructions_speciales }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Action -->
                        <form method="POST" action="{{ route('cuisine.update_status', $cmd->id) }}" class="pt-2">
                            @csrf
                            <input type="hidden" name="statut" value="prete">
                            <button type="submit" class="w-full py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-black text-xs shadow-lg transition flex items-center justify-center gap-2">
                                <i data-lucide="bell" class="w-4 h-4 text-amber-300"></i>
                                <span>Marquer PRÊTE (Appeler livreur)</span>
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="h-full flex flex-col items-center justify-center text-slate-500 py-12 text-center">
                        <i data-lucide="flame" class="w-8 h-8 text-slate-700 mb-2"></i>
                        <p class="text-xs">Aucun plat en cours de cuisson actuellement.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Column 3: Prêtes (Sous cloche pour le coursier) -->
        <div class="flex flex-col bg-slate-900/60 rounded-3xl border border-slate-800 p-4 overflow-hidden">
            <div class="flex items-center justify-between pb-4 border-b border-slate-800 mb-4 shrink-0">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-purple-500"></span>
                    <h2 class="font-bold text-white text-base">Prêtes / Expédition</h2>
                </div>
                <span class="px-2.5 py-0.5 rounded-full bg-purple-900/50 text-purple-300 text-xs font-bold border border-purple-700/50">
                    {{ $pretes->count() }}
                </span>
            </div>

            <div class="flex-1 overflow-y-auto space-y-4 pr-1">
                @forelse($pretes as $cmd)
                    <div class="p-4 rounded-2xl bg-slate-800/80 border border-slate-700 space-y-2 opacity-90">
                        <div class="flex items-start justify-between">
                            <div>
                                <span class="text-xs font-bold font-mono text-purple-400">#{{ $cmd->code_commande }}</span>
                                <h3 class="text-xs font-bold text-white">{{ $cmd->nom_client }}</h3>
                            </div>
                            <span class="text-[10px] px-2 py-0.5 rounded bg-purple-950 text-purple-300 border border-purple-800 font-semibold">
                                En attente coursier
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-400">
                            {{ $cmd->lignes->sum('quantite') }} plat(s) prêt(s) • {{ $cmd->quartier ?? 'Lomé' }}
                        </p>
                    </div>
                @empty
                    <div class="h-full flex flex-col items-center justify-center text-slate-500 py-12 text-center">
                        <i data-lucide="package-check" class="w-8 h-8 text-slate-700 mb-2"></i>
                        <p class="text-xs">Aucune commande en attente d'expédition.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) lucide.createIcons();
        });
    </script>
</body>
</html>
