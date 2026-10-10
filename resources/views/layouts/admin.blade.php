<!DOCTYPE html>
<html lang="fr" class="h-full bg-slate-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Administration') — Café Paris Lomé</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>[x-cloak] { display: none !important; }</style>
    @stack('styles')
</head>
<body class="h-full text-slate-100 flex overflow-hidden antialiased font-sans"
      x-data="adminApp()"
      x-init="initAudio(); startPolling()">

    <!-- Left Sidebar -->
    <aside class="w-64 bg-slate-900 border-r border-slate-800 flex flex-col shrink-0 z-30 transition-all duration-300">
        <!-- Logo -->
        <div class="h-20 flex items-center gap-3 px-6 border-b border-slate-800 bg-slate-900/50">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-700 to-red-950 flex items-center justify-center text-amber-400 shadow-md">
                <i data-lucide="utensils-crossed" class="w-5 h-5"></i>
            </div>
            <div>
                <span class="font-bold text-lg text-white tracking-wide block leading-tight">CAFÉ PARIS</span>
                <span class="text-[10px] uppercase font-semibold text-amber-400 tracking-wider">Back-Office Lomé</span>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1 text-sm">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-red-800 text-white font-semibold shadow' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                <i data-lucide="layout-dashboard" class="w-4 h-4 text-amber-400"></i>
                <span>Tableau de Bord</span>
            </a>

            <a href="{{ route('admin.commandes.index') }}"
               class="flex items-center justify-between px-3.5 py-2.5 rounded-xl transition font-medium {{ request()->routeIs('admin.commandes.*') ? 'bg-red-800 text-white font-semibold shadow' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                <div class="flex items-center gap-3">
                    <i data-lucide="shopping-cart" class="w-4 h-4 text-amber-400"></i>
                    <span>Commandes</span>
                </div>
                <template x-if="newOrdersCount > 0">
                    <span class="px-2 py-0.5 rounded-full bg-amber-400 text-slate-950 text-xs font-bold animate-pulse" x-text="newOrdersCount"></span>
                </template>
            </a>

            <a href="{{ route('admin.menus.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition font-medium {{ request()->routeIs('admin.menus.*') ? 'bg-red-800 text-white font-semibold shadow' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                <i data-lucide="book-open" class="w-4 h-4 text-amber-400"></i>
                <span>Menus & Plats</span>
            </a>

            <a href="{{ route('admin.factures.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition font-medium {{ request()->routeIs('admin.factures.*') ? 'bg-red-800 text-white font-semibold shadow' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                <i data-lucide="file-text" class="w-4 h-4 text-amber-400"></i>
                <span>Factures RAF</span>
            </a>

            <a href="{{ route('admin.revenus.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition font-medium {{ request()->routeIs('admin.revenus.*') ? 'bg-red-800 text-white font-semibold shadow' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                <i data-lucide="trending-up" class="w-4 h-4 text-amber-400"></i>
                <span>Revenus & Stats</span>
            </a>

            <a href="{{ route('admin.reservations.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition font-medium {{ request()->routeIs('admin.reservations.*') ? 'bg-red-800 text-white font-semibold shadow' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                <i data-lucide="calendar" class="w-4 h-4 text-amber-400"></i>
                <span>Réservations</span>
            </a>

            <a href="{{ route('admin.messages.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition font-medium {{ request()->routeIs('admin.messages.*') ? 'bg-red-800 text-white font-semibold shadow' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                <i data-lucide="mail" class="w-4 h-4 text-amber-400"></i>
                <span>Messages Client</span>
            </a>

            <a href="{{ route('admin.parametres.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition font-medium {{ request()->routeIs('admin.parametres.*') ? 'bg-red-800 text-white font-semibold shadow' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                <i data-lucide="settings" class="w-4 h-4 text-amber-400"></i>
                <span>Paramètres & Zones</span>
            </a>

            <div class="pt-6 pb-2">
                <p class="px-3 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Autres interfaces</p>
            </div>

            <a href="{{ route('cuisine.dashboard') }}" target="_blank"
               class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-slate-400 hover:text-amber-400 hover:bg-slate-800/50 transition">
                <i data-lucide="chef-hat" class="w-4 h-4"></i>
                <span>Écran Cuisine (KDS)</span>
                <i data-lucide="external-link" class="w-3 h-3 ml-auto text-slate-600"></i>
            </a>

            <a href="{{ route('coursier.dashboard') }}" target="_blank"
               class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-slate-400 hover:text-indigo-400 hover:bg-slate-800/50 transition">
                <i data-lucide="bike" class="w-4 h-4"></i>
                <span>App Coursier (PWA)</span>
                <i data-lucide="external-link" class="w-3 h-3 ml-auto text-slate-600"></i>
            </a>

            <a href="{{ route('home') }}" target="_blank"
               class="flex items-center gap-3 px-3.5 py-2 rounded-xl text-slate-400 hover:text-emerald-400 hover:bg-slate-800/50 transition">
                <i data-lucide="globe" class="w-4 h-4"></i>
                <span>Site Public Client</span>
                <i data-lucide="external-link" class="w-3 h-3 ml-auto text-slate-600"></i>
            </a>
        </nav>

        <!-- User profile in sidebar footer -->
        <div class="p-4 border-t border-slate-800 bg-slate-900/80 flex items-center justify-between">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-9 h-9 rounded-lg bg-red-800 text-amber-300 font-bold flex items-center justify-center text-xs">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="truncate">
                    <p class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-amber-400 font-medium">Administrateur</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Se déconnecter" class="p-2 text-slate-400 hover:text-rose-400 transition">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Container -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-950">
        <!-- Top Bar -->
        <header class="h-20 bg-slate-900/90 border-b border-slate-800 flex items-center justify-between px-6 shrink-0 z-20 backdrop-blur-md">
            <div>
                <h1 class="text-xl font-bold text-white">@yield('page_title', 'Panneau d’administration')</h1>
                <p class="text-xs text-slate-400">Café Paris • Lomé (Togo)</p>
            </div>

            <div class="flex items-center gap-4">
                <!-- Audio Alert Toggle -->
                <button type="button" @click="toggleSound()"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-lg border text-xs font-medium transition"
                        :class="soundEnabled ? 'border-amber-500/50 bg-amber-500/10 text-amber-300' : 'border-slate-700 bg-slate-800/50 text-slate-400'">
                    <i data-lucide="bell" class="w-3.5 h-3.5" x-show="soundEnabled"></i>
                    <i data-lucide="bell-off" class="w-3.5 h-3.5" x-show="!soundEnabled" x-cloak></i>
                    <span x-text="soundEnabled ? 'Alerte sonore active' : 'Alerte muette'"></span>
                </button>

                <!-- New Orders Badge -->
                <div class="relative">
                    <a href="{{ route('admin.commandes.index', ['statut' => 'en_attente']) }}"
                       class="relative p-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 transition flex items-center justify-center">
                        <i data-lucide="bell-ring" class="w-5 h-5"></i>
                        <span x-show="newOrdersCount > 0"
                              x-text="newOrdersCount"
                              class="absolute -top-1 -right-1 bg-amber-400 text-slate-950 text-[10px] font-bold rounded-full h-5 min-w-5 px-1 flex items-center justify-center animate-bounce">
                            0
                        </span>
                    </a>
                </div>

                <!-- Date display -->
                <div class="hidden sm:block text-right border-l border-slate-800 pl-4">
                    <p class="text-xs font-semibold text-slate-200">{{ now()->translatedFormat('l d F Y') }}</p>
                    <p class="text-[11px] text-amber-400">Fuseau Lomé (GMT)</p>
                </div>
            </div>
        </header>

        <!-- New Order Alert Banner -->
        <div x-show="newOrderNotification" x-cloak
             class="bg-amber-500 text-slate-950 px-6 py-3 font-semibold flex items-center justify-between shadow-lg animate-pulse transition">
            <div class="flex items-center gap-3 text-sm">
                <i data-lucide="alert-circle" class="w-5 h-5"></i>
                <span>🔔 NOUVELLE COMMANDE REÇUE : <strong x-text="latestOrderCode"></strong> par <span x-text="latestOrderClient"></span> (<span x-text="latestOrderTotal"></span>)</span>
            </div>
            <div class="flex items-center gap-3">
                <a :href="'{{ url('admin/commandes') }}/' + latestOrderId" class="px-3 py-1 rounded bg-slate-950 text-white text-xs font-bold hover:bg-slate-900 transition">
                    Voir la commande
                </a>
                <button type="button" @click="newOrderNotification = false" class="text-slate-950 hover:text-slate-800"><i data-lucide="x" class="w-4 h-4"></i></button>
            </div>
        </div>

        <!-- Flash messages -->
        @if(session('success'))
            <div class="mx-6 mt-4 p-4 rounded-xl bg-emerald-900/60 border border-emerald-600/50 text-emerald-200 text-sm flex items-center gap-3">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-400"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mx-6 mt-4 p-4 rounded-xl bg-rose-900/60 border border-rose-600/50 text-rose-200 text-sm flex items-center gap-3">
                <i data-lucide="alert-circle" class="w-5 h-5 text-rose-400"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Scrollable Main View Area -->
        <main class="flex-1 overflow-y-auto p-6 space-y-6">
            @yield('content')
        </main>
    </div>

    <script>
        function adminApp() {
            return {
                soundEnabled: true,
                newOrdersCount: 0,
                lastSeenOrderId: null,
                newOrderNotification: false,
                latestOrderId: null,
                latestOrderCode: '',
                latestOrderClient: '',
                latestOrderTotal: '',
                audioCtx: null,

                initAudio() {
                    try {
                        const AudioContext = window.AudioContext || window.webkitAudioContext;
                        this.audioCtx = new AudioContext();
                    } catch (e) {
                        console.log('AudioContext not supported');
                    }
                },

                playChime() {
                    if (!this.soundEnabled || !this.audioCtx) return;
                    if (this.audioCtx.state === 'suspended') {
                        this.audioCtx.resume();
                    }

                    const now = this.audioCtx.currentTime;
                    // Play a pleasant restaurant chime (two harmonic bells)
                    const osc1 = this.audioCtx.createOscillator();
                    const osc2 = this.audioCtx.createOscillator();
                    const gainNode = this.audioCtx.createGain();

                    osc1.type = 'sine';
                    osc1.frequency.setValueAtTime(587.33, now); // D5
                    osc1.frequency.exponentialRampToValueAtTime(880, now + 0.15); // A5

                    osc2.type = 'triangle';
                    osc2.frequency.setValueAtTime(880, now + 0.15);
                    osc2.frequency.exponentialRampToValueAtTime(1174.66, now + 0.4); // D6

                    gainNode.gain.setValueAtTime(0.2, now);
                    gainNode.gain.exponentialRampToValueAtTime(0.001, now + 1.2);

                    osc1.connect(gainNode);
                    osc2.connect(gainNode);
                    gainNode.connect(this.audioCtx.destination);

                    osc1.start(now);
                    osc2.start(now + 0.15);
                    osc1.stop(now + 0.6);
                    osc2.stop(now + 1.2);
                },

                toggleSound() {
                    this.soundEnabled = !this.soundEnabled;
                    if (this.soundEnabled && this.audioCtx && this.audioCtx.state === 'suspended') {
                        this.audioCtx.resume();
                        this.playChime();
                    }
                },

                startPolling() {
                    const poll = async () => {
                        try {
                            const res = await fetch('{{ route('admin.api.new_orders') }}');
                            if (res.ok) {
                                const data = await res.json();
                                if (data.latest_id && this.lastSeenOrderId !== null && data.latest_id > this.lastSeenOrderId) {
                                    this.newOrderNotification = true;
                                    this.latestOrderId = data.latest_id;
                                    this.latestOrderCode = data.latest_code;
                                    this.latestOrderClient = data.latest_client;
                                    this.latestOrderTotal = data.latest_total;
                                    this.playChime();
                                }
                                this.lastSeenOrderId = data.latest_id;
                                this.newOrdersCount = data.en_attente_count;
                            }
                        } catch (err) {
                            // network silent fail
                        }
                    };

                    poll();
                    setInterval(poll, 8000);
                }
            };
        }

        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) lucide.createIcons();
        });
    </script>
    @stack('scripts')
</body>
</html>
