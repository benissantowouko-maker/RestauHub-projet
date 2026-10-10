@extends('layouts.admin')

@section('title', 'Paramètres & Configuration')
@section('page_title', 'Paramètres Généraux, Zones de Lomé & Utilisateurs')

@section('content')
<div class="space-y-6" x-data="{ tab: 'general', showZoneModal: false, showUserModal: false }">

    <!-- Top Tabs -->
    <div class="flex items-center gap-2 p-1 rounded-xl bg-slate-900 border border-slate-800 text-xs font-bold max-w-xl">
        <button type="button" @click="tab = 'general'"
                class="flex-1 py-2.5 rounded-lg transition"
                :class="tab === 'general' ? 'bg-red-800 text-white shadow' : 'text-slate-400 hover:text-white'">
            Restaurant & Paiements
        </button>
        <button type="button" @click="tab = 'zones'"
                class="flex-1 py-2.5 rounded-lg transition"
                :class="tab === 'zones' ? 'bg-red-800 text-white shadow' : 'text-slate-400 hover:text-white'">
            Zones de Livraison Lomé ({{ $zones->count() }})
        </button>
        <button type="button" @click="tab = 'utilisateurs'"
                class="flex-1 py-2.5 rounded-lg transition"
                :class="tab === 'utilisateurs' ? 'bg-red-800 text-white shadow' : 'text-slate-400 hover:text-white'">
            Comptes Staff & Livreurs ({{ $utilisateurs->count() }})
        </button>
    </div>

    <!-- TAB 1: General Info & Mobile Money numbers -->
    <div x-show="tab === 'general'" class="bg-slate-900 rounded-3xl border border-slate-800 p-6 sm:p-10 space-y-6 max-w-4xl">
        <div class="border-b border-slate-800 pb-3">
            <h3 class="font-bold text-white text-base">Informations Générales du Café Paris</h3>
            <p class="text-xs text-slate-400">Coordonnées affichées sur le site public, factures et notifications</p>
        </div>

        <form method="POST" action="{{ route('admin.parametres.update_general') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Nom de l'établissement</label>
                    <input type="text" name="nom_restaurant" value="{{ $parametres['nom_restaurant'] ?? 'Café Paris Lomé' }}" required
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white focus:border-red-800 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Slogan ou accroche</label>
                    <input type="text" name="slogan" value="{{ $parametres['slogan'] ?? 'Brasserie parisienne au cœur de Lomé' }}"
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white focus:border-red-800 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Téléphone Principal (+228)</label>
                    <input type="text" name="telephone_principal" value="{{ $parametres['telephone_principal'] ?? '+228 90 00 12 34' }}"
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white focus:border-red-800 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Téléphone Livraisons (+228)</label>
                    <input type="text" name="telephone_secondaire" value="{{ $parametres['telephone_secondaire'] ?? '+228 98 00 12 34' }}"
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white focus:border-red-800 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Email de contact</label>
                    <input type="email" name="email_contact" value="{{ $parametres['email_contact'] ?? 'contact@cafeparis-lome.site' }}"
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white focus:border-red-800 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Horaires de service</label>
                    <input type="text" name="horaires" value="{{ $parametres['horaires'] ?? 'Tous les jours : 08h00 - 23h30' }}"
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white focus:border-red-800 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-300 mb-1.5">Adresse physique à Lomé</label>
                <input type="text" name="adresse" value="{{ $parametres['adresse'] ?? 'Boulevard de la Marina, Angle Rue de la Paix, Lomé, Togo' }}"
                       class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white focus:border-red-800 outline-none">
            </div>

            <!-- Mobile Money Togolais -->
            <div class="pt-4 border-t border-slate-800 space-y-4">
                <h4 class="text-xs font-bold uppercase text-amber-400">Comptes Marchands Mobile Money (Togo)</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1.5">Numéro Marchand T-Money (Togocom)</label>
                        <input type="text" name="numero_tmoney" value="{{ $parametres['numero_tmoney'] ?? '+228 90 00 12 34' }}"
                               class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white focus:border-red-800 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1.5">Numéro Marchand Flooz (Moov Africa)</label>
                        <input type="text" name="numero_flooz" value="{{ $parametres['numero_flooz'] ?? '+228 98 00 12 34' }}"
                               class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white focus:border-red-800 outline-none">
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-800 flex justify-end">
                <button type="submit" class="px-6 py-3 rounded-xl bg-red-800 hover:bg-red-700 text-white font-bold text-xs shadow-xl transition">
                    Enregistrer les paramètres
                </button>
            </div>
        </form>
    </div>

    <!-- TAB 2: ZONES DE LIVRAISON -->
    <div x-show="tab === 'zones'" class="bg-slate-900 rounded-3xl border border-slate-800 p-6 space-y-4" x-cloak>
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <div>
                <h3 class="font-bold text-white text-base">Tarification par Quartier à Lomé</h3>
                <p class="text-xs text-slate-400">Frais appliqués automatiquement lors du tunnel de commande</p>
            </div>
            <button type="button" @click="showZoneModal = true" class="px-4 py-2 rounded-xl bg-red-800 hover:bg-red-700 text-white font-bold text-xs shadow flex items-center gap-1.5">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Ajouter une zone</span>
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-400 border-b border-slate-800 uppercase tracking-wider text-[11px]">
                        <th class="py-3 px-3">Nom de la Zone / Quartiers</th>
                        <th class="py-3 px-3">Frais de livraison (FCFA)</th>
                        <th class="py-3 px-3">Délai estimé</th>
                        <th class="py-3 px-3">Description</th>
                        <th class="py-3 px-3">Statut</th>
                        <th class="py-3 px-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @foreach($zones as $z)
                        <tr class="hover:bg-slate-800/40 transition">
                            <td class="py-3.5 px-3 font-bold text-white">{{ $z->nom_zone }}</td>
                            <td class="py-3.5 px-3 font-mono font-bold text-amber-400">{{ $z->frais_formate }}</td>
                            <td class="py-3.5 px-3 text-slate-300">~{{ $z->delai_estime_min }} min</td>
                            <td class="py-3.5 px-3 text-slate-400">{{ $z->description }}</td>
                            <td class="py-3.5 px-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $z->active ? 'bg-emerald-950 text-emerald-400 border border-emerald-800' : 'bg-slate-800 text-slate-500' }}">
                                    {{ $z->active ? 'Active' : 'Inactif' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-3 text-right">
                                <form method="POST" action="{{ route('admin.parametres.zones.destroy', $z->id) }}" class="inline-block"
                                      onsubmit="return confirm('Supprimer cette zone de livraison ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-slate-500 hover:text-rose-400 transition" title="Supprimer">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- TAB 3: UTILISATEURS STAFF & COURSIERS -->
    <div x-show="tab === 'utilisateurs'" class="bg-slate-900 rounded-3xl border border-slate-800 p-6 space-y-4" x-cloak>
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <div>
                <h3 class="font-bold text-white text-base">Comptes Utilisateurs Internes</h3>
                <p class="text-xs text-slate-400">Directeurs, cuisiniers et coursiers de livraison</p>
            </div>
            <button type="button" @click="showUserModal = true" class="px-4 py-2 rounded-xl bg-red-800 hover:bg-red-700 text-white font-bold text-xs shadow flex items-center gap-1.5">
                <i data-lucide="user-plus" class="w-4 h-4"></i>
                <span>Nouveau compte staff</span>
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-400 border-b border-slate-800 uppercase tracking-wider text-[11px]">
                        <th class="py-3 px-3">Nom</th>
                        <th class="py-3 px-3">Email de connexion</th>
                        <th class="py-3 px-3">Téléphone direct</th>
                        <th class="py-3 px-3">Rôle attribué</th>
                        <th class="py-3 px-3">Statut du compte</th>
                        <th class="py-3 px-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @foreach($utilisateurs as $u)
                        <tr class="hover:bg-slate-800/40 transition">
                            <td class="py-3.5 px-3 font-bold text-white">{{ $u->name }}</td>
                            <td class="py-3.5 px-3 text-slate-300 font-mono">{{ $u->email }}</td>
                            <td class="py-3.5 px-3 text-slate-400">{{ $u->telephone ?? 'Non renseigné' }}</td>
                            <td class="py-3.5 px-3">
                                <span class="px-2.5 py-0.5 rounded text-[11px] font-bold uppercase {{ match($u->role) {
                                    'admin' => 'bg-red-950 text-red-300 border border-red-800',
                                    'staff' => 'bg-amber-950 text-amber-300 border border-amber-800',
                                    'livreur' => 'bg-indigo-950 text-indigo-300 border border-indigo-800',
                                    default => 'bg-slate-800 text-slate-300'
                                } }}">
                                    {{ $u->role }}
                                </span>
                            </td>
                            <td class="py-3.5 px-3">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $u->actif ? 'bg-emerald-950 text-emerald-400 border border-emerald-800' : 'bg-rose-950 text-rose-400 border border-rose-800' }}">
                                    {{ $u->actif ? 'Actif' : 'Désactivé' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-3 text-right">
                                @if($u->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.parametres.users.toggle', $u->id) }}">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 rounded-lg text-xs font-semibold {{ $u->actif ? 'bg-slate-800 hover:bg-rose-950 text-rose-400' : 'bg-emerald-900 hover:bg-emerald-800 text-emerald-200' }}">
                                            {{ $u->actif ? 'Désactiver' : 'Activer' }}
                                        </button>
                                    </form>
                                @else
                                    <span class="text-slate-500 text-[11px] italic">Votre session</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Ajout Zone -->
    <div x-show="showZoneModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl space-y-4" @click.outside="showZoneModal = false">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <h3 class="font-bold text-white text-base">Ajouter une Zone de Livraison</h3>
                <button type="button" @click="showZoneModal = false" class="text-slate-400 hover:text-white"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>

            <form method="POST" action="{{ route('admin.parametres.zones.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Nom de la Zone / Quartiers *</label>
                    <input type="text" name="nom_zone" required placeholder="Ex: Totsi / Agbalépédogan"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white outline-none focus:border-red-800">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">Frais en FCFA *</label>
                        <input type="number" name="frais_fcfa" step="100" required placeholder="1500"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white outline-none focus:border-red-800">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">Délai estimé (min) *</label>
                        <input type="number" name="delai_estime_min" required value="30"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white outline-none focus:border-red-800">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Description / Repères</label>
                    <input type="text" name="description" placeholder="Axe principal, échangeur..."
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white outline-none focus:border-red-800">
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="showZoneModal = false" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 text-xs font-bold">Annuler</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-red-800 hover:bg-red-700 text-white text-xs font-bold shadow">Créer la zone</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Ajout Utilisateur Staff -->
    <div x-show="showUserModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl space-y-4" @click.outside="showUserModal = false">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <h3 class="font-bold text-white text-base">Créer un Compte Staff / Coursier</h3>
                <button type="button" @click="showUserModal = false" class="text-slate-400 hover:text-white"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>

            <form method="POST" action="{{ route('admin.parametres.users.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Nom complet *</label>
                    <input type="text" name="name" required placeholder="Ex: Kossi Mensah"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white outline-none focus:border-red-800">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Email de connexion *</label>
                    <input type="email" name="email" required placeholder="coursier@cafeparis.com"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white outline-none focus:border-red-800">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">Téléphone (+228) *</label>
                        <input type="text" name="telephone" required placeholder="+228 90 00 00 00"
                               class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white outline-none focus:border-red-800">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-300 mb-1">Rôle *</label>
                        <select name="role" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white outline-none">
                            <option value="livreur">🛵 Coursier (Livreur)</option>
                            <option value="staff">👨‍🍳 Cuisine / Staff</option>
                            <option value="admin">👑 Administrateur</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-300 mb-1">Mot de passe temporaire *</label>
                    <input type="password" name="password" required value="password"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white outline-none focus:border-red-800">
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="showUserModal = false" class="px-4 py-2 rounded-xl bg-slate-800 text-slate-300 text-xs font-bold">Annuler</button>
                    <button type="submit" class="px-5 py-2 rounded-xl bg-red-800 hover:bg-red-700 text-white text-xs font-bold shadow">Créer le compte</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
