@extends('layouts.admin')

@section('title', 'Gestion des Menus & Plats')
@section('page_title', 'Gestion de la Carte & des Menus')

@section('content')
<div class="space-y-6" x-data="{ tab: 'plats', showCategoryModal: false, showFormuleModal: false }">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-900 p-6 rounded-3xl border border-slate-800">
        <!-- Tabs -->
        <div class="flex items-center gap-2 p-1 rounded-xl bg-slate-800/80 border border-slate-700 text-xs font-bold">
            <button type="button" @click="tab = 'plats'"
                    class="px-4 py-2 rounded-lg transition"
                    :class="tab === 'plats' ? 'bg-red-800 text-white shadow' : 'text-slate-400 hover:text-white'">
                Plats de la Carte ({{ $plats->total() }})
            </button>
            <button type="button" @click="tab = 'categories'"
                    class="px-4 py-2 rounded-lg transition"
                    :class="tab === 'categories' ? 'bg-red-800 text-white shadow' : 'text-slate-400 hover:text-white'">
                Catégories ({{ $categories->count() }})
            </button>
            <button type="button" @click="tab = 'formules'"
                    class="px-4 py-2 rounded-lg transition"
                    :class="tab === 'formules' ? 'bg-red-800 text-white shadow' : 'text-slate-400 hover:text-white'">
                Formules Complètes ({{ $formules->count() }})
            </button>
        </div>

        <!-- Create Buttons -->
        <div class="flex items-center gap-2">
            <a x-show="tab === 'plats'" href="{{ route('admin.menus.plats.create') }}"
               class="px-4 py-2.5 rounded-xl bg-red-800 hover:bg-red-700 text-white font-bold text-xs shadow flex items-center gap-1.5 transition">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Nouveau Plat</span>
            </a>

            <button x-show="tab === 'categories'" type="button" @click="showCategoryModal = true"
                    class="px-4 py-2.5 rounded-xl bg-red-800 hover:bg-red-700 text-white font-bold text-xs shadow flex items-center gap-1.5 transition">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Nouvelle Catégorie</span>
            </button>

            <button x-show="tab === 'formules'" type="button" @click="showFormuleModal = true"
                    class="px-4 py-2.5 rounded-xl bg-red-800 hover:bg-red-700 text-white font-bold text-xs shadow flex items-center gap-1.5 transition">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Nouvelle Formule</span>
            </button>
        </div>
    </div>

    <!-- TAB 1: PLATS -->
    <div x-show="tab === 'plats'" class="bg-slate-900 rounded-3xl border border-slate-800 p-6 space-y-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-400 border-b border-slate-800 uppercase tracking-wider text-[11px]">
                        <th class="py-3 px-3">Visuel</th>
                        <th class="py-3 px-3">Nom du Plat</th>
                        <th class="py-3 px-3">Catégorie</th>
                        <th class="py-3 px-3">Prix (FCFA)</th>
                        <th class="py-3 px-3">Temps Cuisson</th>
                        <th class="py-3 px-3 text-center">Disponibilité (Rupture)</th>
                        <th class="py-3 px-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60" x-data="platList()">
                    @foreach($plats as $plat)
                        <tr class="hover:bg-slate-800/40 transition">
                            <td class="py-3 px-3">
                                <img src="{{ $plat->photo_url ?? 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=120&auto=format&fit=crop&q=80' }}"
                                     alt="{{ $plat->nom }}"
                                     class="w-12 h-12 rounded-xl object-cover border border-slate-700">
                            </td>
                            <td class="py-3 px-3">
                                <p class="font-bold text-white text-sm">{{ $plat->nom }}</p>
                                <p class="text-[11px] text-slate-400 line-clamp-1">{{ $plat->description }}</p>
                            </td>
                            <td class="py-3 px-3 text-slate-300">
                                <span class="px-2 py-0.5 rounded bg-slate-800 text-slate-300 text-[10px] font-semibold border border-slate-700">
                                    {{ $plat->category->nom ?? 'Général' }}
                                </span>
                            </td>
                            <td class="py-3 px-3 font-bold text-amber-400 text-sm">
                                {{ $plat->prix_formate }}
                            </td>
                            <td class="py-3 px-3 text-slate-400">
                                ~{{ $plat->temps_preparation_minutes }} min
                            </td>
                            <td class="py-3 px-3 text-center">
                                <!-- Live AJAX Toggle Button -->
                                <button type="button"
                                        @click="toggleAvailability({{ $plat->id }}, $el)"
                                        class="px-3 py-1 rounded-full text-[11px] font-bold transition cursor-pointer border {{ $plat->disponible ? 'bg-emerald-950 text-emerald-400 border-emerald-700 hover:bg-rose-950 hover:text-rose-400' : 'bg-rose-950 text-rose-400 border-rose-700 hover:bg-emerald-950 hover:text-emerald-400' }}"
                                        title="Cliquer pour changer le stock">
                                    {{ $plat->disponible ? '✓ Disponible' : '✕ Rupture' }}
                                </button>
                            </td>
                            <td class="py-3 px-3 text-right space-x-1">
                                <a href="{{ route('admin.menus.plats.edit', $plat->id) }}"
                                   class="p-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 inline-block transition" title="Modifier">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>

                                <form method="POST" action="{{ route('admin.menus.plats.destroy', $plat->id) }}" class="inline-block"
                                      onsubmit="return confirm('Supprimer définitivement ce plat ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg bg-slate-800 hover:bg-rose-900 text-rose-400 inline-block transition" title="Supprimer">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pt-4 border-t border-slate-800">
            {{ $plats->links() }}
        </div>
    </div>

    <!-- TAB 2: CATEGORIES -->
    <div x-show="tab === 'categories'" class="bg-slate-900 rounded-3xl border border-slate-800 p-6 space-y-4" x-cloak>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($categories as $cat)
                <div class="p-4 rounded-2xl bg-slate-800 border border-slate-700 flex items-start justify-between gap-3">
                    <div class="space-y-1">
                        <h4 class="font-bold text-white text-sm">{{ $cat->nom }}</h4>
                        <p class="text-xs text-slate-400 line-clamp-1">{{ $cat->description }}</p>
                        <span class="inline-block text-[11px] text-amber-400 font-semibold">{{ $cat->plats_count }} plat(s) associé(s)</span>
                    </div>

                    <div class="flex items-center gap-1">
                        <form method="POST" action="{{ route('admin.menus.categories.destroy', $cat->id) }}"
                              onsubmit="return confirm('Attention : supprimer cette catégorie supprimera également ses plats ! Continuer ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 rounded-lg bg-slate-700 hover:bg-rose-900 text-rose-400 transition" title="Supprimer">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- TAB 3: FORMULES -->
    <div x-show="tab === 'formules'" class="bg-slate-900 rounded-3xl border border-slate-800 p-6 space-y-4" x-cloak>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($formules as $f)
                <div class="p-5 rounded-2xl bg-slate-800 border border-slate-700 space-y-3 flex flex-col justify-between">
                    <div>
                        <div class="flex items-start justify-between">
                            <h4 class="font-bold text-white text-base">{{ $f->nom }}</h4>
                            <span class="font-black text-amber-400 text-sm">{{ $f->prix_formate }}</span>
                        </div>
                        <p class="text-xs text-slate-400 italic mt-1">{{ $f->description }}</p>
                        <div class="p-3 rounded-xl bg-slate-900 border border-slate-750 text-xs text-slate-300 mt-3">
                            <strong class="text-white block mb-0.5">Composants :</strong>
                            {{ $f->composants }}
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.menus.formules.destroy', $f->id) }}" class="pt-2 text-right"
                          onsubmit="return confirm('Supprimer cette formule ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-slate-700 hover:bg-rose-900 text-rose-400 text-xs font-bold transition">
                            Supprimer
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal Nouvelle Catégorie -->
    <div x-show="showCategoryModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl space-y-4" @click.outside="showCategoryModal = false">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <h3 class="font-bold text-white text-base">Ajouter une Catégorie</h3>
                <button type="button" @click="showCategoryModal = false" class="text-slate-400 hover:text-white"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>

            <form method="POST" action="{{ route('admin.menus.categories.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Nom de la catégorie *</label>
                    <input type="text" name="nom" required placeholder="Ex: Nos Pâtes & Spaghettis"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white outline-none focus:border-red-800">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Description courte</label>
                    <textarea name="description" rows="2" placeholder="Description affichée sur la carte..."
                              class="w-full px-3.5 py-2 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white outline-none focus:border-red-800"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Photo URL d'illustration</label>
                    <input type="text" name="photo_url" placeholder="https://images.unsplash.com/..."
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white outline-none focus:border-red-800">
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="showCategoryModal = false" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 text-xs font-bold">Annuler</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-red-800 hover:bg-red-700 text-white text-xs font-bold shadow">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Nouvelle Formule -->
    <div x-show="showFormuleModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl space-y-4" @click.outside="showFormuleModal = false">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <h3 class="font-bold text-white text-base">Ajouter une Formule Menu</h3>
                <button type="button" @click="showFormuleModal = false" class="text-slate-400 hover:text-white"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>

            <form method="POST" action="{{ route('admin.menus.formules.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Nom de la formule *</label>
                    <input type="text" name="nom" required placeholder="Ex: Formule Midi Express"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white outline-none focus:border-red-800">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Prix en FCFA *</label>
                    <input type="number" name="prix_fcfa" required placeholder="5000"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white outline-none focus:border-red-800">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Composants détaillés *</label>
                    <textarea name="composants" rows="2" required placeholder="1 Burger + Frites + Boisson 33cl..."
                              class="w-full px-3.5 py-2 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white outline-none focus:border-red-800"></textarea>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="showFormuleModal = false" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 text-xs font-bold">Annuler</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-red-800 hover:bg-red-700 text-white text-xs font-bold shadow">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function platList() {
        return {
            async toggleAvailability(platId, btnEl) {
                try {
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const res = await fetch('{{ url('admin/menus/plats') }}/' + platId + '/toggle', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token
                        }
                    });
                    const data = await res.json();
                    if (data.success) {
                        if (data.disponible) {
                            btnEl.className = 'px-3 py-1 rounded-full text-[11px] font-bold transition cursor-pointer border bg-emerald-950 text-emerald-400 border-emerald-700 hover:bg-rose-950 hover:text-rose-400';
                            btnEl.innerText = '✓ Disponible';
                        } else {
                            btnEl.className = 'px-3 py-1 rounded-full text-[11px] font-bold transition cursor-pointer border bg-rose-950 text-rose-400 border-rose-700 hover:bg-emerald-950 hover:text-emerald-400';
                            btnEl.innerText = '✕ Rupture';
                        }
                    }
                } catch (e) {
                    alert('Erreur lors du changement de disponibilité.');
                }
            }
        };
    }
</script>
@endpush
