@extends('layouts.app')

@section('title', 'Suivi de Commande #' . $commande->code_commande . ' — Café Paris Lomé')

@push('styles')
<style>
    #tracking-map {
        height: 220px;
        width: 100%;
        border-radius: 1rem;
        z-index: 10;
    }
</style>
@endpush

@section('content')
<div class="py-12 bg-[#FAF7F2]" x-data="orderTracker('{{ $commande->tracking_token }}', '{{ $commande->statut }}', {{ $commande->step_progress }})" x-init="startLivePolling()">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

        <!-- Top Status Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200 shadow-sm space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-stone-100 pb-5">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold uppercase text-red-800 bg-red-50 px-2.5 py-1 rounded-md">Suivi en direct</span>
                        <span class="text-xs text-slate-500">Commande passée il y a {{ $commande->created_at->diffForHumans() }}</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-serif font-bold text-slate-900 mt-1">
                        Commande #{{ $commande->code_commande }}
                    </h1>
                </div>

                <div class="flex items-center gap-3">
                    @if($commande->facture)
                        <a href="{{ route('facture.show', $commande->facture->numero_facture) }}" target="_blank"
                           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-stone-100 hover:bg-stone-200 text-slate-700 text-xs font-bold transition">
                            <i data-lucide="file-text" class="w-4 h-4 text-red-800"></i>
                            <span>Facture {{ $commande->facture->numero_facture }}</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Stepper Progress Bar -->
            <div class="py-4">
                <div class="relative">
                    <!-- Background bar -->
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 h-2 bg-stone-200 w-full rounded-full -z-0"></div>
                    <!-- Active filled bar -->
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 h-2 bg-gradient-to-r from-red-800 to-amber-500 rounded-full transition-all duration-700 -z-0"
                         :style="'width: ' + ((currentStep - 1) * 20) + '%'"></div>

                    <!-- 6 Steps: 1=En attente, 2=Confirmée, 3=En préparation, 4=Prête, 5=En livraison, 6=Livrée -->
                    <div class="flex items-center justify-between relative z-10">
                        <!-- Step 1 -->
                        <div class="flex flex-col items-center gap-2">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold shadow-md transition"
                                 :class="currentStep >= 1 ? 'bg-red-800 text-white ring-4 ring-red-100' : 'bg-white text-slate-400 border border-stone-300'">
                                <i data-lucide="check" class="w-4 h-4" x-show="currentStep > 1"></i>
                                <span x-show="currentStep <= 1">1</span>
                            </div>
                            <span class="text-[11px] font-bold text-center" :class="currentStep >= 1 ? 'text-red-950' : 'text-slate-400'">En attente</span>
                        </div>

                        <!-- Step 2 -->
                        <div class="flex flex-col items-center gap-2">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold shadow-md transition"
                                 :class="currentStep >= 2 ? 'bg-red-800 text-white ring-4 ring-red-100' : 'bg-white text-slate-400 border border-stone-300'">
                                <i data-lucide="check" class="w-4 h-4" x-show="currentStep > 2"></i>
                                <span x-show="currentStep <= 2">2</span>
                            </div>
                            <span class="text-[11px] font-bold text-center" :class="currentStep >= 2 ? 'text-red-950' : 'text-slate-400'">Confirmée</span>
                        </div>

                        <!-- Step 3 -->
                        <div class="flex flex-col items-center gap-2">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold shadow-md transition"
                                 :class="currentStep >= 3 ? 'bg-red-800 text-white ring-4 ring-red-100' : 'bg-white text-slate-400 border border-stone-300'">
                                <i data-lucide="check" class="w-4 h-4" x-show="currentStep > 3"></i>
                                <span x-show="currentStep <= 3">3</span>
                            </div>
                            <span class="text-[11px] font-bold text-center" :class="currentStep >= 3 ? 'text-red-950' : 'text-slate-400'">En cuisine</span>
                        </div>

                        <!-- Step 4 -->
                        <div class="flex flex-col items-center gap-2">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold shadow-md transition"
                                 :class="currentStep >= 4 ? 'bg-red-800 text-white ring-4 ring-red-100' : 'bg-white text-slate-400 border border-stone-300'">
                                <i data-lucide="check" class="w-4 h-4" x-show="currentStep > 4"></i>
                                <span x-show="currentStep <= 4">4</span>
                            </div>
                            <span class="text-[11px] font-bold text-center" :class="currentStep >= 4 ? 'text-red-950' : 'text-slate-400'">Prête</span>
                        </div>

                        <!-- Step 5 -->
                        <div class="flex flex-col items-center gap-2">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold shadow-md transition"
                                 :class="currentStep >= 5 ? 'bg-red-800 text-white ring-4 ring-red-100' : 'bg-white text-slate-400 border border-stone-300'">
                                <i data-lucide="check" class="w-4 h-4" x-show="currentStep > 5"></i>
                                <span x-show="currentStep <= 5">5</span>
                            </div>
                            <span class="text-[11px] font-bold text-center" :class="currentStep >= 5 ? 'text-red-950' : 'text-slate-400'">En livraison</span>
                        </div>

                        <!-- Step 6 -->
                        <div class="flex flex-col items-center gap-2">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold shadow-md transition"
                                 :class="currentStep >= 6 ? 'bg-emerald-600 text-white ring-4 ring-emerald-100' : 'bg-white text-slate-400 border border-stone-300'">
                                <i data-lucide="check" class="w-4 h-4" x-show="currentStep >= 6"></i>
                                <span x-show="currentStep < 6">6</span>
                            </div>
                            <span class="text-[11px] font-bold text-center" :class="currentStep >= 6 ? 'text-emerald-800' : 'text-slate-400'">Livrée</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dynamic Status Banner Message -->
            <div class="p-4 rounded-2xl flex items-center gap-3.5"
                 :class="{
                     'bg-amber-50 border border-amber-200 text-amber-900': currentStatut === 'en_attente',
                     'bg-blue-50 border border-blue-200 text-blue-900': currentStatut === 'confirmee',
                     'bg-orange-50 border border-orange-200 text-orange-900': currentStatut === 'en_preparation',
                     'bg-purple-50 border border-purple-200 text-purple-900': currentStatut === 'prete',
                     'bg-indigo-50 border border-indigo-200 text-indigo-900': currentStatut === 'en_livraison',
                     'bg-emerald-50 border border-emerald-200 text-emerald-900': currentStatut === 'livree'
                 }">
                <div class="w-10 h-10 rounded-xl bg-white/80 flex items-center justify-center shrink-0 shadow-sm">
                    <i data-lucide="sparkles" class="w-5 h-5 text-amber-600"></i>
                </div>
                <div class="flex-1">
                    <h4 class="text-xs font-bold uppercase tracking-wider">État actuel : <span x-text="currentStatutLabel"></span></h4>
                    <p class="text-xs mt-0.5" x-text="getStatusMessage()"></p>
                </div>
                <span class="inline-flex items-center gap-1.5 text-[11px] text-slate-500 shrink-0">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                    <span>Actualisation auto</span>
                </span>
            </div>

            <!-- Courier details card (if assigned) -->
            <div x-show="livreurInfo" x-cloak class="p-4 rounded-2xl bg-indigo-50/70 border border-indigo-200 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold text-sm">
                        <i data-lucide="bike" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase text-indigo-700">Votre Coursier Café Paris :</p>
                        <p class="text-sm font-bold text-slate-900" x-text="livreurInfo ? livreurInfo.nom : ''"></p>
                    </div>
                </div>

                <template x-if="livreurInfo && livreurInfo.telephone">
                    <a :href="'tel:' + livreurInfo.telephone" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow transition">
                        <i data-lucide="phone" class="w-3.5 h-3.5"></i>
                        <span>Appeler le coursier</span>
                    </a>
                </template>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Articles Commandés -->
            <div class="bg-white rounded-3xl p-6 border border-stone-200 shadow-sm space-y-4">
                <h3 class="font-bold font-serif text-base text-slate-900 border-b border-stone-100 pb-3 flex items-center gap-2">
                    <i data-lucide="utensils" class="w-4 h-4 text-red-800"></i>
                    <span>Articles de votre commande</span>
                </h3>

                <div class="space-y-3">
                    @foreach($commande->lignes as $ligne)
                        <div class="flex items-center justify-between text-xs py-1.5 border-b border-stone-50">
                            <div>
                                <p class="font-bold text-slate-900">{{ $ligne->quantite }}x {{ $ligne->nom_produit }}</p>
                                @if($ligne->instructions_speciales)
                                    <p class="text-[11px] text-slate-500 italic">Note : {{ $ligne->instructions_speciales }}</p>
                                @endif
                            </div>
                            <span class="font-bold text-slate-900">{{ $ligne->sous_total_formate }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="pt-3 border-t border-stone-200 space-y-1.5 text-xs">
                    <div class="flex justify-between text-slate-600">
                        <span>Sous-total articles :</span>
                        <span>{{ $commande->sous_total_formate }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Frais de livraison ({{ $commande->quartier ?? 'Lomé' }}) :</span>
                        <span>{{ $commande->frais_livraison_formate }}</span>
                    </div>
                    <div class="flex justify-between font-black text-sm text-slate-900 pt-2 border-t border-stone-200">
                        <span>Total TTC :</span>
                        <span class="text-red-900">{{ $commande->montant_total_formate }}</span>
                    </div>
                    <div class="flex justify-between text-slate-500 pt-1 text-[11px]">
                        <span>Mode de paiement :</span>
                        <span class="font-semibold text-slate-700">{{ $commande->mode_paiement_label }}</span>
                    </div>
                </div>
            </div>

            <!-- Adresse & Carte -->
            <div class="bg-white rounded-3xl p-6 border border-stone-200 shadow-sm space-y-4">
                <h3 class="font-bold font-serif text-base text-slate-900 border-b border-stone-100 pb-3 flex items-center gap-2">
                    <i data-lucide="map-pin" class="w-4 h-4 text-red-800"></i>
                    <span>Lieu de livraison à Lomé</span>
                </h3>

                <div class="text-xs space-y-1 text-slate-700">
                    <p><strong>Destinataire :</strong> {{ $commande->nom_client }} ({{ $commande->telephone_client }})</p>
                    <p><strong>Adresse :</strong> {{ $commande->adresse_livraison ?? 'Lomé, Togo' }}</p>
                    @if($commande->note_livreur)
                        <div class="p-2.5 rounded-xl bg-amber-50 text-amber-900 border border-amber-200 text-xs mt-2">
                            <strong>Note pour le coursier :</strong> {{ $commande->note_livreur }}
                        </div>
                    @endif
                </div>

                <!-- Leaflet Mini Map -->
                @if($commande->latitude && $commande->longitude)
                    <div id="tracking-map" class="border border-stone-200 mt-2"></div>
                @endif
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-red-800 transition">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>Retour à la page d'accueil</span>
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function orderTracker(token, initialStatut, initialStep) {
        return {
            token: token,
            currentStatut: initialStatut,
            currentStep: initialStep,
            currentStatutLabel: '{{ $commande->statut_label }}',
            livreurInfo: @json($commande->livreur ? ['nom' => $commande->livreur->name, 'telephone' => $commande->livreur->telephone] : null),

            getStatusMessage() {
                switch (this.currentStatut) {
                    case 'en_attente':
                        return 'Votre commande a été transmise au restaurant Café Paris et va être validée sous peu.';
                    case 'confirmee':
                        return 'La commande est validée ! Notre brigade en cuisine commence la préparation de vos plats.';
                    case 'en_preparation':
                        return 'Vos plats sont en cours de cuisson au feu de bois et d’assemblage par le chef.';
                    case 'prete':
                        return 'Vos plats sont emballés sous cloche isotherme et attendent le départ du coursier.';
                    case 'en_livraison':
                        return 'Le coursier est en route avec votre commande vers votre adresse à Lomé !';
                    case 'livree':
                        return 'Commande livrée ! Café Paris Lomé vous souhaite un excellent appétit !';
                    default:
                        return 'Suivi en cours.';
                }
            },

            startLivePolling() {
                // Initialize map if element exists
                const mapEl = document.getElementById('tracking-map');
                if (mapEl && window.L) {
                    const lat = {{ (float)($commande->latitude ?? 6.1375) }};
                    const lng = {{ (float)($commande->longitude ?? 1.2123) }};
                    const map = L.map('tracking-map').setView([lat, lng], 15);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; OpenStreetMap'
                    }).addTo(map);

                    const icon = L.divIcon({
                        className: 'custom-pin',
                        html: `<div style="background-color: #991b1b; width: 28px; height: 28px; border-radius: 50%; border: 2px solid white; box-shadow: 0 3px 6px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; color: white;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="3"></circle></svg>
                               </div>`,
                        iconSize: [28, 28],
                        iconAnchor: [14, 28]
                    });

                    L.marker([lat, lng], { icon: icon }).addTo(map)
                        .bindPopup("<strong>Lieu de livraison</strong><br>{{ $commande->nom_client }}").openPopup();
                }

                // Poll every 5s for live updates
                setInterval(async () => {
                    try {
                        const res = await fetch('{{ url('api/suivi') }}/' + this.token + '/statut');
                        if (res.ok) {
                            const data = await res.json();
                            this.currentStatut = data.statut;
                            this.currentStep = data.step;
                            this.currentStatutLabel = data.statut_label;
                            if (data.livreur) {
                                this.livreurInfo = data.livreur;
                            }
                        }
                    } catch (e) {}
                }, 5000);
            }
        };
    }
</script>
@endpush
