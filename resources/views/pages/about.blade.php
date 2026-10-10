@extends('layouts.app')

@section('title', 'À Propos de Nous — Café Paris Lomé')

@section('content')
<!-- Header Banner -->
<section class="bg-gradient-to-r from-red-950 via-stone-900 to-red-950 text-white py-16 border-b border-red-900/40">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center space-y-3">
        <span class="inline-block px-3 py-1 rounded-full bg-red-900/60 text-amber-300 text-xs font-bold uppercase tracking-wider border border-red-700/50">
            Notre Histoire
        </span>
        <h1 class="text-3xl sm:text-5xl font-serif font-bold tracking-tight">L'Élégance Culinaire au Cœur de Lomé</h1>
        <p class="text-sm sm:text-base text-stone-300 max-w-xl mx-auto">
            Une table gourmande où se rencontrent tradition de la brasserie française et authenticité des saveurs togolaises.
        </p>
    </div>
</section>

<section class="py-16 bg-[#FAF7F2]">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">

        <!-- Story Block 1 -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
            <div class="space-y-4">
                <span class="text-xs font-bold uppercase text-red-800 tracking-wider">Depuis plus de 10 ans</span>
                <h2 class="text-2xl sm:text-3xl font-serif font-bold text-slate-900 leading-tight">
                    Une Passion pour le Fait Maison et les Produits Frais
                </h2>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Installé sur le front de mer à Lomé, Café Paris est né d'un rêve : offrir aux Loméens et aux voyageurs une expérience bistronomique d'excellence. Nos chefs revisitent avec générosité les grands classiques de la cuisine parisienne (entrecôtes maître d'hôtel, salades niçoises, crèmes brûlées) tout en valorisant la fraîcheur incomparable des produits locaux : poissons fraîchement pêchés au port de Lomé, volailles fermières et épices togolaises sélectionnées.
                </p>
                <div class="pt-2 flex items-center gap-6">
                    <div>
                        <p class="text-2xl font-black text-red-900">15 000+</p>
                        <p class="text-xs text-slate-500">Clients servis</p>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-red-900">4.9 / 5</p>
                        <p class="text-xs text-slate-500">Satisfaction clients</p>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-red-900">35 min</p>
                        <p class="text-xs text-slate-500">Délai moyen livraison</p>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl overflow-hidden shadow-xl border border-stone-200">
                <img src="https://images.unsplash.com/photo-1550966871-3ed3cdb5ed0c?w=800&auto=format&fit=crop&q=80"
                     alt="Chef Café Paris Lomé"
                     class="w-full h-80 object-cover">
            </div>
        </div>

        <!-- Pillars -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-8">
            <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm space-y-3">
                <div class="w-10 h-10 rounded-xl bg-red-100 text-red-800 flex items-center justify-center font-bold">
                    <i data-lucide="flame" class="w-5 h-5"></i>
                </div>
                <h3 class="font-bold text-slate-900 text-base">Cuisson au Feu de Bois</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Nos pâtes à pizza reposent 48h et nos grillades sont saisies au feu de bois pour une saveur fumée authentique.
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm space-y-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-900 flex items-center justify-center font-bold">
                    <i data-lucide="shield-check" class="w-5 h-5"></i>
                </div>
                <h3 class="font-bold text-slate-900 text-base">Rigueur & Hygiène</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Chaîne du froid certifiée, emballages scellés hermétiques et respect strict des normes sanitaires internationales.
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm space-y-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold">
                    <i data-lucide="zap" class="w-5 h-5"></i>
                </div>
                <h3 class="font-bold text-slate-900 text-base">Digitalisation Locale</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Commande en ligne fluide, suivi par GPS en temps réel et paiement direct par T-Money ou Flooz sans intermédiaire.
                </p>
            </div>
        </div>

        <!-- CTA -->
        <div class="bg-white rounded-3xl p-8 border border-stone-200 text-center space-y-4">
            <h3 class="text-xl font-serif font-bold text-slate-900">Venez nous rendre visite au Boulevard de la Marina</h3>
            <p class="text-xs text-slate-500 max-w-lg mx-auto">
                Notre terrasse ombragée vous accueille chaque jour de 08h00 à 23h30 pour vos petits déjeuners, déjeuners et dîners.
            </p>
            <div class="pt-2 flex items-center justify-center gap-4">
                <a href="{{ route('menu.index') }}" class="px-6 py-3 rounded-xl bg-red-800 text-white font-bold text-xs shadow hover:bg-red-900 transition">
                    Découvrir notre menu
                </a>
                <a href="{{ route('reservation.create') }}" class="px-6 py-3 rounded-xl border border-stone-300 text-slate-700 font-bold text-xs hover:bg-stone-50 transition">
                    Réserver une table
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
