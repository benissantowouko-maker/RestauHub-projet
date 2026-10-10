@extends('layouts.app')

@section('title', 'Inscription Client — Café Paris Lomé')

@section('content')
<div class="py-16 bg-[#FAF7F2] min-h-[calc(100vh-200px)] flex items-center justify-center">
    <div class="max-w-md w-full mx-4">
        <div class="bg-white rounded-3xl p-8 sm:p-10 border border-stone-200 shadow-xl space-y-6">
            <!-- Brand icon -->
            <div class="text-center space-y-2">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-red-800 to-red-950 flex items-center justify-center text-amber-400 mx-auto shadow-md">
                    <i data-lucide="user-plus" class="w-7 h-7"></i>
                </div>
                <h1 class="text-2xl font-serif font-bold text-slate-900">Créer un Compte Client</h1>
                <p class="text-xs text-slate-500">Commandez en quelques clics et suivez vos livraisons à Lomé</p>
            </div>

            <!-- Register Form -->
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Nom complet *</label>
                    <input type="text"
                           name="name"
                           value="{{ old('name') }}"
                           required
                           placeholder="Ex: Kodjo Mensah"
                           class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm focus:border-red-800 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Numéro de téléphone (+228) *</label>
                    <input type="text"
                           name="telephone"
                           value="{{ old('telephone') }}"
                           required
                           placeholder="+228 90 00 00 00"
                           class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm focus:border-red-800 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Adresse email *</label>
                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           placeholder="votre@email.com"
                           class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm focus:border-red-800 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Mot de passe *</label>
                    <input type="password"
                           name="password"
                           required
                           placeholder="Au moins 6 caractères"
                           class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm focus:border-red-800 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Confirmez le mot de passe *</label>
                    <input type="password"
                           name="password_confirmation"
                           required
                           placeholder="Retapez votre mot de passe"
                           class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm focus:border-red-800 outline-none">
                </div>

                <button type="submit"
                        class="w-full py-3.5 rounded-xl bg-red-800 hover:bg-red-900 text-white font-bold text-sm shadow-xl shadow-red-950/20 transition flex items-center justify-center gap-2">
                    <i data-lucide="check" class="w-4 h-4 text-amber-300"></i>
                    <span>Créer mon compte</span>
                </button>
            </form>

            <div class="text-center text-xs text-slate-500 pt-2 border-t border-stone-100">
                <span>Déjà inscrit ?</span>
                <a href="{{ route('login') }}" class="font-bold text-red-800 hover:underline ml-1">Connectez-vous</a>
            </div>
        </div>
    </div>
</div>
@endsection
