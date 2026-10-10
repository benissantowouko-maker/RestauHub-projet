@extends('layouts.app')

@section('title', $plat->nom . ' — Café Paris Lomé')

@section('content')
<div class="py-12 bg-[#FAF7F2]">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-slate-500 mb-6">
            <a href="{{ route('home') }}" class="hover:text-red-800">Accueil</a>
            <span>/</span>
            <a href="{{ route('menu.index') }}" class="hover:text-red-800">Carte</a>
            <span>/</span>
            <span class="text-slate-800 font-semibold">{{ $plat->nom }}</span>
        </nav>

        <div class="bg-white rounded-3xl overflow-hidden border border-stone-200 shadow-sm grid grid-cols-1 md:grid-cols-2 gap-8 p-6 sm:p-10"
             x-data="{ qty: 1, note: '' }">
            <!-- Left: Photo -->
            <div class="space-y-4">
                <div class="rounded-2xl overflow-hidden border border-stone-200 h-80 sm:h-96 relative">
                    <img src="{{ $plat->photo_url ?? 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=600&auto=format&fit=crop&q=80' }}"
                         alt="{{ $plat->nom }}"
                         class="w-full h-full object-cover">
                    @if($plat->est_populaire)
                        <span class="absolute top-4 left-4 bg-amber-400 text-slate-950 text-xs font-bold px-3 py-1 rounded-lg shadow">
                            ★ Populaire
                        </span>
                    @endif
                </div>
            </div>

            <!-- Right: Details & Order -->
            <div class="flex flex-col justify-between space-y-6">
                <div class="space-y-3">
                    <span class="inline-block px-3 py-1 rounded-full bg-red-50 text-red-800 text-xs font-bold uppercase tracking-wider">
                        {{ $plat->category->nom ?? 'Plat' }}
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-serif font-bold text-slate-900 leading-tight">
                        {{ $plat->nom }}
                    </h1>
                    <div class="text-2xl font-black text-red-900">
                        {{ $plat->prix_formate }}
                    </div>

                    <div class="flex items-center gap-4 text-xs text-slate-500 pt-2 border-y border-stone-100 py-3">
                        <span class="flex items-center gap-1.5"><i data-lucide="clock" class="w-4 h-4 text-amber-500"></i> Préparation : ~{{ $plat->temps_preparation_minutes }} min</span>
                        <span>•</span>
                        <span class="flex items-center gap-1.5"><i data-lucide="check" class="w-4 h-4 text-emerald-500"></i> {{ $plat->disponible ? 'En stock' : 'Rupture' }}</span>
                    </div>

                    <p class="text-sm text-slate-600 leading-relaxed pt-2">
                        {{ $plat->description }}
                    </p>
                </div>

                <!-- Quantity & Add to Cart -->
                <div class="space-y-4 pt-4 border-t border-stone-200">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase text-slate-500">Quantité désirée :</span>
                        <div class="flex items-center border border-stone-300 rounded-xl bg-stone-50 overflow-hidden shadow-sm">
                            <button type="button" @click="if (qty > 1) qty--" class="px-3.5 py-2 text-slate-700 hover:bg-stone-200 transition">
                                <i data-lucide="minus" class="w-4 h-4"></i>
                            </button>
                            <span class="px-5 text-sm font-bold text-slate-900" x-text="qty"></span>
                            <button type="button" @click="qty++" class="px-3.5 py-2 text-slate-700 hover:bg-stone-200 transition">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-sm font-semibold text-slate-700">
                        <span>Sous-total plat :</span>
                        <span class="text-base font-bold text-slate-900" x-text="new Intl.NumberFormat('fr-FR').format({{ (float)$plat->prix_fcfa }} * qty) + ' FCFA'"></span>
                    </div>

                    <button type="button"
                            @click="addToCart({{ json_encode(['id' => $plat->id, 'nom' => $plat->nom, 'prix_fcfa' => $plat->prix_fcfa, 'photo_url' => $plat->photo_url]) }}, qty, 'plat')"
                            class="w-full py-4 rounded-xl bg-red-800 hover:bg-red-900 text-white font-bold text-sm shadow-xl shadow-red-950/20 transition flex items-center justify-center gap-2">
                        <i data-lucide="shopping-bag" class="w-5 h-5 text-amber-300"></i>
                        <span>Ajouter au panier et commander</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Similar dishes -->
        @if($similaires->isNotEmpty())
            <div class="mt-16">
                <h3 class="text-xl font-serif font-bold text-slate-900 mb-6">Dans la même catégorie</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    @foreach($similaires as $sim)
                        <a href="{{ route('menu.show', $sim->slug) }}" class="bg-white rounded-2xl p-4 border border-stone-200 shadow-sm hover:shadow-md transition flex items-center gap-4 group">
                            <img src="{{ $sim->photo_url }}" alt="{{ $sim->nom }}" class="w-16 h-16 rounded-xl object-cover shrink-0">
                            <div>
                                <h4 class="text-xs font-bold text-slate-900 group-hover:text-red-800 transition">{{ $sim->nom }}</h4>
                                <span class="text-xs font-black text-red-900 mt-1 block">{{ $sim->prix_formate }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
