@extends('layouts.app')

@section('title', 'Carte des Menus & Tarifs — Café Paris Lomé')

@section('content')
<!-- Header Banner -->
<section class="bg-gradient-to-r from-red-950 via-stone-900 to-red-950 text-white py-14 border-b border-red-900/40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-3">
        <span class="inline-block px-3 py-1 rounded-full bg-red-900/60 text-amber-300 text-xs font-bold uppercase tracking-wider border border-red-700/50">
            Carte Gastronomique
        </span>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-serif font-bold tracking-tight">Nos Menus & Plats Gourmands</h1>
        <p class="text-sm sm:text-base text-stone-300 max-w-xl mx-auto">
            Faites votre choix parmi nos pizzas cuites au feu de bois, nos viandes braisées et nos spécialités togolaises réinventées.
        </p>
    </div>
</section>

<!-- Filter & Search Bar -->
<section class="sticky top-20 z-30 bg-white/95 backdrop-blur-md border-b border-stone-200 shadow-sm py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <form method="GET" action="{{ route('menu.index') }}" class="flex flex-col lg:flex-row items-center justify-between gap-4">
            <!-- Search input -->
            <div class="relative w-full lg:w-96">
                <i data-lucide="search" class="w-4 h-4 text-stone-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                <input type="text"
                       name="q"
                       value="{{ request('q') }}"
                       placeholder="Rechercher un plat (ex: Pizza, Capitaine, Burger...)"
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-stone-200 bg-stone-50 text-sm focus:bg-white focus:border-red-800 focus:ring-1 focus:ring-red-800 transition outline-none">
            </div>

            <!-- Category Pills (Horizontal Scroll) -->
            <div class="w-full lg:flex-1 overflow-x-auto no-scrollbar flex items-center gap-2 py-1">
                <a href="{{ route('menu.index', array_merge(request()->except('categorie', 'page'), ['categorie' => 'tous'])) }}"
                   class="whitespace-nowrap px-4 py-2 rounded-xl text-xs font-bold transition {{ !$selectedCategorySlug || $selectedCategorySlug === 'tous' ? 'bg-red-800 text-white shadow-sm' : 'bg-stone-100 text-slate-700 hover:bg-stone-200' }}">
                    Tous les plats
                </a>

                @foreach($categories as $cat)
                    <a href="{{ route('menu.index', array_merge(request()->except('categorie', 'page'), ['categorie' => $cat->slug])) }}"
                       class="whitespace-nowrap px-4 py-2 rounded-xl text-xs font-bold transition {{ $selectedCategorySlug === $cat->slug ? 'bg-red-800 text-white shadow-sm' : 'bg-stone-100 text-slate-700 hover:bg-stone-200' }}">
                        {{ $cat->nom }}
                    </a>
                @endforeach
            </div>

            <!-- Sort dropdown -->
            <div class="w-full lg:w-auto shrink-0 flex items-center gap-2">
                @if($selectedCategorySlug)
                    <input type="hidden" name="categorie" value="{{ $selectedCategorySlug }}">
                @endif
                <select name="tri" onchange="this.form.submit()" class="w-full lg:w-auto px-3.5 py-2.5 rounded-xl border border-stone-200 bg-stone-50 text-xs font-semibold focus:border-red-800 outline-none">
                    <option value="ordre" {{ request('tri') === 'ordre' ? 'selected' : '' }}>Ordre de la carte</option>
                    <option value="populaire" {{ request('tri') === 'populaire' ? 'selected' : '' }}>Les plus populaires</option>
                    <option value="prix_asc" {{ request('tri') === 'prix_asc' ? 'selected' : '' }}>Prix : Moins cher</option>
                    <option value="prix_desc" {{ request('tri') === 'prix_desc' ? 'selected' : '' }}>Prix : Plus cher</option>
                </select>
            </div>
        </form>
    </div>
</section>

<!-- Menu Grid Section -->
<section class="py-12 bg-[#FAF7F2]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($plats->isEmpty())
            <div class="bg-white rounded-2xl p-12 text-center border border-stone-200 max-w-lg mx-auto space-y-4">
                <div class="w-16 h-16 rounded-full bg-stone-100 text-stone-400 flex items-center justify-center mx-auto">
                    <i data-lucide="utensils" class="w-8 h-8"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Aucun plat trouvé</h3>
                <p class="text-xs text-slate-500">Essayez de modifier votre recherche ou sélectionnez une autre catégorie.</p>
                <a href="{{ route('menu.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-red-800 text-white text-xs font-bold shadow hover:bg-red-900 transition">
                    Réinitialiser les filtres
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($plats as $plat)
                    <div class="bg-white rounded-2xl overflow-hidden border border-stone-200 flex flex-col hover:border-red-300 hover:shadow-xl transition group">
                        <!-- Photo Plat -->
                        <div class="relative h-48 overflow-hidden bg-stone-100">
                            <img src="{{ $plat->photo_url ?? 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&auto=format&fit=crop&q=80' }}"
                                 alt="{{ $plat->nom }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500">

                            @if($plat->est_populaire)
                                <div class="absolute top-3 left-3 bg-amber-400 text-slate-950 text-[10px] font-extrabold uppercase px-2.5 py-1 rounded-md shadow-sm">
                                    ★ Populaire
                                </div>
                            @endif

                            <div class="absolute top-3 right-3 bg-slate-900/80 backdrop-blur text-white text-[10px] font-semibold px-2 py-0.5 rounded-md flex items-center gap-1">
                                <i data-lucide="clock" class="w-3 h-3 text-amber-400"></i>
                                <span>{{ $plat->temps_preparation_minutes }} min</span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-5 flex-1 flex flex-col justify-between">
                            <div>
                                <span class="text-[10px] font-extrabold uppercase text-red-800 tracking-wider block mb-1">
                                    {{ $plat->category->nom ?? 'Plat' }}
                                </span>
                                <a href="{{ route('menu.show', $plat->slug) }}" class="font-bold text-slate-900 text-base leading-snug group-hover:text-red-800 transition block">
                                    {{ $plat->nom }}
                                </a>
                                <p class="text-xs text-slate-500 mt-2 line-clamp-2 leading-relaxed">
                                    {{ $plat->description }}
                                </p>
                            </div>

                            <div class="pt-5 mt-4 border-t border-stone-100 flex items-center justify-between gap-2">
                                <div>
                                    <span class="text-xs font-extrabold text-red-900">{{ $plat->prix_formate }}</span>
                                </div>

                                @if($plat->disponible)
                                    <button type="button"
                                            @click="addToCart({{ json_encode(['id' => $plat->id, 'nom' => $plat->nom, 'prix_fcfa' => $plat->prix_fcfa, 'photo_url' => $plat->photo_url]) }}, 1, 'plat')"
                                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-red-800 hover:bg-red-900 text-white text-xs font-bold shadow-md shadow-red-900/20 transition active:scale-95">
                                        <i data-lucide="plus" class="w-4 h-4"></i>
                                        <span>Ajouter</span>
                                    </button>
                                @else
                                    <span class="px-2.5 py-1 rounded bg-stone-100 text-stone-500 text-[11px] font-semibold">
                                        Rupture
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-10">
                {{ $plats->links() }}
            </div>
        @endif
    </div>
</section>

<!-- Formules Section on Menu Page -->
@if(isset($formules) && $formules->isNotEmpty())
<section class="py-14 bg-white border-t border-stone-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <span class="text-xs font-bold uppercase text-red-800 tracking-wider">Menus Tout Compris</span>
            <h2 class="text-2xl font-serif font-bold text-slate-900 mt-1">Nos Formules Complètes</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($formules as $formule)
                <div class="bg-[#FAF7F2] rounded-2xl p-6 border border-stone-200 flex flex-col justify-between">
                    <div>
                        <div class="flex items-start justify-between">
                            <h3 class="font-bold text-slate-900 text-lg">{{ $formule->nom }}</h3>
                            <span class="font-black text-red-900 text-base">{{ $formule->prix_formate }}</span>
                        </div>
                        <p class="text-xs text-slate-600 mt-2">{{ $formule->composants }}</p>
                    </div>

                    <button type="button"
                            @click="addToCart({{ json_encode(['id' => $formule->id, 'nom' => $formule->nom, 'prix_fcfa' => $formule->prix_fcfa, 'photo_url' => $formule->photo_url]) }}, 1, 'formule')"
                            class="mt-6 w-full py-2.5 rounded-xl bg-red-800 hover:bg-red-900 text-white text-xs font-bold shadow transition flex items-center justify-center gap-2">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        <span>Ajouter cette formule</span>
                    </button>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
