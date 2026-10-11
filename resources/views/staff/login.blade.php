<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">

    <title>Accès Interne — RestauHub</title>

    {{-- PWA: Staff manifest --}}
    <link rel="manifest" href="/manifest-staff.json">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="RH Staff">
    <meta name="theme-color" content="#0F172A">

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        body { background: #0f172a; }
    </style>
</head>
<body class="h-full flex items-center justify-center p-4 font-sans antialiased"
      x-data="{ showPass: false }">

    <div class="w-full max-w-sm">

        {{-- Logo / Header --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-600 to-indigo-900 shadow-xl mb-4">
                <i data-lucide="shield-check" class="w-8 h-8 text-white"></i>
            </div>
            <h1 class="text-xl font-bold text-white">Accès Interne</h1>
            <p class="text-sm text-slate-400 mt-1">RestauHub — Personnel autorisé uniquement</p>
        </div>

        {{-- Flash Messages --}}
        @if(session('error'))
            <div class="mb-4 p-3 rounded-xl bg-rose-900/60 border border-rose-700/50 text-rose-200 text-sm flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-4 h-4 text-rose-400 shrink-0"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif
        @if(session('info'))
            <div class="mb-4 p-3 rounded-xl bg-slate-800 border border-slate-700 text-slate-300 text-sm flex items-center gap-2">
                <i data-lucide="info" class="w-4 h-4 text-indigo-400 shrink-0"></i>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        {{-- Login Card --}}
        <div class="bg-slate-800/80 backdrop-blur border border-slate-700/60 rounded-2xl shadow-2xl p-6 space-y-5">
            <form method="POST" action="{{ route('staff.login.post') }}" class="space-y-4">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-300 mb-1.5 uppercase tracking-wider">
                        Adresse email
                    </label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <i data-lucide="mail" class="w-4 h-4 text-slate-500"></i>
                        </div>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            autocomplete="username"
                            required
                            placeholder="votre@email.com"
                            class="w-full pl-10 pr-4 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white placeholder-slate-500 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition
                                   @error('email') border-rose-500 ring-1 ring-rose-500 @enderror"
                        >
                    </div>
                    @error('email')
                        <p class="mt-1.5 text-xs text-rose-400 flex items-center gap-1">
                            <i data-lucide="alert-triangle" class="w-3 h-3"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-300 mb-1.5 uppercase tracking-wider">
                        Mot de passe
                    </label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <i data-lucide="lock" class="w-4 h-4 text-slate-500"></i>
                        </div>
                        <input
                            :type="showPass ? 'text' : 'password'"
                            id="password"
                            name="password"
                            autocomplete="current-password"
                            required
                            placeholder="••••••••"
                            class="w-full pl-10 pr-10 py-3 rounded-xl bg-slate-900 border border-slate-700 text-white placeholder-slate-500 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                        >
                        <button type="button" @click="showPass = !showPass"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-500 hover:text-slate-300 transition">
                            <i :data-lucide="showPass ? 'eye-off' : 'eye'" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                {{-- Remember --}}
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-600 bg-slate-900 text-indigo-500 focus:ring-indigo-500">
                        <span class="text-xs text-slate-400">Se souvenir de moi</span>
                    </label>
                    <span class="text-xs text-slate-600">Accès réservé au personnel</span>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm
                               shadow-lg shadow-indigo-900/40 transition active:scale-95 flex items-center justify-center gap-2">
                    <i data-lucide="log-in" class="w-4 h-4"></i>
                    Se connecter
                </button>
            </form>
        </div>

        {{-- Role Badges --}}
        <div class="mt-6 flex items-center justify-center gap-3">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-950/60 text-red-300 text-xs border border-red-900/40">
                <i data-lucide="shield" class="w-3 h-3"></i> Admin
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-950/60 text-amber-300 text-xs border border-amber-900/40">
                <i data-lucide="chef-hat" class="w-3 h-3"></i> Cuisine
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-indigo-950/60 text-indigo-300 text-xs border border-indigo-900/40">
                <i data-lucide="bike" class="w-3 h-3"></i> Livreur
            </span>
        </div>

        {{-- Install PWA hint --}}
        <div id="pwa-install-hint" class="hidden mt-5 p-3 rounded-xl bg-indigo-950/50 border border-indigo-800/40 text-indigo-300 text-xs text-center">
            <i data-lucide="download" class="w-3.5 h-3.5 inline mr-1"></i>
            Installez l'application pour un accès rapide depuis votre écran d'accueil.
            <button id="pwa-install-btn" class="ml-2 font-semibold underline hover:text-indigo-100">Installer</button>
        </div>

        {{-- Security note --}}
        <p class="text-center text-xs text-slate-700 mt-5">
            Session chiffrée · Accès journalisé · Ne partagez pas vos identifiants
        </p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) lucide.createIcons();

            // Service Worker
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('/sw.js').catch(() => {});
            }

            // PWA Install prompt
            let deferredPrompt;
            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                deferredPrompt = e;
                document.getElementById('pwa-install-hint').classList.remove('hidden');
                document.getElementById('pwa-install-btn').addEventListener('click', () => {
                    deferredPrompt.prompt();
                    deferredPrompt.userChoice.then(() => {
                        document.getElementById('pwa-install-hint').classList.add('hidden');
                        deferredPrompt = null;
                    });
                });
            });

            // Re-render eye icon when Alpine changes it
            document.querySelectorAll('button[\\@click]').forEach(btn => {
                btn.addEventListener('click', () => setTimeout(() => lucide.createIcons(), 50));
            });
        });
    </script>
</body>
</html>
