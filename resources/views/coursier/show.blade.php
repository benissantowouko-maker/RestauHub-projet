@extends('layouts.coursier')

@section('title', 'Détail Mission #' . $commande->code_commande . ' — Café Paris Livraisons')

@push('styles')
<style>
    #delivery-map {
        height: 260px;
        width: 100%;
        border-radius: 1rem;
        z-index: 10;
    }
</style>
@endpush

@section('content')
<div class="space-y-4">
    <!-- Back Header -->
    <div class="flex items-center justify-between">
        <a href="{{ route('coursier.dashboard') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-400 hover:text-white transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Retour aux missions</span>
        </a>
        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $commande->statut_badge_class }}">
            {{ $commande->statut_label }}
        </span>
    </div>

    <!-- Client Header Card -->
    <div class="p-5 rounded-2xl bg-slate-800 border border-slate-700 shadow-md space-y-4">
        <div class="flex items-start justify-between">
            <div>
                <span class="text-[10px] uppercase font-bold text-slate-400">Client destinataire</span>
                <h2 class="text-xl font-bold text-white mt-0.5">{{ $commande->nom_client }}</h2>
                <p class="text-xs text-indigo-400 font-medium">{{ $commande->quartier ?? 'Lomé' }}</p>
            </div>

            <!-- Direct phone call button -->
            <a href="tel:{{ $commande->telephone_client }}"
               class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-lg flex items-center gap-2">
                <i data-lucide="phone-call" class="w-4 h-4"></i>
                <span>Appeler</span>
            </a>
        </div>

        <!-- Address details -->
        <div class="p-3.5 rounded-xl bg-slate-900 border border-slate-750 text-xs space-y-2">
            <p class="text-slate-200">
                <strong class="text-white">Adresse :</strong> {{ $commande->adresse_livraison }}
            </p>

            @if($commande->note_livreur)
                <div class="p-2.5 rounded-lg bg-amber-950 border border-amber-600/60 text-amber-300 font-bold text-xs space-y-0.5">
                    <p class="text-[10px] uppercase tracking-wider text-amber-400">📌 NOTE DU CLIENT POUR LE LIVREUR :</p>
                    <p class="text-sm font-normal">{{ $commande->note_livreur }}</p>
                </div>
            @endif
        </div>

        <!-- Leaflet GPS Map with Draggable marker or pinpoint location -->
        <div class="space-y-2">
            <div id="delivery-map" class="border border-slate-700"></div>

            <div class="flex items-center justify-between text-xs pt-1">
                <a href="https://www.google.com/maps/dir/?api=1&destination={{ $commande->latitude ?? 6.1375 }},{{ $commande->longitude ?? 1.2123 }}"
                   target="_blank"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-600/30 hover:bg-indigo-600/50 text-indigo-300 border border-indigo-500/50 text-xs font-bold transition">
                    <i data-lucide="navigation" class="w-3.5 h-3.5"></i>
                    <span>Ouvrir l'itinéraire GPS</span>
                </a>
                <span class="text-slate-400 text-[11px]">Coordonnées fixées par le client</span>
            </div>
        </div>
    </div>

    <!-- Items breakdown -->
    <div class="p-5 rounded-2xl bg-slate-800 border border-slate-700 space-y-3">
        <h3 class="text-xs font-bold uppercase text-slate-400 tracking-wider">Contenu du sac isotherme :</h3>
        <div class="space-y-2 text-xs divide-y divide-slate-750">
            @foreach($commande->lignes as $ligne)
                <div class="flex items-center justify-between pt-2">
                    <div class="flex items-center gap-2">
                        <span class="w-5 h-5 rounded bg-slate-700 text-white font-bold flex items-center justify-center text-[11px]">{{ $ligne->quantite }}x</span>
                        <span class="text-slate-200 font-medium">{{ $ligne->nom_produit }}</span>
                    </div>
                    <span class="text-slate-400 font-mono">{{ $ligne->sous_total_formate }}</span>
                </div>
            @endforeach
        </div>

        <!-- Payment Reminder Alert -->
        <div class="pt-3 border-t border-slate-700">
            @if($commande->mode_paiement === 'especes_livraison')
                <div class="p-3.5 rounded-xl bg-amber-950/90 border border-amber-500/80 text-amber-200 text-xs flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold uppercase block text-amber-400">À ENCAISSER EN ESPÈCES :</span>
                        <strong class="text-base text-white">{{ $commande->montant_total_formate }}</strong>
                    </div>
                    <i data-lucide="banknote" class="w-6 h-6 text-amber-400"></i>
                </div>
            @else
                <div class="p-3 rounded-xl bg-emerald-950/80 border border-emerald-500/50 text-emerald-300 text-xs flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-bold uppercase block text-emerald-400">RÈGLEMENT :</span>
                        <span class="font-bold">Déjà payé en ligne ({{ strtoupper($commande->mode_paiement) }})</span>
                    </div>
                    <i data-lucide="check" class="w-5 h-5 text-emerald-400"></i>
                </div>
            @endif
        </div>
    </div>

    <!-- Mission Actions -->
    <div class="pt-2 space-y-2">
        @if($commande->statut === 'prete')
            <form method="POST" action="{{ route('coursier.start', $commande->id) }}">
                @csrf
                <button type="submit" class="w-full py-4 rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-sm shadow-xl flex items-center justify-center gap-2">
                    <i data-lucide="bike" class="w-5 h-5"></i>
                    <span>Prendre le colis & Partir en livraison</span>
                </button>
            </form>
        @elseif($commande->statut === 'en_livraison')
            <form method="POST" action="{{ route('coursier.complete', $commande->id) }}"
                  onsubmit="return confirm('Confirmez-vous que le client a bien reçu sa commande ?')">
                @csrf
                <button type="submit" class="w-full py-4 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-black text-sm shadow-xl flex items-center justify-center gap-2">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                    <span>Confirmer la remise au client (Livrée)</span>
                </button>
            </form>
        @else
            <div class="p-3 rounded-xl bg-slate-800 text-center text-xs text-slate-400">
                Commande au statut : <strong class="text-white">{{ $commande->statut_label }}</strong>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const mapEl = document.getElementById('delivery-map');
        if (mapEl && window.L) {
            const lat = {{ (float)($commande->latitude ?? 6.1375) }};
            const lng = {{ (float)($commande->longitude ?? 1.2123) }};

            const map = L.map('delivery-map').setView([lat, lng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            const icon = L.divIcon({
                className: 'custom-pin',
                html: `<div style="background-color: #4f46e5; width: 32px; height: 32px; border-radius: 50%; border: 3px solid white; box-shadow: 0 4px 6px rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; color: white;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                       </div>`,
                iconSize: [32, 32],
                iconAnchor: [16, 32]
            });

            L.marker([lat, lng], { icon: icon }).addTo(map)
                .bindPopup("<strong>Destination livraison</strong><br>{{ $commande->nom_client }}<br>{{ $commande->adresse_livraison }}").openPopup();
        }
    });
</script>
@endpush
