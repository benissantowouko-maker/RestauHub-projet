<!DOCTYPE html>
<html lang="fr" class="h-full bg-slate-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Espace Coursier') — Café Paris Lomé</title>

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
<body class="h-full text-slate-100 flex flex-col font-sans antialiased selection:bg-indigo-600 selection:text-white"
      x-data="coursierApp()"
      x-init="startPolling()">

    <!-- Courier Top App Bar -->
    <header class="sticky top-0 z-40 bg-slate-950/95 backdrop-blur border-b border-slate-800 px-4 py-3 flex items-center justify-between shadow-lg">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-600 to-indigo-900 flex items-center justify-center text-white shadow-md">
                <i data-lucide="bike" class="w-5 h-5"></i>
            </div>
            <div>
                <h1 class="text-sm font-bold text-white flex items-center gap-2">
                    <span>Café Paris Livraisons</span>
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                </h1>
                <p class="text-[11px] text-indigo-300 font-medium">{{ auth()->user()->name }}</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <!-- Manual Refresh Button -->
            <button type="button" @click="refreshPage()"
                    class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 transition"
                    title="Actualiser les livraisons">
                <i data-lucide="refresh-cw" class="w-4 h-4" :class="refreshing ? 'animate-spin text-indigo-400' : ''"></i>
            </button>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="p-2 rounded-xl bg-slate-800 hover:bg-rose-950/50 text-slate-400 hover:text-rose-400 transition" title="Déconnexion">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </header>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="mx-4 mt-3 p-3.5 rounded-xl bg-emerald-900/90 border border-emerald-600/50 text-emerald-100 text-xs flex items-center gap-2.5 shadow">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-400 shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mx-4 mt-3 p-3.5 rounded-xl bg-rose-900/90 border border-rose-600/50 text-rose-100 text-xs flex items-center gap-2.5 shadow">
            <i data-lucide="alert-circle" class="w-4 h-4 text-rose-400 shrink-0"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Content Area -->
    <main class="flex-1 overflow-y-auto px-4 py-5 pb-24 max-w-lg mx-auto w-full space-y-4">
        @yield('content')
    </main>

    <!-- Courier Bottom Navigation Bar -->
    <nav class="fixed bottom-0 inset-x-0 bg-slate-950/95 backdrop-blur-md border-t border-slate-800 py-2 px-6 z-40 max-w-lg mx-auto flex items-center justify-around">
        <a href="{{ route('coursier.dashboard') }}"
           class="flex flex-col items-center gap-1 py-1 px-3 rounded-xl transition {{ request()->routeIs('coursier.dashboard') ? 'text-indigo-400 font-bold' : 'text-slate-400 hover:text-slate-200' }}">
            <i data-lucide="list-ordered" class="w-5 h-5"></i>
            <span class="text-[11px]">Missions</span>
        </a>

        <a href="{{ route('coursier.dashboard') }}#en-cours"
           class="flex flex-col items-center gap-1 py-1 px-3 rounded-xl transition text-slate-400 hover:text-slate-200">
            <i data-lucide="navigation" class="w-5 h-5"></i>
            <span class="text-[11px]">En route</span>
        </a>

        <a href="{{ route('coursier.dashboard') }}#historique"
           class="flex flex-col items-center gap-1 py-1 px-3 rounded-xl transition text-slate-400 hover:text-slate-200">
            <i data-lucide="history" class="w-5 h-5"></i>
            <span class="text-[11px]">Historique</span>
        </a>

        <a href="{{ route('home') }}" target="_blank"
           class="flex flex-col items-center gap-1 py-1 px-3 rounded-xl transition text-slate-400 hover:text-slate-200">
            <i data-lucide="external-link" class="w-5 h-5"></i>
            <span class="text-[11px]">Site Web</span>
        </a>
    </nav>

    <script>
        function coursierApp() {
            return {
                refreshing: false,
                lastCount: 0,

                refreshPage() {
                    this.refreshing = true;
                    setTimeout(() => window.location.reload(), 300);
                },

                startPolling() {
                    setInterval(async () => {
                        try {
                            const res = await fetch('{{ route('coursier.api.poll') }}');
                            if (res.ok) {
                                const data = await res.json();
                                if (data.count_pretes > this.lastCount && this.lastCount !== 0) {
                                    // New delivery ready
                                    if (navigator.vibrate) navigator.vibrate([200, 100, 200]);
                                    window.location.reload();
                                }
                                this.lastCount = data.count_pretes;
                            }
                        } catch (e) {}
                    }, 7000);
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
