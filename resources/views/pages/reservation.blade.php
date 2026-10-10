@extends('layouts.app')

@section('title', 'Réserver une Table — Café Paris Lomé')

@section('content')
<div class="py-14 bg-[#FAF7F2]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-xl mx-auto mb-10 space-y-3">
            <span class="inline-block px-3 py-1 rounded-full bg-red-100 text-red-900 text-xs font-bold uppercase tracking-wider">
                Réservation de Table
            </span>
            <h1 class="text-3xl sm:text-4xl font-serif font-bold text-slate-900">
                Partagez un Moment d'Exception
            </h1>
            <p class="text-sm text-slate-600">
                Pour vos déjeuners professionnels, dîners romantiques ou fêtes de famille, réservez votre table en salle climatisée ou sur notre terrasse à Lomé.
            </p>
        </div>

        <div class="bg-white rounded-3xl p-6 sm:p-10 border border-stone-200 shadow-sm">
            <form method="POST" action="{{ route('reservation.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nom et Prénom *</label>
                        <input type="text"
                               name="nom"
                               value="{{ old('nom', auth()->user()?->name) }}"
                               required
                               placeholder="Ex: Edwige Foli"
                               class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm focus:border-red-800 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Téléphone direct (+228) *</label>
                        <input type="text"
                               name="telephone"
                               value="{{ old('telephone', auth()->user()?->telephone) }}"
                               required
                               placeholder="+228 90 12 34 56"
                               class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm focus:border-red-800 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Nombre de convives *</label>
                        <select name="nombre_personnes" required class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm focus:border-red-800 outline-none bg-white">
                            @for($i = 1; $i <= 20; $i++)
                                <option value="{{ $i }}" {{ old('nombre_personnes', 2) == $i ? 'selected' : '' }}>
                                    {{ $i }} {{ $i === 1 ? 'personne' : 'personnes' }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Date souhaitée *</label>
                        <input type="date"
                               name="date_reservation"
                               min="{{ date('Y-m-d') }}"
                               value="{{ old('date_reservation', date('Y-m-d')) }}"
                               required
                               class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm focus:border-red-800 outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Créneau horaire *</label>
                        <select name="heure_reservation" required class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm focus:border-red-800 outline-none bg-white">
                            <optgroup label="Déjeuner">
                                <option value="12h00">12h00</option>
                                <option value="12h30" selected>12h30</option>
                                <option value="13h00">13h00</option>
                                <option value="13h30">13h30</option>
                                <option value="14h00">14h00</option>
                            </optgroup>
                            <optgroup label="Dîner">
                                <option value="19h00">19h00</option>
                                <option value="19h30">19h30</option>
                                <option value="20h00">20h00</option>
                                <option value="20h30">20h30</option>
                                <option value="21h00">21h00</option>
                                <option value="21h30">21h30</option>
                            </optgroup>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Occasion spéciale (optionnel)</label>
                        <select name="occasion" class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm focus:border-red-800 outline-none bg-white">
                            <option value="">Repas régulier</option>
                            <option value="anniversaire">Anniversaire</option>
                            <option value="affaires">Déjeuner / Dîner d'affaires</option>
                            <option value="romantique">Tête-à-tête romantique</option>
                            <option value="famille">Fête de famille</option>
                            <option value="autre">Autre événement</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Adresse email (confirmation)</label>
                        <input type="email"
                               name="email"
                               value="{{ old('email', auth()->user()?->email) }}"
                               placeholder="edwige@gmail.com"
                               class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm focus:border-red-800 outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Préférences ou demandes spécifiques</label>
                    <textarea name="remarques"
                              rows="3"
                              placeholder="Ex: Table en terrasse vue mer, chaise haute pour bébé, gâteau d'anniversaire..."
                              class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm focus:border-red-800 outline-none">{{ old('remarques') }}</textarea>
                </div>

                <div class="pt-4 border-t border-stone-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-xs text-slate-500">
                        Confirmation immédiate par nos maîtres d'hôtel Café Paris Lomé.
                    </p>
                    <button type="submit"
                            class="w-full sm:w-auto px-8 py-4 rounded-xl bg-red-800 hover:bg-red-900 text-white font-bold text-sm shadow-xl shadow-red-950/20 transition flex items-center justify-center gap-2">
                        <i data-lucide="check" class="w-4 h-4 text-amber-300"></i>
                        <span>Confirmer ma réservation</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
