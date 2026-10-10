@extends('layouts.admin')

@section('title', ($isEdit ? 'Modifier' : 'Ajouter') . ' un Plat')
@section('page_title', ($isEdit ? 'Modifier le Plat « ' . $plat->nom . ' »' : 'Ajouter un Nouveau Plat'))

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <a href="{{ route('admin.menus.index') }}" class="text-xs font-bold text-slate-400 hover:text-white flex items-center gap-1">
            ← Retour à la liste des plats
        </a>
    </div>

    <div class="bg-slate-900 rounded-3xl border border-slate-800 p-6 sm:p-10 shadow-xl"
         x-data="{
             photoUrl: '{{ old('photo_url', $plat->photo_url) }}',
             setPhoto(url) { this.photoUrl = url; }
         }">

        <form method="POST" action="{{ $isEdit ? route('admin.menus.plats.update', $plat->id) : route('admin.menus.plats.store') }}" class="space-y-6">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Nom -->
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Nom du plat *</label>
                    <input type="text"
                           name="nom"
                           value="{{ old('nom', $plat->nom) }}"
                           required
                           placeholder="Ex: Entrecôte Grillée Maître d'Hôtel"
                           class="w-full px-4 py-3 rounded-xl bg-slate-800 border border-slate-700 text-sm text-white focus:border-red-800 outline-none">
                </div>

                <!-- Catégorie -->
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Catégorie du menu *</label>
                    <select name="categorie_id" required class="w-full px-4 py-3 rounded-xl bg-slate-800 border border-slate-700 text-sm text-white focus:border-red-800 outline-none">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('categorie_id', $plat->categorie_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Prix en FCFA -->
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Prix en FCFA *</label>
                    <div class="relative">
                        <input type="number"
                               name="prix_fcfa"
                               step="100"
                               min="500"
                               value="{{ old('prix_fcfa', $plat->prix_fcfa ? (int)$plat->prix_fcfa : 5000) }}"
                               required
                               placeholder="6500"
                               class="w-full px-4 py-3 rounded-xl bg-slate-800 border border-slate-700 text-sm text-white focus:border-red-800 outline-none">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-amber-400">FCFA</span>
                    </div>
                </div>

                <!-- Temps préparation -->
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Temps estimé de préparation</label>
                    <div class="relative">
                        <input type="number"
                               name="temps_preparation_minutes"
                               min="1"
                               value="{{ old('temps_preparation_minutes', $plat->temps_preparation_minutes ?? 20) }}"
                               class="w-full px-4 py-3 rounded-xl bg-slate-800 border border-slate-700 text-sm text-white focus:border-red-800 outline-none">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-slate-400">Minutes</span>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1.5">Description détaillée & Ingrédients</label>
                <textarea name="description"
                          rows="3"
                          placeholder="Décrivez les saveurs, accompagnements et garnitures..."
                          class="w-full px-4 py-3 rounded-xl bg-slate-800 border border-slate-700 text-sm text-white focus:border-red-800 outline-none">{{ old('description', $plat->description) }}</textarea>
            </div>

            <!-- Photo URL + Presets -->
            <div class="space-y-3">
                <label class="block text-xs font-bold text-slate-300 mb-1.5">Lien vers la photo du plat (URL)</label>
                <input type="text"
                       name="photo_url"
                       x-model="photoUrl"
                       placeholder="https://images.unsplash.com/photo-..."
                       class="w-full px-4 py-3 rounded-xl bg-slate-800 border border-slate-700 text-sm text-white focus:border-red-800 outline-none">

                <!-- Quick realistic food image presets -->
                <div class="p-3.5 rounded-2xl bg-slate-800/80 border border-slate-750 space-y-2">
                    <span class="text-[11px] font-bold uppercase text-slate-400">Ou choisir une photo réaliste prédéfinie :</span>
                    <div class="flex flex-wrap gap-2 text-xs">
                        <button type="button" @click="setPhoto('https://images.unsplash.com/photo-1513104890138-7c749659a591?w=600&auto=format&fit=crop&q=80')" class="px-2.5 py-1 rounded-lg bg-slate-700 hover:bg-slate-600 text-slate-200">🍕 Pizza</button>
                        <button type="button" @click="setPhoto('https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=600&auto=format&fit=crop&q=80')" class="px-2.5 py-1 rounded-lg bg-slate-700 hover:bg-slate-600 text-slate-200">🍔 Burger</button>
                        <button type="button" @click="setPhoto('https://images.unsplash.com/photo-1598515214211-89d3c73ae83b?w=600&auto=format&fit=crop&q=80')" class="px-2.5 py-1 rounded-lg bg-slate-700 hover:bg-slate-600 text-slate-200">🍗 Poulet Braisé</button>
                        <button type="button" @click="setPhoto('https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=600&auto=format&fit=crop&q=80')" class="px-2.5 py-1 rounded-lg bg-slate-700 hover:bg-slate-600 text-slate-200">🐟 Poisson / Capitaine</button>
                        <button type="button" @click="setPhoto('https://images.unsplash.com/photo-1555939594-58d7cb561ad1?w=600&auto=format&fit=crop&q=80')" class="px-2.5 py-1 rounded-lg bg-slate-700 hover:bg-slate-600 text-slate-200">🍢 Brochettes</button>
                        <button type="button" @click="setPhoto('https://images.unsplash.com/photo-1540420773420-3366772f4999?w=600&auto=format&fit=crop&q=80')" class="px-2.5 py-1 rounded-lg bg-slate-700 hover:bg-slate-600 text-slate-200">🥗 Salade</button>
                        <button type="button" @click="setPhoto('https://images.unsplash.com/photo-1587314168485-3236d6710814?w=600&auto=format&fit=crop&q=80')" class="px-2.5 py-1 rounded-lg bg-slate-700 hover:bg-slate-600 text-slate-200">🍮 Crème Brûlée</button>
                    </div>
                </div>

                <!-- Preview thumbnail -->
                <div x-show="photoUrl" class="flex items-center gap-3 pt-2">
                    <img :src="photoUrl" alt="Aperçu" class="w-16 h-16 rounded-xl object-cover border border-slate-700">
                    <span class="text-xs text-slate-400">Aperçu du visuel</span>
                </div>
            </div>

            <!-- Toggles: Disponible & Populaire -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-slate-800">
                <label class="flex items-center gap-3 p-3.5 rounded-xl bg-slate-800 border border-slate-700 cursor-pointer">
                    <input type="checkbox"
                           name="disponible"
                           value="1"
                           {{ old('disponible', $plat->disponible ?? true) ? 'checked' : '' }}
                           class="rounded text-red-800 focus:ring-red-800">
                    <div>
                        <span class="text-xs font-bold text-white block">Disponible à la vente</span>
                        <span class="text-[11px] text-slate-400">Décochez pour marquer en rupture</span>
                    </div>
                </label>

                <label class="flex items-center gap-3 p-3.5 rounded-xl bg-slate-800 border border-slate-700 cursor-pointer">
                    <input type="checkbox"
                           name="est_populaire"
                           value="1"
                           {{ old('est_populaire', $plat->est_populaire ?? false) ? 'checked' : '' }}
                           class="rounded text-amber-500 focus:ring-amber-500">
                    <div>
                        <span class="text-xs font-bold text-white block">Mettre en vedette (Populaire)</span>
                        <span class="text-[11px] text-slate-400">Affiché sur la page d'accueil</span>
                    </div>
                </label>
            </div>

            <div class="pt-6 border-t border-slate-800 flex justify-end gap-3">
                <a href="{{ route('admin.menus.index') }}" class="px-5 py-3 rounded-xl bg-slate-800 text-slate-300 font-bold text-xs hover:bg-slate-700 transition">
                    Annuler
                </a>
                <button type="submit"
                        class="px-8 py-3 rounded-xl bg-red-800 hover:bg-red-700 text-white font-bold text-xs shadow-xl transition">
                    {{ $isEdit ? 'Enregistrer les modifications' : 'Créer et ajouter le plat' }}
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
