@extends('layouts.app')

@section('title', 'Connexion — Café Paris Lomé')

@section('content')
<div class="py-16 bg-[#FAF7F2] min-h-[calc(100vh-200px)] flex items-center justify-center">
    <div class="max-w-md w-full mx-4" x-data="{
        fillUser(email, pwd) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = pwd;
        }
    }">
        <div class="bg-white rounded-3xl p-8 sm:p-10 border border-stone-200 shadow-xl space-y-6">
            <!-- Brand icon -->
            <div class="text-center space-y-2">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-red-800 to-red-950 flex items-center justify-center text-amber-400 mx-auto shadow-md">
                    <i data-lucide="utensils-crossed" class="w-7 h-7"></i>
                </div>
                <h1 class="text-2xl font-serif font-bold text-slate-900">Espace de Connexion</h1>
                <p class="text-xs text-slate-500">Accédez à votre espace Client, Cuisine, Coursier ou Administrateur</p>
            </div>

            <!-- Fast Demo Fill Buttons for Examiners -->
            <div class="p-4 rounded-2xl bg-stone-50 border border-stone-200 space-y-2">
                <span class="block text-[11px] font-bold uppercase text-slate-500 tracking-wider">
                    ⚡ Remplissage rapide Démonstration :
                </span>
                <div class="grid grid-cols-2 gap-2">
                    <button type="button" @click="fillUser('admin@cafeparis.com', 'password')"
                            class="px-2.5 py-1.5 rounded-lg bg-red-900 text-white text-[11px] font-bold hover:bg-red-800 transition text-center truncate">
                        👑 Admin / Gérant
                    </button>
                    <button type="button" @click="fillUser('cuisine@cafeparis.com', 'password')"
                            class="px-2.5 py-1.5 rounded-lg bg-amber-600 text-white text-[11px] font-bold hover:bg-amber-500 transition text-center truncate">
                        👨‍🍳 Staff Cuisine
                    </button>
                    <button type="button" @click="fillUser('livreur1@cafeparis.com', 'password')"
                            class="px-2.5 py-1.5 rounded-lg bg-indigo-600 text-white text-[11px] font-bold hover:bg-indigo-500 transition text-center truncate">
                        🛵 Coursier Livreur
                    </button>
                    <button type="button" @click="fillUser('client@cafeparis.com', 'password')"
                            class="px-2.5 py-1.5 rounded-lg bg-stone-700 text-white text-[11px] font-bold hover:bg-stone-600 transition text-center truncate">
                        👤 Client Démo
                    </button>
                </div>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">Adresse email</label>
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email', 'admin@cafeparis.com') }}"
                           required
                           placeholder="votre@email.com"
                           class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm focus:border-red-800 outline-none">
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5">Mot de passe</label>
                    <input type="password"
                           id="password"
                           name="password"
                           value="password"
                           required
                           placeholder="••••••••"
                           class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm focus:border-red-800 outline-none">
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-600">
                        <input type="checkbox" name="remember" class="rounded text-red-800 focus:ring-red-800">
                        <span>Se souvenir de moi</span>
                    </label>
                </div>

                <button type="submit"
                        class="w-full py-3.5 rounded-xl bg-red-800 hover:bg-red-900 text-white font-bold text-sm shadow-xl shadow-red-950/20 transition flex items-center justify-center gap-2">
                    <i data-lucide="log-in" class="w-4 h-4 text-amber-300"></i>
                    <span>Se connecter</span>
                </button>
            </form>

            <div class="text-center text-xs text-slate-500 pt-2 border-t border-stone-100">
                <span>Vous êtes nouveau client ?</span>
                <a href="{{ route('register') }}" class="font-bold text-red-800 hover:underline ml-1">Créer un compte</a>
            </div>
        </div>
    </div>
</div>
@endsection
