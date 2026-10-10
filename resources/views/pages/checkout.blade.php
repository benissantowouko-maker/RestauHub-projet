@extends('layouts.app')

@section('title', 'Finaliser ma Commande — Café Paris Lomé')

@push('styles')
<style>
    #map-picker {
        height: 280px;
        width: 100%;
        border-radius: 1rem;
        z-index: 10;
    }
</style>
@endpush

@section('content')
<div class="py-12 bg-[#FAF7F2]" x-data="checkoutPage()">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Progress Steps Bar -->
        <div class="mb-10 max-w-2xl mx-auto">
            <div class="flex items-center justify-between relative">
                <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-stone-300 w-full -z-0"></div>
                <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-red-800 transition-all duration-300 -z-0"
                     :style="'width: ' + ((step - 1) * 50) + '%'"></div>

                <!-- Step 1 Indicator -->
                <button type="button" @click="step = 1" class="relative z-10 flex flex-col items-center gap-1.5 focus:outline-none">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold shadow-md transition"
                         :class="step >= 1 ? 'bg-red-800 text-white ring-4 ring-red-100' : 'bg-white text-slate-500 border border-stone-300'">
                        1
                    </div>
                    <span class="text-xs font-bold" :class="step >= 1 ? 'text-red-900' : 'text-slate-400'">Sélection</span>
                </button>

                <!-- Step 2 Indicator -->
                <button type="button" @click="if (cart.length > 0) step = 2" class="relative z-10 flex flex-col items-center gap-1.5 focus:outline-none">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold shadow-md transition"
                         :class="step >= 2 ? 'bg-red-800 text-white ring-4 ring-red-100' : 'bg-white text-slate-500 border border-stone-300'">
                        2
                    </div>
                    <span class="text-xs font-bold" :class="step >= 2 ? 'text-red-900' : 'text-slate-400'">Livraison & Carte</span>
                </button>

                <!-- Step 3 Indicator -->
                <button type="button" @click="if (validateStep2()) step = 3" class="relative z-10 flex flex-col items-center gap-1.5 focus:outline-none">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold shadow-md transition"
                         :class="step >= 3 ? 'bg-red-800 text-white ring-4 ring-red-100' : 'bg-white text-slate-500 border border-stone-300'">
                        3
                    </div>
                    <span class="text-xs font-bold" :class="step >= 3 ? 'text-red-900' : 'text-slate-400'">Paiement & Validation</span>
                </button>
            </div>
        </div>

        <!-- Main Form Wrapper -->
        <form method="POST" action="{{ route('checkout.process') }}" @submit="prepareSubmission($event)">
            @csrf

            <!-- Hidden cart JSON string -->
            <input type="hidden" name="panier_data" :value="JSON.stringify(cart)">
            <input type="hidden" name="latitude" :value="lat">
            <input type="hidden" name="longitude" :value="lng">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                <!-- Left Column: Step Content -->
                <div class="lg:col-span-7 space-y-6">

                    <!-- STEP 1: Mon Panier & Type -->
                    <div x-show="step === 1" class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200 shadow-sm space-y-6">
                        <div class="border-b border-stone-100 pb-4">
                            <h2 class="text-xl font-bold font-serif text-slate-900">Étape 1 : Vérification des Articles</h2>
                            <p class="text-xs text-slate-500 mt-1">Ajustez vos quantités ou ajoutez des précisions avant livraison.</p>
                        </div>

                        <!-- Empty cart warning -->
                        <template x-if="cart.length === 0">
                            <div class="py-12 text-center text-slate-400">
                                <i data-lucide="shopping-bag" class="w-12 h-12 mx-auto text-stone-300 mb-3"></i>
                                <p class="text-sm font-bold text-slate-700">Votre panier est actuellement vide.</p>
                                <a href="{{ route('menu.index') }}" class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-red-800 text-white text-xs font-bold shadow">
                                    Voir la carte des menus
                                </a>
                            </div>
                        </template>

                        <!-- Items list -->
                        <div class="space-y-3" x-show="cart.length > 0">
                            <template x-for="(item, idx) in cart" :key="idx">
                                <div class="flex items-center justify-between p-3.5 rounded-2xl bg-stone-50 border border-stone-200 gap-3">
                                    <img :src="item.image || 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=120&auto=format&fit=crop&q=80'"
                                         :alt="item.name"
                                         class="w-14 h-14 rounded-xl object-cover shrink-0 border border-stone-200">
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-bold text-slate-900 truncate" x-text="item.name"></h4>
                                        <p class="text-xs font-bold text-red-900" x-text="formatMoney(item.price) + ' FCFA'"></p>
                                    </div>
                                    <div class="flex items-center border border-stone-300 rounded-lg bg-white overflow-hidden shadow-sm shrink-0">
                                        <button type="button" @click="updateQty(idx, item.qty - 1)" class="px-2.5 py-1 text-slate-600 hover:bg-stone-100">
                                            <i data-lucide="minus" class="w-3 h-3"></i>
                                        </button>
                                        <span class="px-3 text-xs font-bold text-slate-800" x-text="item.qty"></span>
                                        <button type="button" @click="updateQty(idx, item.qty + 1)" class="px-2.5 py-1 text-slate-600 hover:bg-stone-100">
                                            <i data-lucide="plus" class="w-3 h-3"></i>
                                        </button>
                                    </div>
                                    <span class="text-xs font-black text-slate-900 shrink-0 w-20 text-right" x-text="formatMoney(item.price * item.qty) + ' FCFA'"></span>
                                </div>
                            </template>
                        </div>

                        <!-- Mode de commande -->
                        <div class="pt-4 border-t border-stone-100" x-show="cart.length > 0">
                            <label class="block text-xs font-bold uppercase text-slate-600 mb-3">Mode de commande souhaité :</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="p-4 rounded-2xl border-2 flex items-center gap-3 cursor-pointer transition"
                                       :class="orderType === 'livraison' ? 'border-red-800 bg-red-50/50' : 'border-stone-200 bg-white hover:border-stone-300'">
                                    <input type="radio" name="type" value="livraison" x-model="orderType" class="text-red-800 focus:ring-red-800">
                                    <div>
                                        <p class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                            <i data-lucide="truck" class="w-4 h-4 text-red-800"></i> Livraison à domicile
                                        </p>
                                        <p class="text-[11px] text-slate-500">Lomé et banlieue proche</p>
                                    </div>
                                </label>

                                <label class="p-4 rounded-2xl border-2 flex items-center gap-3 cursor-pointer transition"
                                       :class="orderType === 'a_emporter' ? 'border-red-800 bg-red-50/50' : 'border-stone-200 bg-white hover:border-stone-300'">
                                    <input type="radio" name="type" value="a_emporter" x-model="orderType" class="text-red-800 focus:ring-red-800">
                                    <div>
                                        <p class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                            <i data-lucide="store" class="w-4 h-4 text-red-800"></i> À Emporter
                                        </p>
                                        <p class="text-[11px] text-slate-500">Retrait au restaurant Café Paris</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Next button -->
                        <div x-show="cart.length > 0" class="pt-2">
                            <button type="button" @click="step = 2; initMap()"
                                    class="w-full py-4 rounded-xl bg-red-800 hover:bg-red-900 text-white font-bold text-sm shadow-lg shadow-red-900/20 transition flex items-center justify-center gap-2">
                                <span>Continuer vers les détails de livraison</span>
                                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <!-- STEP 2: Coordonnées & Adresse avec Carte Leaflet Interactive (Lomé) -->
                    <div x-show="step === 2" class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200 shadow-sm space-y-6">
                        <div class="border-b border-stone-100 pb-4">
                            <h2 class="text-xl font-bold font-serif text-slate-900">Étape 2 : Vos Coordonnées & Adresse</h2>
                            <p class="text-xs text-slate-500 mt-1">Renseignez votre nom, numéro togolais et positionnez précisément votre lieu de livraison sur la carte.</p>
                        </div>

                        <!-- Nom & Téléphone (+228) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nom complet ou Entreprise *</label>
                                <input type="text"
                                       name="nom_client"
                                       x-model="clientNom"
                                       required
                                       placeholder="Ex: Kodjo Mensah"
                                       class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm focus:ring-1 focus:ring-red-800 focus:border-red-800 outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Téléphone direct (+228) *</label>
                                <div class="relative">
                                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-500">+228</span>
                                    <input type="text"
                                           name="telephone_client"
                                           x-model="clientTel"
                                           required
                                           placeholder="90 12 34 56"
                                           class="w-full pl-14 pr-4 py-3 rounded-xl border border-stone-300 text-sm focus:ring-1 focus:ring-red-800 focus:border-red-800 outline-none">
                                </div>
                            </div>
                        </div>

                        <!-- Email (optionnel) -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Adresse email (pour recevoir la facture)</label>
                            <input type="email"
                                   name="email_client"
                                   x-model="clientEmail"
                                   placeholder="exemple@gmail.com"
                                   class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm focus:ring-1 focus:ring-red-800 focus:border-red-800 outline-none">
                        </div>

                        <!-- Section Livraison spécifique -->
                        <div x-show="orderType === 'livraison'" class="space-y-4 pt-2">
                            <!-- Zone selector -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Zone de livraison à Lomé *</label>
                                <select name="zone_livraison_id"
                                        x-model="selectedZoneId"
                                        @change="onZoneChange()"
                                        class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm focus:ring-1 focus:ring-red-800 focus:border-red-800 outline-none bg-stone-50">
                                    @foreach($zones as $zone)
                                        <option value="{{ $zone->id }}" data-frais="{{ (float)$zone->frais_fcfa }}" data-nom="{{ $zone->nom_zone }}">
                                            {{ $zone->nom_zone }} — {{ $zone->frais_formate }} (Délai ~{{ $zone->delai_estime_min }} min)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Adresse textuelle -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Adresse textuelle / Rue / Repère *</label>
                                <input type="text"
                                       name="adresse_livraison"
                                       x-model="clientAdresse"
                                       placeholder="Ex: Tokoin Ramco, face pharmacie de l'Union"
                                       class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm focus:ring-1 focus:ring-red-800 focus:border-red-800 outline-none">
                            </div>

                            <!-- CARTE INTERACTIVE LEAFLET (Résout l'erreur 'API key required' de la démo) -->
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <label class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                        <i data-lucide="map-pin" class="w-4 h-4 text-red-800"></i>
                                        <span>Positionner votre adresse exacte sur la carte de Lomé :</span>
                                    </label>
                                    <span class="text-[11px] text-amber-700 font-semibold bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                                        Faites glisser le marqueur rouge
                                    </span>
                                </div>

                                <!-- Raccourcis de quartiers rapides de Lomé -->
                                <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar py-1">
                                    <span class="text-[11px] text-slate-400 font-bold shrink-0">Centrer sur :</span>
                                    <button type="button" @click="centerLome(6.1245, 1.2098, 'Marina / Kodjoviakopé')" class="px-2.5 py-1 rounded-lg bg-stone-100 hover:bg-stone-200 text-slate-700 text-[11px] font-semibold whitespace-nowrap">Kodjoviakopé</button>
                                    <button type="button" @click="centerLome(6.1412, 1.2185, 'Tokoin Ramco')" class="px-2.5 py-1 rounded-lg bg-stone-100 hover:bg-stone-200 text-slate-700 text-[11px] font-semibold whitespace-nowrap">Tokoin</button>
                                    <button type="button" @click="centerLome(6.1523, 1.2345, 'Hedzranawoé')" class="px-2.5 py-1 rounded-lg bg-stone-100 hover:bg-stone-200 text-slate-700 text-[11px] font-semibold whitespace-nowrap">Hedzranawoé</button>
                                    <button type="button" @click="centerLome(6.1850, 1.2050, 'Agoè-Nyivé')" class="px-2.5 py-1 rounded-lg bg-stone-100 hover:bg-stone-200 text-slate-700 text-[11px] font-semibold whitespace-nowrap">Agoè</button>
                                    <button type="button" @click="centerLome(6.1310, 1.2420, 'Bè / Marché')" class="px-2.5 py-1 rounded-lg bg-stone-100 hover:bg-stone-200 text-slate-700 text-[11px] font-semibold whitespace-nowrap">Bè</button>
                                    <button type="button" @click="centerLome(6.1580, 1.3250, 'Baguida Plage')" class="px-2.5 py-1 rounded-lg bg-stone-100 hover:bg-stone-200 text-slate-700 text-[11px] font-semibold whitespace-nowrap">Baguida</button>
                                </div>

                                <!-- Container de la carte -->
                                <div id="map-picker" class="border border-stone-300 shadow-inner"></div>

                                <div class="flex items-center justify-between text-[11px] text-slate-500 pt-1">
                                    <span class="inline-flex items-center gap-1">
                                        <i data-lucide="check-circle" class="w-3.5 h-3.5 text-emerald-600"></i>
                                        <span>Coordonnées GPS : <strong x-text="lat.toFixed(4) + ', ' + lng.toFixed(4)"></strong></span>
                                    </span>
                                    <span class="text-slate-400">Précision garantie pour le coursier</span>
                                </div>
                            </div>

                            <!-- Note libre pour le coursier -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center gap-1.5">
                                    <i data-lucide="file-text" class="w-3.5 h-3.5 text-amber-600"></i>
                                    <span>Note libre pour le livreur (ex: « au portail bleu, maison à étage »)</span>
                                </label>
                                <textarea name="note_livreur"
                                          x-model="clientNote"
                                          rows="2"
                                          placeholder="Indiquez des détails utiles pour retrouver facilement votre porte (ex: portail bleu, deuxième ruelle après la pharmacie...)"
                                          class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:ring-1 focus:ring-red-800 focus:border-red-800 outline-none"></textarea>
                            </div>
                        </div>

                        <!-- Buttons navigation -->
                        <div class="flex items-center justify-between pt-4 border-t border-stone-100">
                            <button type="button" @click="step = 1" class="px-5 py-3 rounded-xl border border-stone-300 text-slate-700 text-xs font-bold hover:bg-stone-100 transition">
                                Retour au panier
                            </button>
                            <button type="button" @click="if (validateStep2()) step = 3"
                                    class="px-8 py-3.5 rounded-xl bg-red-800 hover:bg-red-900 text-white font-bold text-sm shadow transition flex items-center gap-2">
                                <span>Continuer vers le paiement</span>
                                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <!-- STEP 3: Mode de Paiement & Confirmation -->
                    <div x-show="step === 3" class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200 shadow-sm space-y-6">
                        <div class="border-b border-stone-100 pb-4">
                            <h2 class="text-xl font-bold font-serif text-slate-900">Étape 3 : Mode de Paiement & Confirmation</h2>
                            <p class="text-xs text-slate-500 mt-1">Sélectionnez votre moyen de règlement préféré disponible au Togo.</p>
                        </div>

                        <div class="space-y-3">
                            <!-- Option 1: Espèces à la livraison -->
                            <label class="p-4 rounded-2xl border-2 flex items-start gap-3.5 cursor-pointer transition"
                                   :class="paymentMode === 'especes_livraison' ? 'border-red-800 bg-red-50/40' : 'border-stone-200 hover:border-stone-300'">
                                <input type="radio" name="mode_paiement" value="especes_livraison" x-model="paymentMode" class="mt-1 text-red-800 focus:ring-red-800">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-bold text-slate-900 flex items-center gap-2">
                                            <i data-lucide="banknote" class="w-4 h-4 text-emerald-700"></i>
                                            <span>Paiement en espèces à la livraison</span>
                                        </p>
                                        <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 text-[10px] font-bold">Populaire</span>
                                    </div>
                                    <p class="text-xs text-slate-500 mt-1">Réglez directement en espèces au coursier à réception de votre commande à Lomé.</p>
                                </div>
                            </label>

                            <!-- Option 2: T-Money (Togocom) -->
                            <label class="p-4 rounded-2xl border-2 flex items-start gap-3.5 cursor-pointer transition"
                                   :class="paymentMode === 't_money' ? 'border-red-800 bg-red-50/40' : 'border-stone-200 hover:border-stone-300'">
                                <input type="radio" name="mode_paiement" value="t_money" x-model="paymentMode" class="mt-1 text-red-800 focus:ring-red-800">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-bold text-slate-900 flex items-center gap-2">
                                            <i data-lucide="smartphone" class="w-4 h-4 text-amber-600"></i>
                                            <span>T-Money (Togocom)</span>
                                        </p>
                                        <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-900 text-[10px] font-bold">Mobile Money Togo</span>
                                    </div>
                                    <p class="text-xs text-slate-500 mt-1">Paiement instantané sécurisé par compte Togocom T-Money.</p>
                                </div>
                            </label>

                            <!-- Option 3: Flooz (Moov Africa) -->
                            <label class="p-4 rounded-2xl border-2 flex items-start gap-3.5 cursor-pointer transition"
                                   :class="paymentMode === 'flooz' ? 'border-red-800 bg-red-50/40' : 'border-stone-200 hover:border-stone-300'">
                                <input type="radio" name="mode_paiement" value="flooz" x-model="paymentMode" class="mt-1 text-red-800 focus:ring-red-800">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-bold text-slate-900 flex items-center gap-2">
                                            <i data-lucide="zap" class="w-4 h-4 text-blue-600"></i>
                                            <span>Flooz (Moov Africa Togo)</span>
                                        </p>
                                        <span class="px-2 py-0.5 rounded bg-blue-100 text-blue-900 text-[10px] font-bold">Mobile Money Moov</span>
                                    </div>
                                    <p class="text-xs text-slate-500 mt-1">Paiement immédiat via votre portefeuille Moov Money Flooz.</p>
                                </div>
                            </label>
                        </div>

                        <!-- Mobile Money Number Input (if T-Money or Flooz selected) -->
                        <div x-show="paymentMode === 't_money' || paymentMode === 'flooz'" x-cloak class="p-4 rounded-2xl bg-stone-50 border border-stone-200 space-y-2">
                            <label class="block text-xs font-bold text-slate-700">Numéro Mobile Money débité :</label>
                            <input type="text"
                                   name="numero_mobile_money"
                                   placeholder="Ex: 90 00 12 34 ou 98 00 12 34"
                                   class="w-full px-4 py-2.5 rounded-xl border border-stone-300 text-sm focus:border-red-800 outline-none">
                            <p class="text-[11px] text-slate-500">Une notification push ou SMS de confirmation sera déclenchée dès la validation.</p>
                        </div>

                        <!-- Recap Box before submission -->
                        <div class="p-4 rounded-2xl bg-amber-50/80 border border-amber-200 text-xs text-amber-950 space-y-1">
                            <p class="font-bold flex items-center gap-1.5">
                                <i data-lucide="shield-check" class="w-4 h-4 text-amber-700"></i>
                                <span>Garantie Café Paris Lomé :</span>
                            </p>
                            <p>Dès validation, votre commande entre directement en cuisine et génère automatiquement votre facture officielle numérotée RAF-2026-XXXX avec lien de suivi en direct.</p>
                        </div>

                        <!-- Final Buttons -->
                        <div class="flex items-center justify-between pt-4 border-t border-stone-100">
                            <button type="button" @click="step = 2" class="px-5 py-3 rounded-xl border border-stone-300 text-slate-700 text-xs font-bold hover:bg-stone-100 transition">
                                Modifier l'adresse
                            </button>

                            <button type="submit"
                                    class="px-8 py-4 rounded-xl bg-gradient-to-r from-red-800 to-red-900 hover:from-red-700 hover:to-red-800 text-white font-extrabold text-sm shadow-xl shadow-red-900/30 transition flex items-center gap-2">
                                <i data-lucide="check-circle" class="w-5 h-5 text-amber-400"></i>
                                <span>Confirmer et Commander (<span x-text="formatMoney(cartTotal + shippingFee) + ' FCFA'"></span>)</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Live Order Summary Card -->
                <div class="lg:col-span-5 sticky top-24 space-y-4">
                    <div class="bg-white rounded-3xl p-6 sm:p-7 border border-stone-200 shadow-sm space-y-6">
                        <div class="flex items-center justify-between border-b border-stone-100 pb-4">
                            <h3 class="font-bold font-serif text-lg text-slate-900">Récapitulatif de Commande</h3>
                            <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-stone-100 text-slate-700" x-text="cartCount + ' article(s)'"></span>
                        </div>

                        <!-- Order items snapshot -->
                        <div class="space-y-3 max-h-60 overflow-y-auto pr-1">
                            <template x-for="(item, i) in cart" :key="i">
                                <div class="flex items-center justify-between text-xs py-1">
                                    <div class="flex items-center gap-2 truncate pr-2">
                                        <span class="font-bold text-slate-800" x-text="item.qty + 'x'"></span>
                                        <span class="text-slate-700 truncate" x-text="item.name"></span>
                                    </div>
                                    <span class="font-bold text-slate-900 shrink-0" x-text="formatMoney(item.price * item.qty) + ' FCFA'"></span>
                                </div>
                            </template>
                        </div>

                        <!-- Financial lines -->
                        <div class="space-y-2.5 text-xs border-t border-stone-100 pt-4">
                            <div class="flex justify-between text-slate-600">
                                <span>Sous-total articles :</span>
                                <span class="font-bold text-slate-900" x-text="formatMoney(cartTotal) + ' FCFA'"></span>
                            </div>

                            <div class="flex justify-between text-slate-600">
                                <span class="flex items-center gap-1">
                                    <span>Frais de livraison :</span>
                                    <span class="text-[10px] text-slate-400" x-text="'(' + selectedZoneName + ')'"></span>
                                </span>
                                <span class="font-bold text-slate-900" x-text="formatMoney(shippingFee) + ' FCFA'"></span>
                            </div>

                            <div class="flex justify-between text-base font-extrabold text-slate-900 border-t border-stone-200 pt-3">
                                <span>Total net à payer :</span>
                                <span class="text-red-900 text-lg" x-text="formatMoney(cartTotal + shippingFee) + ' FCFA'"></span>
                            </div>
                        </div>

                        <!-- Security & Badges -->
                        <div class="p-4 rounded-2xl bg-stone-50 border border-stone-200 text-[11px] text-slate-500 space-y-2">
                            <div class="flex items-center gap-2">
                                <i data-lucide="clock" class="w-4 h-4 text-amber-500 shrink-0"></i>
                                <span>Livraison express estimée : <strong>30 à 45 minutes</strong></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i data-lucide="file-text" class="w-4 h-4 text-red-700 shrink-0"></i>
                                <span>Facture officielle générée instantanément</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function checkoutPage() {
        return {
            step: 1,
            orderType: 'livraison',
            paymentMode: 'especes_livraison',
            clientNom: '{{ auth()->user()?->name ?? '' }}',
            clientTel: '{{ auth()->user()?->telephone ?? '' }}',
            clientEmail: '{{ auth()->user()?->email ?? '' }}',
            clientAdresse: '',
            clientNote: '',
            selectedZoneId: '{{ $zones->first()?->id ?? 1 }}',
            selectedZoneName: '{{ $zones->first()?->nom_zone ?? "Lomé" }}',
            shippingFee: {{ (float)($zones->first()?->frais_fcfa ?? 1000) }},

            // Default Map coordinates: Lomé City Center (6.1375, 1.2123)
            lat: 6.1375,
            lng: 1.2123,
            leafletMap: null,
            marker: null,

            init() {
                // Preselect zone if present
                this.onZoneChange();
            },

            onZoneChange() {
                if (this.orderType !== 'livraison') {
                    this.shippingFee = 0;
                    return;
                }
                const select = document.querySelector('select[name="zone_livraison_id"]');
                if (select && select.selectedOptions[0]) {
                    const opt = select.selectedOptions[0];
                    this.shippingFee = parseFloat(opt.getAttribute('data-frais')) || 1000;
                    this.selectedZoneName = opt.getAttribute('data-nom') || 'Lomé';
                }
            },

            initMap() {
                this.$nextTick(() => {
                    const mapContainer = document.getElementById('map-picker');
                    if (!mapContainer || this.leafletMap) return;

                    // Initialize OpenStreetMap Leaflet (Centering on Lomé, Togo)
                    this.leafletMap = L.map('map-picker').setView([this.lat, this.lng], 14);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; OpenStreetMap'
                    }).addTo(this.leafletMap);

                    // Custom draggable marker
                    const customIcon = L.divIcon({
                        className: 'custom-pin',
                        html: `<div style="background-color: #991b1b; width: 32px; height: 32px; border-radius: 50%; border: 3px solid white; box-shadow: 0 4px 6px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; color: white;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                               </div>`,
                        iconSize: [32, 32],
                        iconAnchor: [16, 32]
                    });

                    this.marker = L.marker([this.lat, this.lng], {
                        draggable: true,
                        icon: customIcon
                    }).addTo(this.leafletMap);

                    this.marker.bindPopup("<strong>Point de livraison sélectionné</strong><br>Déplacez-moi vers votre position exacte.").openPopup();

                    // When user finishes dragging the marker
                    this.marker.on('dragend', (e) => {
                        const position = this.marker.getLatLng();
                        this.lat = position.lat;
                        this.lng = position.lng;
                    });

                    // Clicking on map also moves marker
                    this.leafletMap.on('click', (e) => {
                        this.marker.setLatLng(e.latlng);
                        this.lat = e.latlng.lat;
                        this.lng = e.latlng.lng;
                    });
                });
            },

            centerLome(latitude, longitude, quartierNom) {
                this.lat = latitude;
                this.lng = longitude;
                if (this.leafletMap && this.marker) {
                    this.leafletMap.setView([latitude, longitude], 15);
                    this.marker.setLatLng([latitude, longitude]);
                    this.marker.bindPopup("<strong>Quartier : " + quartierNom + "</strong>").openPopup();
                }
                if (!this.clientAdresse) {
                    this.clientAdresse = quartierNom + ', Lomé';
                }
            },

            validateStep2() {
                if (!this.clientNom.trim()) {
                    alert('Veuillez renseigner votre nom complet.');
                    return false;
                }
                if (!this.clientTel.trim()) {
                    alert('Veuillez renseigner votre numéro de téléphone.');
                    return false;
                }
                if (this.orderType === 'livraison' && !this.clientAdresse.trim()) {
                    alert('Veuillez renseigner votre adresse de livraison.');
                    return false;
                }
                return true;
            },

            prepareSubmission(event) {
                if (this.cart.length === 0) {
                    event.preventDefault();
                    alert('Votre panier est vide. Veuillez ajouter des plats.');
                    return;
                }

                if (!this.validateStep2()) {
                    event.preventDefault();
                    return;
                }

                // If all good, clear cart in localStorage after brief delay so client has a clean slate
                setTimeout(() => {
                    localStorage.removeItem('cafeparis_cart');
                }, 500);
            }
        };
    }
</script>
@endpush
