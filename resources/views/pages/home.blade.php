@extends('layouts.app')

@section('title', 'Café Paris Lomé — Brasserie, Grillades & Commande en Ligne')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-b from-red-950 via-stone-900 to-stone-950 text-white overflow-hidden py-20 lg:py-28">
    <!-- Background Texture / Overlay -->
    <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#d97706_1px,transparent_1px)] [background-size:24px_24px]"></div>
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-red-600/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <!-- Text Content -->
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-red-900/60 border border-red-700/60 text-amber-300 text-xs font-semibold backdrop-blur-sm">
                    <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                    <span>La référence brasserie & gastronomie à Lomé</span>
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-serif font-bold tracking-tight text-white leading-tight">
                    L’art de la table parisienne <br class="hidden sm:inline">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-300 to-amber-500">au cœur de Lomé.</span>
                </h1>

                <p class="text-base sm:text-lg text-stone-300 max-w-2xl mx-auto lg:mx-0 leading-relaxed font-normal">
                    Pizzas croustillantes au feu de bois, viandes braisées d’exception, chawarmas généreux et poissons frais du Golfe de Guinée. Commandez en ligne pour une livraison express ou réservez votre table en terrasse.
                </p>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-4">
                    <a href="{{ route('menu.index') }}"
                       class="w-full sm:w-auto px-8 py-4 rounded-xl bg-gradient-to-r from-red-700 to-red-800 hover:from-red-600 hover:to-red-700 text-white font-bold text-base shadow-xl shadow-red-950/40 transition flex items-center justify-center gap-2 group">
                        <i data-lucide="shopping-bag" class="w-5 h-5 text-amber-300"></i>
                        <span>Commander en ligne</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition"></i>
                    </a>

                    <a href="{{ route('reservation.create') }}"
                       class="w-full sm:w-auto px-7 py-4 rounded-xl bg-stone-800/80 hover:bg-stone-800 text-stone-100 hover:text-white border border-stone-700 font-semibold text-base transition flex items-center justify-center gap-2">
                        <i data-lucide="calendar" class="w-5 h-5 text-amber-400"></i>
                        <span>Réserver une table</span>
                    </a>
                </div>

                <!-- Feature Badges -->
                <div class="grid grid-cols-3 gap-4 pt-8 border-t border-stone-800/80 text-left">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-red-900/60 flex items-center justify-center text-amber-400 shrink-0">
                            <i data-lucide="truck" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-white">Livraison 30-45m</p>
                            <p class="text-[11px] text-stone-400">Tout Lomé</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-red-900/60 flex items-center justify-center text-amber-400 shrink-0">
                            <i data-lucide="smartphone" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-white">T-Money & Flooz</p>
                            <p class="text-[11px] text-stone-400">Ou espèces</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-red-900/60 flex items-center justify-center text-amber-400 shrink-0">
                            <i data-lucide="award" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-white">100% Fait Maison</p>
                            <p class="text-[11px] text-stone-400">Produits frais</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hero Image Visual -->
            <div class="lg:col-span-5 relative">
                <div class="relative mx-auto max-w-md lg:max-w-none">
                    <div class="rounded-3xl overflow-hidden shadow-2xl border-4 border-stone-800/60 shadow-red-950/50">
                        <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=800&auto=format&fit=crop&q=80"
                             alt="Ambiance bistrot Café Paris Lomé"
                             class="w-full h-96 lg:h-[480px] object-cover scale-105 hover:scale-100 transition duration-700">
                    </div>

                    <!-- Floating Special Dish Badge -->
                    <div class="absolute -bottom-6 -left-6 bg-white/95 backdrop-blur text-slate-900 p-4 rounded-2xl shadow-xl border border-stone-200 hidden sm:flex items-center gap-3.5 max-w-xs">
                        <img src="https://images.unsplash.com/photo-1513104890138-7c749659a591?w=120&auto=format&fit=crop&q=80"
                             alt="Pizza Royale"
                             class="w-14 h-14 rounded-xl object-cover shrink-0 border border-stone-200">
                        <div>
                            <span class="inline-block text-[10px] font-bold uppercase tracking-wider text-red-800 bg-red-50 px-2 py-0.5 rounded">Star de la carte</span>
                            <p class="text-xs font-bold mt-1 text-slate-900">Pizza Royale Café Paris</p>
                            <p class="text-xs font-extrabold text-red-800">6 500 FCFA</p>
                        </div>
                    </div>

                    <!-- Floating Quality Badge -->
                    <div class="absolute -top-4 -right-4 bg-amber-400 text-stone-950 px-4 py-2 rounded-xl shadow-lg font-bold text-xs flex items-center gap-1.5 rotate-3">
                        <i data-lucide="star" class="w-4 h-4 fill-stone-950"></i>
                        <span>Note 4.9/5 à Lomé</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Catégories du Menu -->
<section class="py-16 bg-[#FAF7F2]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
            <div>
                <span class="text-xs uppercase font-bold text-red-800 tracking-wider">Carte Gastronomique</span>
                <h2 class="text-3xl font-serif font-bold text-slate-900 mt-1">Explorez nos Spécialités</h2>
                <p class="text-sm text-slate-600 mt-1">De la brasserie parisienne aux délices togolais et méditerranéens</p>
            </div>
            <a href="{{ route('menu.index') }}" class="inline-flex items-center gap-1 text-sm font-bold text-red-800 hover:text-red-950 transition">
                <span>Voir toutes les catégories</span>
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach($categories as $cat)
                <a href="{{ route('menu.index', ['categorie' => $cat->slug]) }}"
                   class="group relative bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl border border-stone-200/80 transition transform hover:-translate-y-1">
                    <div class="h-32 w-full overflow-hidden relative">
                        <img src="{{ $cat->photo_url ?? 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&auto=format&fit=crop&q=80' }}"
                             alt="{{ $cat->nom }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/30 to-transparent"></div>
                    </div>
                    <div class="p-4 text-center">
                        <h3 class="font-bold text-slate-900 text-sm group-hover:text-red-800 transition truncate">{{ $cat->nom }}</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">{{ $cat->plats_count }} plats disponibles</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Plats Populaires / Incontournables -->
<section class="py-16 bg-white border-y border-stone-200/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="inline-block px-3 py-1 rounded-full bg-amber-100 text-amber-900 text-xs font-bold uppercase tracking-wider mb-2">Les Incontournables</span>
            <h2 class="text-3xl sm:text-4xl font-serif font-bold text-slate-900">Les Coups de Cœur de nos Clients</h2>
            <p class="text-slate-600 text-sm mt-2">Plats les plus commandés cette semaine à Lomé, préparés à la minute par notre brigade.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($platsPopulaires as $plat)
                <div class="bg-[#FAF7F2] rounded-2xl overflow-hidden border border-stone-200 flex flex-col hover:border-red-300 hover:shadow-xl transition group">
                    <!-- Photo Plat -->
                    <div class="relative h-48 overflow-hidden bg-stone-200">
                        <img src="{{ $plat->photo_url ?? 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&auto=format&fit=crop&q=80' }}"
                             alt="{{ $plat->nom }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">

                        <div class="absolute top-3 left-3 bg-amber-400 text-slate-950 text-[10px] font-extrabold uppercase px-2.5 py-1 rounded-md shadow-sm">
                            ★ Populaire
                        </div>

                        <div class="absolute top-3 right-3 bg-slate-900/80 backdrop-blur text-white text-[10px] font-semibold px-2 py-0.5 rounded-md flex items-center gap-1">
                            <i data-lucide="clock" class="w-3 h-3 text-amber-400"></i>
                            <span>{{ $plat->temps_preparation_minutes }} min</span>
                        </div>
                    </div>

                    <!-- Info Plat -->
                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            <span class="text-[11px] font-bold uppercase text-red-800 tracking-wider block mb-1">
                                {{ $plat->category->nom ?? 'Plat du chef' }}
                            </span>
                            <h3 class="font-bold text-slate-900 text-base leading-snug group-hover:text-red-800 transition">
                                {{ $plat->nom }}
                            </h3>
                            <p class="text-xs text-slate-600 mt-2 line-clamp-2 leading-relaxed">
                                {{ $plat->description }}
                            </p>
                        </div>

                        <div class="pt-5 mt-4 border-t border-stone-200 flex items-center justify-between gap-2">
                            <div>
                                <span class="text-[10px] uppercase text-slate-600 font-semibold block">Prix</span>
                                <span class="text-base font-extrabold text-red-900">{{ $plat->prix_formate }}</span>
                            </div>

                            <button type="button"
                                    @click="addToCart({{ json_encode(['id' => $plat->id, 'nom' => $plat->nom, 'prix_fcfa' => $plat->prix_fcfa, 'photo_url' => $plat->photo_url]) }}, 1, 'plat')"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-red-800 hover:bg-red-900 text-white text-xs font-bold shadow-md shadow-red-900/20 transition active:scale-95">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                                <span>Ajouter</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('menu.index') }}"
               class="inline-flex items-center gap-2 px-8 py-3.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm shadow-lg transition">
                <i data-lucide="book-open" class="w-4 h-4 text-amber-400"></i>
                <span>Consulter toute la carte des menus</span>
            </a>
        </div>
    </div>
</section>

<!-- Formules Complètes -->
<section class="py-16 bg-[#FAF7F2]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
            <div>
                <span class="text-xs uppercase font-bold text-red-800 tracking-wider">Menus Combinés</span>
                <h2 class="text-3xl font-serif font-bold text-slate-900 mt-1">Nos Formules Tout-en-Un</h2>
                <p class="text-sm text-slate-600 mt-1">Plat + boisson + dessert à tarif préférentiel</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($formules as $formule)
                <div class="bg-white rounded-2xl p-6 border border-stone-200/90 shadow-sm hover:shadow-xl transition flex flex-col justify-between relative group">
                    <div class="space-y-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <span class="inline-block px-2.5 py-0.5 rounded bg-red-50 text-red-800 text-[11px] font-bold uppercase tracking-wider">Formule</span>
                                <h3 class="text-lg font-bold text-slate-900 mt-1">{{ $formule->nom }}</h3>
                            </div>
                            <span class="text-lg font-black text-red-900">{{ $formule->prix_formate }}</span>
                        </div>

                        <p class="text-xs text-slate-500 italic">{{ $formule->description }}</p>

                        <div class="p-3.5 rounded-xl bg-stone-50 border border-stone-200 text-xs text-slate-700 leading-relaxed">
                            <strong class="text-slate-900 block mb-1">Composants inclus :</strong>
                            {{ $formule->composants }}
                        </div>
                    </div>

                    <button type="button"
                            @click="addToCart({{ json_encode(['id' => $formule->id, 'nom' => $formule->nom, 'prix_fcfa' => $formule->prix_fcfa, 'photo_url' => $formule->photo_url]) }}, 1, 'formule')"
                            class="mt-6 w-full py-3 rounded-xl bg-stone-900 hover:bg-red-800 text-white font-bold text-xs flex items-center justify-center gap-2 transition shadow">
                        <i data-lucide="plus" class="w-4 h-4 text-amber-400"></i>
                        <span>Ajouter cette formule au panier</span>
                    </button>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Zones de Livraison à Lomé -->
<section class="py-16 bg-stone-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
            <div class="lg:col-span-5 space-y-5">
                <span class="text-xs font-bold uppercase tracking-widest text-amber-400">Service de Livraison</span>
                <h2 class="text-3xl sm:text-4xl font-serif font-bold leading-tight">Où que vous soyez à Lomé, nous vous livrons chaud !</h2>
                <p class="text-sm text-stone-300 leading-relaxed">
                    Grâce à notre équipe de coursiers dédiés Café Paris équipés de caissons isothermes, vos plats arrivent à température parfaite chez vous ou à votre bureau.
                </p>
                <div class="pt-2">
                    <a href="{{ route('checkout.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-amber-400 text-stone-950 font-bold text-sm shadow hover:bg-amber-300 transition">
                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                        <span>Commander maintenant pour ma zone</span>
                    </a>
                </div>
            </div>

            <div class="lg:col-span-7">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($zones as $z)
                        <div class="p-4 rounded-xl bg-stone-800/80 border border-stone-700 flex items-start justify-between gap-3">
                            <div class="space-y-1">
                                <h4 class="text-sm font-bold text-white flex items-center gap-1.5">
                                    <i data-lucide="navigation" class="w-3.5 h-3.5 text-amber-400"></i>
                                    <span>{{ $z->nom_zone }}</span>
                                </h4>
                                <p class="text-xs text-stone-400">{{ $z->description }}</p>
                                <span class="inline-block text-[10px] text-stone-300">Délai estimé : ~{{ $z->delai_estime_min }} min</span>
                            </div>
                            <span class="px-2.5 py-1 rounded bg-stone-900 text-amber-400 text-xs font-bold whitespace-nowrap border border-stone-700">
                                {{ $z->frais_formate }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call To Action Réservation de Table -->
<section class="py-20 bg-gradient-to-r from-red-950 to-stone-950 text-white relative overflow-hidden">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10 space-y-6">
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-900/60 text-amber-300 text-xs font-semibold border border-red-700/60">
            <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
            <span>Dîners d'affaires, anniversaires & soirées privées</span>
        </span>

        <h2 class="text-3xl sm:text-4xl font-serif font-bold">Envie de profiter de l'ambiance Café Paris sur place ?</h2>
        <p class="text-sm sm:text-base text-stone-300 max-w-2xl mx-auto leading-relaxed">
            Notre terrasse ombragée et notre salle climatisée vous accueillent tous les jours. Réservez votre table en quelques clics sans attente.
        </p>

        <div class="pt-4">
            <a href="{{ route('reservation.create') }}" class="inline-flex items-center gap-2 px-8 py-4 rounded-xl bg-amber-400 hover:bg-amber-300 text-stone-950 font-bold text-sm shadow-xl transition group">
                <i data-lucide="utensils" class="w-4 h-4"></i>
                <span>Réserver une table maintenant</span>
                <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition"></i>
            </a>
        </div>
    </div>
</section>
@endsection
