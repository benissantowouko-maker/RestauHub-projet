@extends('layouts.app')

@section('title', 'Confirmation de Réservation #' . $reservation->code_reservation . ' — Café Paris Lomé')

@section('content')
<div class="py-16 bg-[#FAF7F2]">
    <div class="max-w-xl mx-auto px-4 sm:px-6">
        <div class="bg-white rounded-3xl p-8 sm:p-10 border border-stone-200 shadow-sm text-center space-y-6">
            <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center mx-auto shadow-sm">
                <i data-lucide="check-circle-2" class="w-8 h-8"></i>
            </div>

            <div>
                <span class="inline-block px-3 py-1 rounded bg-amber-100 text-amber-900 text-xs font-bold font-mono">
                    {{ $reservation->code_reservation }}
                </span>
                <h1 class="text-2xl sm:text-3xl font-serif font-bold text-slate-900 mt-2">
                    Réservation Confirmée !
                </h1>
                <p class="text-xs text-slate-500 mt-1">Nous nous réjouissons de vous accueillir au Café Paris Lomé.</p>
            </div>

            <!-- Booking details card -->
            <div class="p-6 rounded-2xl bg-stone-50 border border-stone-200 text-left space-y-3 text-xs">
                <div class="flex justify-between border-b border-stone-200 pb-2">
                    <span class="text-slate-500">Nom du réservataire :</span>
                    <strong class="text-slate-900">{{ $reservation->nom }}</strong>
                </div>
                <div class="flex justify-between border-b border-stone-200 pb-2">
                    <span class="text-slate-500">Téléphone de contact :</span>
                    <strong class="text-slate-900">{{ $reservation->telephone }}</strong>
                </div>
                <div class="flex justify-between border-b border-stone-200 pb-2">
                    <span class="text-slate-500">Date prévue :</span>
                    <strong class="text-slate-900">{{ $reservation->date_reservation->translatedFormat('l d F Y') }}</strong>
                </div>
                <div class="flex justify-between border-b border-stone-200 pb-2">
                    <span class="text-slate-500">Heure :</span>
                    <strong class="text-slate-900">{{ $reservation->heure_reservation }}</strong>
                </div>
                <div class="flex justify-between border-b border-stone-200 pb-2">
                    <span class="text-slate-500">Nombre de personnes :</span>
                    <strong class="text-slate-900">{{ $reservation->nombre_personnes }} personnes</strong>
                </div>
                @if($reservation->occasion)
                    <div class="flex justify-between border-b border-stone-200 pb-2">
                        <span class="text-slate-500">Occasion :</span>
                        <strong class="text-red-900 uppercase font-bold">{{ $reservation->occasion }}</strong>
                    </div>
                @endif
                <div class="flex justify-between pt-1">
                    <span class="text-slate-500">Statut réservation :</span>
                    <span class="px-2 py-0.5 rounded font-bold {{ $reservation->statut_badge_class }}">
                        {{ $reservation->statut_label }}
                    </span>
                </div>
            </div>

            <div class="p-4 rounded-xl bg-amber-50 text-amber-900 border border-amber-200 text-xs text-left space-y-1">
                <p class="font-bold flex items-center gap-1.5">
                    <i data-lucide="info" class="w-4 h-4 text-amber-600"></i>
                    <span>Informations pratiques :</span>
                </p>
                <p>Votre table est réservée pendant 20 minutes après l'heure convenue. Pour toute modification ou retard, contactez notre maître d'hôtel au <a href="tel:+22890001234" class="font-bold underline">+228 90 00 12 34</a>.</p>
            </div>

            <div class="pt-4 flex flex-col sm:flex-row gap-3">
                <a href="{{ route('menu.index') }}" class="flex-1 py-3 px-4 rounded-xl bg-red-800 hover:bg-red-900 text-white font-bold text-xs shadow transition">
                    Découvrir nos menus à l'avance
                </a>
                <a href="{{ route('home') }}" class="py-3 px-4 rounded-xl border border-stone-300 text-slate-700 font-bold text-xs hover:bg-stone-100 transition">
                    Retour à l'accueil
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
