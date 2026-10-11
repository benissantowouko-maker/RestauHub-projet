<!DOCTYPE html>
<html lang="fr" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Café Paris Lomé — Brasserie Gourmande & Commande en Ligne')</title>
    <meta name="description" content="Café Paris à Lomé (Togo) : cuisine raffinée, pizzas au feu de bois, grillades et spécialités locales. Commandez en ligne, paiement Flooz, T-Money ou espèces à la livraison.">

    {{-- PWA --}}
    <link rel="manifest" href="/manifest.json">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="RestauHub">
    <meta name="theme-color" content="#7F1D1D">
    <link rel="apple-touch-icon" href="/icons/icon-192.png">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">

    <!-- Fonts: Instrument Sans & Playfair Display -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">

    <!-- Leaflet CSS for maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .font-serif {
            font-family: 'Playfair Display', Georgia, serif;
        }
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body class="bg-[#FAF7F2] text-slate-800 flex flex-col min-h-screen antialiased selection:bg-red-800 selection:text-white"
      x-data="globalApp()"
      x-init="initCart()">

    <!-- Top Announcement Bar -->
    <div class="bg-red-950 text-white text-xs py-2 px-4 border-b border-red-900/60">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1 text-amber-400 font-semibold">
                    <i data-lucide="map-pin" class="w-3.5 h-3.5"></i> Lomé, Togo
                </span>
                <span class="hidden sm:inline text-red-300">|</span>
                <span class="text-slate-200">Livraison express dans tout Lomé en 30-45 min</span>
            </div>
            <div class="flex items-center gap-4 text-slate-200">
                <span class="inline-flex items-center gap-1.5">
                    <i data-lucide="clock" class="w-3.5 h-3.5 text-amber-400"></i> Ouvert 08h00 - 23h30
                </span>
                <span class="hidden sm:inline text-red-300">|</span>
                <a href="tel:+22890001234" class="inline-flex items-center gap-1.5 hover:text-amber-400 transition font-medium">
                    <i data-lucide="phone-call" class="w-3.5 h-3.5 text-amber-400"></i> +228 90 00 12 34
                </a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-stone-200 shadow-sm transition">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-800 to-red-950 flex items-center justify-center text-amber-400 shadow-md group-hover:scale-105 transition">
                    <i data-lucide="utensils-crossed" class="w-6 h-6"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-serif font-bold text-2xl tracking-tight text-red-950 group-hover:text-red-800 transition">CAFÉ PARIS</span>
                        <span class="text-xs font-semibold px-1.5 py-0.5 rounded bg-amber-100 text-amber-900 border border-amber-300">LOMÉ</span>
                    </div>
                    <p class="text-xs text-stone-500 uppercase tracking-widest font-medium">Brasserie & Grillades</p>
                </div>
            </a>

            <!-- Desktop Nav Links -->
            <nav class="hidden md:flex items-center gap-1 lg:gap-2">
                <a href="{{ route('home') }}" class="px-3.5 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('home') ? 'text-red-800 bg-red-50 font-semibold' : 'text-slate-700 hover:text-red-800 hover:bg-stone-50' }}">
                    Accueil
                </a>
                <a href="{{ route('menu.index') }}" class="px-3.5 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('menu.*') ? 'text-red-800 bg-red-50 font-semibold' : 'text-slate-700 hover:text-red-800 hover:bg-stone-50' }}">
                    Nos Menus
                </a>
                <a href="{{ route('reservation.create') }}" class="px-3.5 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('reservation.*') ? 'text-red-800 bg-red-50 font-semibold' : 'text-slate-700 hover:text-red-800 hover:bg-stone-50' }}">
                    Réserver une Table
                </a>
                <a href="{{ route('about') }}" class="px-3.5 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('about') ? 'text-red-800 bg-red-50 font-semibold' : 'text-slate-700 hover:text-red-800 hover:bg-stone-50' }}">
                    À Propos
                </a>
                <a href="{{ route('contact') }}" class="px-3.5 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('contact') ? 'text-red-800 bg-red-50 font-semibold' : 'text-slate-700 hover:text-red-800 hover:bg-stone-50' }}">
                    Contact
                </a>
            </nav>

            <!-- Actions & Cart & User -->
            <div class="flex items-center gap-3">
                <!-- Cart Trigger Button -->
                <button type="button"
                        @click="cartOpen = true"
                        class="relative p-2.5 rounded-xl bg-stone-100 hover:bg-stone-200 text-slate-700 hover:text-red-800 transition flex items-center gap-2"
                        title="Voir le panier">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                    <span class="hidden sm:inline text-xs font-semibold" x-text="formatMoney(cartTotal) + ' FCFA'">0 FCFA</span>
                    <span x-show="cartCount > 0"
                          x-text="cartCount"
                          class="absolute -top-1.5 -right-1.5 bg-red-700 text-white text-[11px] font-bold rounded-full h-5 min-w-5 px-1 flex items-center justify-center ring-2 ring-white">
                        0
                    </span>
                </button>

                <!-- Auth / Profile -->
                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 p-1.5 pr-3 rounded-xl bg-stone-100 hover:bg-stone-200 text-slate-800 transition">
                            <div class="w-8 h-8 rounded-lg bg-red-800 text-white font-bold flex items-center justify-center text-xs">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                            <span class="text-xs font-semibold hidden lg:inline max-w-[100px] truncate">{{ auth()->user()->name }}</span>
                            <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-slate-500"></i>
                        </button>

                        <div x-show="open" @click.outside="open = false" x-cloak
                             class="absolute right-0 mt-2 w-56 rounded-xl bg-white shadow-xl border border-stone-200 py-2 z-50">
                            <div class="px-4 py-2 border-b border-stone-100">
                                <p class="text-xs font-medium text-slate-400">Connecté en tant que</p>
                                <p class="text-sm font-bold text-slate-800 truncate">{{ auth()->user()->name }}</p>
                                <span class="inline-block mt-1 text-[10px] font-semibold uppercase px-2 py-0.5 rounded bg-amber-100 text-amber-800">
                                    {{ auth()->user()->role }}
                                </span>
                            </div>

                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-red-800 font-semibold hover:bg-red-50">
                                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Back-office Admin
                                </a>
                            @endif

                            @if(auth()->user()->isLivreur())
                                <a href="{{ route('coursier.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-indigo-700 font-semibold hover:bg-indigo-50">
                                    <i data-lucide="bike" class="w-4 h-4"></i> Espace Coursier
                                </a>
                            @endif

                            @if(auth()->user()->isStaff())
                                <a href="{{ route('cuisine.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-amber-700 font-semibold hover:bg-amber-50">
                                    <i data-lucide="chef-hat" class="w-4 h-4"></i> Écran Cuisine (KDS)
                                </a>
                            @endif

                            <a href="{{ route('compte.commandes') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-700 hover:bg-stone-50">
                                <i data-lucide="receipt" class="w-4 h-4"></i> Mes Commandes
                            </a>

                            <form method="POST" action="{{ route('logout') }}" class="border-t border-stone-100 pt-1 mt-1">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2 text-sm text-rose-600 hover:bg-rose-50 text-left font-medium">
                                    <i data-lucide="log-out" class="w-4 h-4"></i> Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-red-800 hover:bg-red-900 text-white text-xs sm:text-sm font-semibold shadow-sm transition">
                        <i data-lucide="user" class="w-4 h-4"></i>
                        <span>Connexion</span>
                    </a>
                @endauth

                <!-- Mobile Menu Button -->
                <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 rounded-lg bg-stone-100 text-slate-700">
                    <i data-lucide="menu" class="w-5 h-5" x-show="!mobileMenuOpen"></i>
                    <i data-lucide="x" class="w-5 h-5" x-show="mobileMenuOpen" x-cloak></i>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation Drawer -->
        <div x-show="mobileMenuOpen" x-cloak class="md:hidden border-t border-stone-200 bg-white px-4 pt-3 pb-5 space-y-1">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('home') ? 'bg-red-50 text-red-800 font-bold' : 'text-slate-700' }}">Accueil</a>
            <a href="{{ route('menu.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('menu.*') ? 'bg-red-50 text-red-800 font-bold' : 'text-slate-700' }}">Nos Menus</a>
            <a href="{{ route('reservation.create') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('reservation.*') ? 'bg-red-50 text-red-800 font-bold' : 'text-slate-700' }}">Réserver une Table</a>
            <a href="{{ route('about') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('about') ? 'bg-red-50 text-red-800 font-bold' : 'text-slate-700' }}">À Propos</a>
            <a href="{{ route('contact') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('contact') ? 'bg-red-50 text-red-800 font-bold' : 'text-slate-700' }}">Contact</a>
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold text-red-800 bg-red-50">Back-office Admin</a>
                @endif
                @if(auth()->user()->isLivreur())
                    <a href="{{ route('coursier.dashboard') }}" class="block px-3 py-2 rounded-lg text-sm font-semibold text-indigo-700 bg-indigo-50">Espace Coursier</a>
                @endif
            @endauth
        </div>
    </header>

    <!-- Flash Messages (Toasts) -->
    <div class="fixed top-24 right-4 z-50 max-w-md w-full space-y-2 pointer-events-none">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)"
                 class="pointer-events-auto flex items-start gap-3 p-4 rounded-xl bg-emerald-900/95 text-white shadow-xl border border-emerald-700/50 backdrop-blur-md">
                <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-400 shrink-0 mt-0.5"></i>
                <div class="flex-1 text-sm leading-relaxed">{{ session('success') }}</div>
                <button @click="show = false" class="text-emerald-300 hover:text-white"><i data-lucide="x" class="w-4 h-4"></i></button>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show"
                 class="pointer-events-auto flex items-start gap-3 p-4 rounded-xl bg-rose-900/95 text-white shadow-xl border border-rose-700/50 backdrop-blur-md">
                <i data-lucide="alert-octagon" class="w-5 h-5 text-rose-400 shrink-0 mt-0.5"></i>
                <div class="flex-1 text-sm leading-relaxed">{{ session('error') }}</div>
                <button @click="show = false" class="text-rose-300 hover:text-white"><i data-lucide="x" class="w-4 h-4"></i></button>
            </div>
        @endif

        @if(session('warning'))
            <div x-data="{ show: true }" x-show="show"
                 class="pointer-events-auto flex items-start gap-3 p-4 rounded-xl bg-amber-900/95 text-white shadow-xl border border-amber-700/50 backdrop-blur-md">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-400 shrink-0 mt-0.5"></i>
                <div class="flex-1 text-sm leading-relaxed">{{ session('warning') }}</div>
                <button @click="show = false" class="text-amber-300 hover:text-white"><i data-lucide="x" class="w-4 h-4"></i></button>
            </div>
        @endif

        @if($errors->any())
            <div x-data="{ show: true }" x-show="show"
                 class="pointer-events-auto flex items-start gap-3 p-4 rounded-xl bg-rose-900/95 text-white shadow-xl border border-rose-700/50 backdrop-blur-md">
                <i data-lucide="alert-circle" class="w-5 h-5 text-rose-400 shrink-0 mt-0.5"></i>
                <div class="flex-1 text-sm">
                    <p class="font-bold mb-1">Veuillez corriger les erreurs suivantes :</p>
                    <ul class="list-disc list-inside text-xs space-y-0.5 text-rose-200">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
                <button @click="show = false" class="text-rose-300 hover:text-white"><i data-lucide="x" class="w-4 h-4"></i></button>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Persistent Slide-Over Cart Drawer -->
    <div x-show="cartOpen" x-cloak class="relative z-50">
        <!-- Backdrop -->
        <div x-show="cartOpen"
             x-transition:enter="ease-in-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in-out duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="cartOpen = false"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

        <!-- Slide panel -->
        <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
            <div x-show="cartOpen"
                 x-transition:enter="transform transition ease-in-out duration-300"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transform transition ease-in-out duration-300"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full"
                 class="w-screen max-w-md bg-white shadow-2xl flex flex-col">

                <!-- Header -->
                <div class="p-6 bg-red-950 text-white flex items-center justify-between border-b border-red-900">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-lg bg-red-900/60 text-amber-400">
                            <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold font-serif">Mon Panier Gourmand</h2>
                            <p class="text-xs text-red-200" x-text="cartCount + ' article(s) sélectionné(s)'"></p>
                        </div>
                    </div>
                    <button type="button" @click="cartOpen = false" class="p-2 rounded-lg hover:bg-red-900 text-red-200 hover:text-white transition">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <!-- Items list -->
                <div class="flex-1 overflow-y-auto p-6 space-y-4">
                    <template x-if="cart.length === 0">
                        <div class="h-full flex flex-col items-center justify-center text-center text-slate-400 py-12">
                            <div class="w-16 h-16 rounded-full bg-stone-100 flex items-center justify-center mb-4 text-stone-400">
                                <i data-lucide="shopping-cart" class="w-8 h-8"></i>
                            </div>
                            <h3 class="text-base font-bold text-slate-700">Votre panier est encore vide</h3>
                            <p class="text-xs text-slate-500 mt-1 max-w-xs">Découvrez nos pizzas croustillantes, grillades savoureuses et formules du jour.</p>
                            <a href="{{ route('menu.index') }}" @click="cartOpen = false" class="mt-5 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-red-800 text-white text-xs font-semibold shadow hover:bg-red-900 transition">
                                <i data-lucide="utensils" class="w-4 h-4"></i> Parcourir la carte
                            </a>
                        </div>
                    </template>

                    <template x-for="(item, index) in cart" :key="index">
                        <div class="p-4 rounded-xl bg-stone-50 border border-stone-200/80 flex gap-3 relative group hover:border-red-200 transition">
                            <img :src="item.image || 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=120&auto=format&fit=crop&q=80'"
                                 :alt="item.name"
                                 class="w-16 h-16 rounded-lg object-cover shrink-0 border border-stone-200">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2">
                                    <h4 class="text-sm font-bold text-slate-900 truncate" x-text="item.name"></h4>
                                    <button type="button" @click="removeFromCart(index)" class="text-slate-400 hover:text-rose-600 transition p-0.5">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                                <p class="text-xs font-semibold text-red-800 mt-0.5" x-text="formatMoney(item.price) + ' FCFA'"></p>

                                <div class="flex items-center justify-between mt-3">
                                    <div class="flex items-center border border-stone-300 rounded-lg bg-white overflow-hidden shadow-sm">
                                        <button type="button" @click="updateQty(index, item.qty - 1)" class="px-2 py-1 text-slate-600 hover:bg-stone-100 transition">
                                            <i data-lucide="minus" class="w-3 h-3"></i>
                                        </button>
                                        <span class="px-3 text-xs font-bold text-slate-800" x-text="item.qty"></span>
                                        <button type="button" @click="updateQty(index, item.qty + 1)" class="px-2 py-1 text-slate-600 hover:bg-stone-100 transition">
                                            <i data-lucide="plus" class="w-3 h-3"></i>
                                        </button>
                                    </div>
                                    <span class="text-xs font-bold text-slate-900" x-text="formatMoney(item.price * item.qty) + ' FCFA'"></span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Footer Summary & Checkout -->
                <div x-show="cart.length > 0" class="p-6 bg-stone-50 border-t border-stone-200 space-y-4">
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between text-slate-600">
                            <span>Sous-total articles :</span>
                            <span class="font-semibold text-slate-900" x-text="formatMoney(cartTotal) + ' FCFA'"></span>
                        </div>
                        <div class="flex justify-between text-slate-600 text-xs">
                            <span>Frais de livraison estimés (Lomé) :</span>
                            <span class="font-medium text-slate-700">dès 1 000 FCFA</span>
                        </div>
                        <div class="border-t border-stone-200 pt-2 flex justify-between text-base font-bold text-slate-900">
                            <span>Total estimé :</span>
                            <span class="text-red-800 text-lg" x-text="formatMoney(cartTotal) + ' FCFA'"></span>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <a href="{{ route('checkout.index') }}"
                           class="w-full flex items-center justify-center gap-2 py-3.5 px-4 rounded-xl bg-red-800 hover:bg-red-900 text-white font-bold text-sm shadow-lg shadow-red-900/20 transition group">
                            <span>Passer la commande</span>
                            <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition"></i>
                        </a>

                        <button type="button" @click="clearCart()" class="w-full py-2 text-center text-xs text-slate-500 hover:text-rose-600 transition">
                            Vider le panier
                        </button>
                    </div>

                    <div class="flex items-center justify-center gap-3 pt-2 text-[11px] text-slate-500">
                        <span class="inline-flex items-center gap-1"><i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-600"></i> Espèces à la livraison</span>
                        <span>•</span>
                        <span class="inline-flex items-center gap-1"><i data-lucide="zap" class="w-3.5 h-3.5 text-amber-500"></i> T-Money / Flooz</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Mobile Cart Pill -->
    <div x-show="cartCount > 0" x-cloak class="md:hidden fixed bottom-5 left-4 right-4 z-40">
        <button type="button" @click="cartOpen = true"
                class="w-full bg-red-900 text-white py-3.5 px-5 rounded-2xl shadow-2xl flex items-center justify-between border border-red-700/50">
            <div class="flex items-center gap-3">
                <div class="w-7 h-7 rounded-full bg-amber-400 text-red-950 font-bold text-xs flex items-center justify-center shadow" x-text="cartCount"></div>
                <span class="text-sm font-semibold">Voir mon panier</span>
            </div>
            <div class="flex items-center gap-2 font-bold text-sm">
                <span x-text="formatMoney(cartTotal) + ' FCFA'"></span>
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </div>
        </button>
    </div>

    <!-- Global Footer -->
    <footer class="bg-stone-900 text-stone-300 pt-16 pb-12 border-t border-stone-800 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 pb-12 border-b border-stone-800">
                <!-- Col 1: Brand & About -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-red-800 flex items-center justify-center text-amber-400 shadow">
                            <i data-lucide="utensils-crossed" class="w-5 h-5"></i>
                        </div>
                        <span class="font-serif font-bold text-xl text-white">CAFÉ PARIS LOMÉ</span>
                    </div>
                    <p class="text-sm text-stone-400 leading-relaxed">
                        L'art de la gastronomie et de la brasserie au cœur de Lomé. Produits frais du terroir togolais, pizzas au feu de bois et savoir-faire culinaire depuis plus de 10 ans.
                    </p>
                    <div class="flex items-center gap-3 pt-2">
                        <span class="px-2.5 py-1 rounded bg-stone-800 text-amber-400 text-xs font-semibold">T-Money</span>
                        <span class="px-2.5 py-1 rounded bg-stone-800 text-amber-400 text-xs font-semibold">Flooz</span>
                        <span class="px-2.5 py-1 rounded bg-stone-800 text-emerald-400 text-xs font-semibold">Espèces</span>
                    </div>
                </div>

                <!-- Col 2: Navigation Links -->
                <div>
                    <h3 class="text-white font-bold text-sm tracking-wider uppercase mb-4">Plan du site</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-amber-400 transition">Accueil & Présentation</a></li>
                        <li><a href="{{ route('menu.index') }}" class="hover:text-amber-400 transition">Carte des Menus & Tarifs</a></li>
                        <li><a href="{{ route('reservation.create') }}" class="hover:text-amber-400 transition">Réserver une Table en salle</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-amber-400 transition">Notre Histoire & Valeurs</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-amber-400 transition">Nous Contacter à Lomé</a></li>
                    </ul>
                </div>

                <!-- Col 3: Hours & Zones -->
                <div>
                    <h3 class="text-white font-bold text-sm tracking-wider uppercase mb-4">Horaires & Livraison</h3>
                    <ul class="space-y-2 text-sm text-stone-400">
                        <li class="flex items-start gap-2">
                            <i data-lucide="clock" class="w-4 h-4 text-amber-400 shrink-0 mt-0.5"></i>
                            <div>
                                <strong class="text-white">Lundi — Dimanche :</strong><br>
                                08h00 - 23h30 (Service continu)
                            </div>
                        </li>
                        <li class="flex items-start gap-2 pt-2">
                            <i data-lucide="map" class="w-4 h-4 text-amber-400 shrink-0 mt-0.5"></i>
                            <div>
                                <strong class="text-white">Zones desservies :</strong><br>
                                Marina, Tokoin, Kodjoviakopé, Hedzranawoé, Agoè, Bè, Baguida.
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Col 4: Contact & Access -->
                <div>
                    <h3 class="text-white font-bold text-sm tracking-wider uppercase mb-4">Café Paris Lomé</h3>
                    <address class="not-italic space-y-3 text-sm text-stone-400">
                        <p class="flex items-start gap-2.5">
                            <i data-lucide="map-pin" class="w-4 h-4 text-amber-400 shrink-0 mt-0.5"></i>
                            <span>Boulevard de la Marina, Angle Rue de la Paix, Lomé - Togo</span>
                        </p>
                        <p class="flex items-center gap-2.5">
                            <i data-lucide="phone" class="w-4 h-4 text-amber-400 shrink-0"></i>
                            <a href="tel:+22890001234" class="hover:text-white transition">+228 90 00 12 34</a>
                        </p>
                        <p class="flex items-center gap-2.5">
                            <i data-lucide="mail" class="w-4 h-4 text-amber-400 shrink-0"></i>
                            <a href="mailto:contact@cafeparis-lome.site" class="hover:text-white transition">contact@cafeparis-lome.site</a>
                        </p>
                    </address>
                    <div class="mt-4 pt-3 border-t border-stone-800">
                        <a href="{{ route('login') }}" class="text-xs text-stone-500 hover:text-stone-300 transition inline-flex items-center gap-1.5">
                            <i data-lucide="lock" class="w-3 h-3"></i> Accès Administration / Staff / Livreur
                        </a>
                    </div>
                </div>
            </div>

            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-stone-500">
                <p>© 2026 Café Paris Lomé. Tous droits réservés. Plateforme digitale développée avec Laravel & Blade.</p>
                <div class="flex items-center gap-4">
                    <span>Lomé • Togo</span>
                    <span>•</span>
                    <span>Version 1.0</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Global Cart Script in Alpine.js -->
    <script>
        function globalApp() {
            return {
                cartOpen: false,
                mobileMenuOpen: false,
                cart: [],

                initCart() {
                    const saved = localStorage.getItem('cafeparis_cart');
                    if (saved) {
                        try {
                            this.cart = JSON.parse(saved);
                        } catch (e) {
                            this.cart = [];
                        }
                    }
                    this.$watch('cart', (val) => {
                        localStorage.setItem('cafeparis_cart', JSON.stringify(val));
                    });
                },

                get cartCount() {
                    return this.cart.reduce((total, item) => total + (item.qty || 1), 0);
                },

                get cartTotal() {
                    return this.cart.reduce((total, item) => total + ((item.price || 0) * (item.qty || 1)), 0);
                },

                addToCart(product, qty = 1, type = 'plat') {
                    const existingIndex = this.cart.findIndex(i => i.id === product.id && i.type === type);
                    if (existingIndex > -1) {
                        this.cart[existingIndex].qty += qty;
                    } else {
                        this.cart.push({
                            id: product.id,
                            type: type,
                            name: product.nom || product.name,
                            price: parseFloat(product.prix_fcfa || product.price),
                            image: product.photo_url || product.image,
                            qty: qty,
                            note: ''
                        });
                    }
                    this.cartOpen = true;
                    if (window.lucide) {
                        setTimeout(() => lucide.createIcons(), 50);
                    }
                },

                updateQty(index, newQty) {
                    if (newQty <= 0) {
                        this.removeFromCart(index);
                    } else {
                        this.cart[index].qty = newQty;
                    }
                },

                removeFromCart(index) {
                    this.cart.splice(index, 1);
                },

                clearCart() {
                    this.cart = [];
                },

                formatMoney(amount) {
                    return new Intl.NumberFormat('fr-FR').format(amount || 0);
                }
            };
        }

        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) {
                lucide.createIcons();
            }

            // Register PWA Service Worker
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('/sw.js').catch(() => {});
            }

            // PWA Install Prompt — show a subtle banner
            let deferredPrompt;
            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                deferredPrompt = e;
                const banner = document.getElementById('pwa-banner');
                if (banner) banner.classList.remove('hidden');

                document.getElementById('pwa-install-btn')?.addEventListener('click', () => {
                    deferredPrompt.prompt();
                    deferredPrompt.userChoice.then(() => {
                        if (banner) banner.classList.add('hidden');
                        deferredPrompt = null;
                    });
                });
                document.getElementById('pwa-dismiss-btn')?.addEventListener('click', () => {
                    if (banner) banner.classList.add('hidden');
                    sessionStorage.setItem('pwa-dismissed', '1');
                });
            });
        });
    </script>

    {{-- PWA Install Banner (shown only when browser triggers beforeinstallprompt) --}}
    <div id="pwa-banner"
         class="hidden fixed bottom-20 sm:bottom-6 left-1/2 -translate-x-1/2 z-50
                w-[calc(100%-2rem)] max-w-sm
                bg-red-950 border border-red-800/60 text-white rounded-2xl shadow-2xl
                flex items-center gap-3 px-4 py-3.5">
        <div class="shrink-0 w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
            <i data-lucide="smartphone" class="w-5 h-5 text-amber-400"></i>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold leading-tight">Installer RestauHub</p>
            <p class="text-xs text-red-300 truncate">Commandez depuis votre écran d'accueil</p>
        </div>
        <button id="pwa-install-btn"
                class="shrink-0 px-3 py-1.5 bg-amber-500 hover:bg-amber-400 text-black text-xs font-bold rounded-lg transition">
            Installer
        </button>
        <button id="pwa-dismiss-btn" class="shrink-0 text-red-400 hover:text-white transition">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
    </div>

    @stack('scripts')
</body>
</html>
