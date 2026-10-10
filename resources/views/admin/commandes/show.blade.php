@extends('layouts.admin')

@section('title', 'Détail Commande #' . $commande->code_commande)
@section('page_title', 'Commande #' . $commande->code_commande)

@push('styles')
<style>
    #admin-order-map {
        height: 280px;
        width: 100%;
        border-radius: 1rem;
        z-index: 10;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-900 p-6 rounded-3xl border border-slate-800">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.commandes.index') }}" class="text-xs font-bold text-slate-400 hover:text-white flex items-center gap-1">
                    ← Retour à la liste
                </a>
                <span class="text-slate-600">•</span>
                <span class="text-xs text-slate-400">Reçue le {{ $commande->created_at->format('d/m/Y à H:i') }}</span>
            </div>
            <h2 class="text-2xl font-bold text-white mt-1">Commande #{{ $commande->code_commande }}</h2>
        </div>

        <div class="flex items-center gap-3">
            @if($commande->facture)
                <a href="{{ route('facture.show', $commande->facture->numero_facture) }}" target="_blank"
                   class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-amber-400 font-bold text-xs flex items-center gap-1.5 border border-slate-700 transition">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                    <span>Facture {{ $commande->facture->numero_facture }}</span>
                </a>
            @endif

            <a href="{{ route('suivi.commande', $commande->tracking_token) }}" target="_blank"
               class="px-4 py-2.5 rounded-xl bg-red-800 hover:bg-red-700 text-white font-bold text-xs flex items-center gap-1.5 shadow transition">
                <i data-lucide="external-link" class="w-4 h-4"></i>
                <span>Vue Suivi Client</span>
            </a>
        </div>
    </div>

    <!-- Status Progression Controls -->
    <div class="bg-slate-900 p-6 rounded-3xl border border-slate-800 space-y-4">
        <h3 class="text-xs font-bold uppercase text-slate-400 tracking-wider">Avancement du Flux de Traitement :</h3>

        <div class="flex flex-wrap items-center gap-2">
            <!-- 1. En attente -->
            <form method="POST" action="{{ route('admin.commandes.update_status', $commande->id) }}">
                @csrf
                <input type="hidden" name="statut" value="en_attente">
                <button type="submit" class="px-3.5 py-2 rounded-xl text-xs font-bold transition border {{ $commande->statut === 'en_attente' ? 'bg-amber-500 text-slate-950 border-amber-400 ring-2 ring-amber-400/30' : 'bg-slate-800 text-slate-400 border-slate-700 hover:text-white' }}">
                    1. En attente
                </button>
            </form>

            <!-- 2. Confirmer -->
            <form method="POST" action="{{ route('admin.commandes.update_status', $commande->id) }}">
                @csrf
                <input type="hidden" name="statut" value="confirmee">
                <button type="submit" class="px-3.5 py-2 rounded-xl text-xs font-bold transition border {{ $commande->statut === 'confirmee' ? 'bg-blue-600 text-white border-blue-500 ring-2 ring-blue-500/30' : 'bg-slate-800 text-slate-400 border-slate-700 hover:text-white' }}">
                    2. Confirmer (Cuisine)
                </button>
            </form>

            <!-- 3. En préparation -->
            <form method="POST" action="{{ route('admin.commandes.update_status', $commande->id) }}">
                @csrf
                <input type="hidden" name="statut" value="en_preparation">
                <button type="submit" class="px-3.5 py-2 rounded-xl text-xs font-bold transition border {{ $commande->statut === 'en_preparation' ? 'bg-orange-600 text-white border-orange-500 ring-2 ring-orange-500/30' : 'bg-slate-800 text-slate-400 border-slate-700 hover:text-white' }}">
                    3. En préparation
                </button>
            </form>

            <!-- 4. Prête -->
            <form method="POST" action="{{ route('admin.commandes.update_status', $commande->id) }}">
                @csrf
                <input type="hidden" name="statut" value="prete">
                <button type="submit" class="px-3.5 py-2 rounded-xl text-xs font-bold transition border {{ $commande->statut === 'prete' ? 'bg-purple-600 text-white border-purple-500 ring-2 ring-purple-500/30' : 'bg-slate-800 text-slate-400 border-slate-700 hover:text-white' }}">
                    4. Prête (Appel Coursier)
                </button>
            </form>

            <!-- 5. En livraison -->
            <form method="POST" action="{{ route('admin.commandes.update_status', $commande->id) }}">
                @csrf
                <input type="hidden" name="statut" value="en_livraison">
                <button type="submit" class="px-3.5 py-2 rounded-xl text-xs font-bold transition border {{ $commande->statut === 'en_livraison' ? 'bg-indigo-600 text-white border-indigo-500 ring-2 ring-indigo-500/30' : 'bg-slate-800 text-slate-400 border-slate-700 hover:text-white' }}">
                    5. En livraison
                </button>
            </form>

            <!-- 6. Livrée -->
            <form method="POST" action="{{ route('admin.commandes.update_status', $commande->id) }}">
                @csrf
                <input type="hidden" name="statut" value="livree">
                <button type="submit" class="px-3.5 py-2 rounded-xl text-xs font-bold transition border {{ $commande->statut === 'livree' ? 'bg-emerald-600 text-white border-emerald-500 ring-2 ring-emerald-500/30' : 'bg-slate-800 text-slate-400 border-slate-700 hover:text-white' }}">
                    6. Livrée & Terminée
                </button>
            </form>

            <!-- Annuler -->
            <form method="POST" action="{{ route('admin.commandes.update_status', $commande->id) }}"
                  onsubmit="return confirm('Confirmez-vous l’annulation de cette commande ?')">
                @csrf
                <input type="hidden" name="statut" value="annulee">
                <button type="submit" class="px-3.5 py-2 rounded-xl text-xs font-bold transition border {{ $commande->statut === 'annulee' ? 'bg-rose-600 text-white border-rose-500' : 'bg-slate-800 text-rose-400 border-slate-700 hover:bg-rose-950/40' }}">
                    Annuler la commande
                </button>
            </form>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- Left: Line items -->
        <div class="lg:col-span-7 bg-slate-900 rounded-3xl border border-slate-800 p-6 space-y-4">
            <h3 class="font-bold text-white text-base border-b border-slate-800 pb-3">Articles commandés</h3>

            <div class="divide-y divide-slate-800">
                @foreach($commande->lignes as $ligne)
                    <div class="py-3 flex items-start justify-between text-xs gap-3">
                        <div class="flex items-start gap-3">
                            <span class="w-6 h-6 rounded bg-slate-800 text-amber-400 font-bold flex items-center justify-center shrink-0">
                                {{ $ligne->quantite }}x
                            </span>
                            <div>
                                <p class="font-bold text-white text-sm">{{ $ligne->nom_produit }}</p>
                                <p class="text-slate-400">{{ $ligne->prix_unitaire_formate }} l'unité</p>
                                @if($ligne->instructions_speciales)
                                    <p class="text-[11px] text-amber-400 italic mt-0.5">Note : {{ $ligne->instructions_speciales }}</p>
                                @endif
                            </div>
                        </div>
                        <span class="font-black text-amber-400 text-sm">{{ $ligne->sous_total_formate }}</span>
                    </div>
                @endforeach
            </div>

            <!-- Financial Recap -->
            <div class="pt-4 border-t border-slate-800 space-y-2 text-xs">
                <div class="flex justify-between text-slate-400">
                    <span>Sous-total articles :</span>
                    <span class="text-white font-bold">{{ $commande->sous_total_formate }}</span>
                </div>
                <div class="flex justify-between text-slate-400">
                    <span>Frais de livraison :</span>
                    <span class="text-white font-bold">{{ $commande->frais_livraison_formate }}</span>
                </div>
                <div class="flex justify-between text-base font-black text-white pt-2 border-t border-slate-800">
                    <span>Total de la commande :</span>
                    <span class="text-amber-400 text-lg">{{ $commande->montant_total_formate }}</span>
                </div>
            </div>

            <!-- Payment status banner -->
            <div class="p-4 rounded-2xl bg-slate-800/80 border border-slate-750 flex items-center justify-between text-xs">
                <div>
                    <span class="text-slate-400 block text-[11px]">Mode de règlement choisi :</span>
                    <strong class="text-white font-semibold">{{ $commande->mode_paiement_label }}</strong>
                </div>
                <span class="px-3 py-1 rounded-full font-bold {{ $commande->statut_paiement === 'paye' ? 'bg-emerald-950 text-emerald-400 border border-emerald-700' : 'bg-amber-950 text-amber-400 border border-amber-700' }}">
                    {{ strtoupper($commande->statut_paiement) }}
                </span>
            </div>
        </div>

        <!-- Right: Client, Courier & Map -->
        <div class="lg:col-span-5 space-y-6">
            <!-- Client & Notes -->
            <div class="bg-slate-900 rounded-3xl border border-slate-800 p-6 space-y-4">
                <h3 class="font-bold text-white text-base border-b border-slate-800 pb-3">Informations de livraison</h3>

                <div class="space-y-2 text-xs text-slate-300">
                    <p><strong class="text-white">Client :</strong> {{ $commande->nom_client }}</p>
                    <p><strong class="text-white">Téléphone :</strong> <a href="tel:{{ $commande->telephone_client }}" class="text-amber-400 hover:underline">{{ $commande->telephone_client }}</a></p>
                    @if($commande->email_client)
                        <p><strong class="text-white">Email :</strong> {{ $commande->email_client }}</p>
                    @endif
                    <p><strong class="text-white">Quartier :</strong> {{ $commande->quartier ?? 'Lomé' }}</p>
                    <p><strong class="text-white">Adresse :</strong> {{ $commande->adresse_livraison }}</p>

                    @if($commande->note_livreur)
                        <div class="p-3 rounded-xl bg-amber-950/70 border border-amber-600/60 text-amber-300 font-medium text-xs mt-3">
                            <strong>Note du client pour le livreur :</strong><br>
                            {{ $commande->note_livreur }}
                        </div>
                    @endif
                </div>

                <!-- Courier Assignment -->
                <div class="pt-4 border-t border-slate-800 space-y-2">
                    <label class="block text-xs font-bold text-slate-300">Coursier assigné à cette commande :</label>
                    <form method="POST" action="{{ route('admin.commandes.assign_driver', $commande->id) }}" class="flex gap-2">
                        @csrf
                        <select name="livreur_id" class="flex-1 px-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-xs text-white outline-none">
                            <option value="">-- Sélectionner un coursier --</option>
                            @foreach($livreurs as $l)
                                <option value="{{ $l->id }}" {{ $commande->livreur_id === $l->id ? 'selected' : '' }}>
                                    {{ $l->name }} ({{ $l->telephone }})
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow transition">
                            Assigner
                        </button>
                    </form>
                </div>
            </div>

            <!-- Leaflet Map Location -->
            @if($commande->latitude && $commande->longitude)
                <div class="bg-slate-900 rounded-3xl border border-slate-800 p-6 space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="font-bold text-white text-xs uppercase tracking-wider">Position exacte sur la carte :</h3>
                        <span class="text-[11px] text-slate-400 font-mono">{{ $commande->latitude }}, {{ $commande->longitude }}</span>
                    </div>

                    <div id="admin-order-map" class="border border-slate-700"></div>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const mapEl = document.getElementById('admin-order-map');
        if (mapEl && window.L) {
            const lat = {{ (float)($commande->latitude ?? 6.1375) }};
            const lng = {{ (float)($commande->longitude ?? 1.2123) }};

            const map = L.map('admin-order-map').setView([lat, lng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            const icon = L.divIcon({
                className: 'custom-pin',
                html: `<div style="background-color: #dc2626; width: 30px; height: 30px; border-radius: 50%; border: 3px solid white; box-shadow: 0 4px 6px rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center; color: white;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="3"></circle></svg>
                       </div>`,
                iconSize: [30, 30],
                iconAnchor: [15, 30]
            });

            L.marker([lat, lng], { icon: icon }).addTo(map)
                .bindPopup("<strong>Livraison Café Paris</strong><br>{{ $commande->nom_client }}<br>{{ $commande->adresse_livraison }}").openPopup();
        }
    });
</script>
@endpush
