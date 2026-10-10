@extends('layouts.app')

@section('title', 'Contactez-Nous — Café Paris Lomé')

@section('content')
<!-- Header Banner -->
<section class="bg-gradient-to-r from-red-950 via-stone-900 to-red-950 text-white py-14 border-b border-red-900/40">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center space-y-3">
        <span class="inline-block px-3 py-1 rounded-full bg-red-900/60 text-amber-300 text-xs font-bold uppercase tracking-wider border border-red-700/50">
            Nous Contacter
        </span>
        <h1 class="text-3xl sm:text-4xl font-serif font-bold tracking-tight">Une Question ? Une Demande Spéciale ?</h1>
        <p class="text-sm sm:text-base text-stone-300 max-w-xl mx-auto">
            L'équipe de Café Paris Lomé est à votre écoute pour vos commandes, réceptions ou suggestions.
        </p>
    </div>
</section>

<section class="py-16 bg-[#FAF7F2]">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

            <!-- Left: Contact Form -->
            <div class="lg:col-span-7 bg-white rounded-3xl p-6 sm:p-10 border border-stone-200 shadow-sm space-y-6">
                <div>
                    <h2 class="text-xl font-serif font-bold text-slate-900">Envoyez-nous un message</h2>
                    <p class="text-xs text-slate-500 mt-1">Nous répondons généralement sous quelques heures.</p>
                </div>

                <form method="POST" action="{{ route('contact.send') }}" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Nom et Prénom *</label>
                            <input type="text"
                                   name="nom"
                                   value="{{ old('nom', auth()->user()?->name) }}"
                                   required
                                   placeholder="Votre nom"
                                   class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm focus:border-red-800 outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Adresse email *</label>
                            <input type="email"
                                   name="email"
                                   value="{{ old('email', auth()->user()?->email) }}"
                                   required
                                   placeholder="votre@email.com"
                                   class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm focus:border-red-800 outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Numéro de téléphone</label>
                            <input type="text"
                                   name="telephone"
                                   value="{{ old('telephone', auth()->user()?->telephone) }}"
                                   placeholder="+228 90 00 00 00"
                                   class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm focus:border-red-800 outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Sujet de votre demande *</label>
                            <input type="text"
                                   name="sujet"
                                   value="{{ old('sujet') }}"
                                   required
                                   placeholder="Ex: Privatisation, Commande traiteur..."
                                   class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm focus:border-red-800 outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Votre message *</label>
                        <textarea name="message"
                                  rows="4"
                                  required
                                  placeholder="Décrivez votre demande en quelques lignes..."
                                  class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm focus:border-red-800 outline-none">{{ old('message') }}</textarea>
                    </div>

                    <button type="submit"
                            class="w-full py-4 rounded-xl bg-red-800 hover:bg-red-900 text-white font-bold text-sm shadow-xl shadow-red-950/20 transition flex items-center justify-center gap-2">
                        <i data-lucide="send" class="w-4 h-4 text-amber-300"></i>
                        <span>Envoyer mon message</span>
                    </button>
                </form>
            </div>

            <!-- Right: Info Details & Hours -->
            <div class="lg:col-span-5 space-y-6">
                <!-- Contact info card -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-stone-200 shadow-sm space-y-5">
                    <h3 class="font-serif font-bold text-lg text-slate-900 border-b border-stone-100 pb-3">Coordonnées Café Paris</h3>

                    <div class="space-y-4 text-xs text-slate-700">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-red-100 text-red-800 flex items-center justify-center shrink-0">
                                <i data-lucide="map-pin" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <strong class="block text-slate-900 text-sm">Adresse à Lomé :</strong>
                                <span>Boulevard de la Marina, Angle Rue de la Paix, Lomé, Togo</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-900 flex items-center justify-center shrink-0">
                                <i data-lucide="phone" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <strong class="block text-slate-900 text-sm">Lignes directes :</strong>
                                <a href="tel:+22890001234" class="text-red-800 font-semibold hover:underline block">+228 90 00 12 34 (Principal)</a>
                                <a href="tel:+22898001234" class="text-red-800 font-semibold hover:underline block">+228 98 00 12 34 (Livraisons)</a>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-900 flex items-center justify-center shrink-0">
                                <i data-lucide="mail" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <strong class="block text-slate-900 text-sm">Email :</strong>
                                <a href="mailto:contact@cafeparis-lome.site" class="text-slate-600 hover:text-red-800">contact@cafeparis-lome.site</a>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-stone-100 text-stone-800 flex items-center justify-center shrink-0">
                                <i data-lucide="clock" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <strong class="block text-slate-900 text-sm">Horaires d'ouverture :</strong>
                                <p>Lundi au Dimanche : 08h00 - 23h30</p>
                                <p class="text-[11px] text-slate-500">Service continu & Livraison non-stop</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fast Order CTA -->
                <div class="bg-gradient-to-br from-red-950 to-stone-900 text-white rounded-3xl p-6 border border-red-900/40 space-y-3">
                    <h4 class="font-serif font-bold text-base">Prêt pour une commande immédiate ?</h4>
                    <p class="text-xs text-stone-300">Notre cuisine commence la préparation dès validation de votre panier.</p>
                    <a href="{{ route('menu.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-amber-400 text-stone-950 font-bold text-xs shadow hover:bg-amber-300 transition">
                        <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                        <span>Commander en ligne</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
