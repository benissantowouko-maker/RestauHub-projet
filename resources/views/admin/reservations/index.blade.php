@extends('layouts.admin')

@section('title', 'Gestion des Réservations')
@section('page_title', 'Gestion des Réservations de Table')

@section('content')
<div class="space-y-6">

    <!-- KPI & Filter bar -->
    <div class="bg-slate-900 rounded-2xl border border-slate-800 p-4">
        <form method="GET" action="{{ route('admin.reservations.index') }}" class="flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <select name="statut" onchange="this.form.submit()" class="px-3.5 py-2 rounded-xl bg-slate-800 border border-slate-700 text-xs text-slate-200 outline-none">
                    <option value="tous" {{ $statut === 'tous' ? 'selected' : '' }}>Tous les statuts</option>
                    <option value="en_attente" {{ $statut === 'en_attente' ? 'selected' : '' }}>En attente ({{ $enAttenteCount }})</option>
                    <option value="confirmee" {{ $statut === 'confirmee' ? 'selected' : '' }}>Confirmées</option>
                    <option value="terminee" {{ $statut === 'terminee' ? 'selected' : '' }}>Terminées</option>
                    <option value="annulee" {{ $statut === 'annulee' ? 'selected' : '' }}>Annulées</option>
                </select>

                <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()"
                       class="px-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-xs text-slate-200 outline-none">

                @if($statut !== 'tous' || $date)
                    <a href="{{ route('admin.reservations.index') }}" class="p-2 rounded-xl bg-slate-800 text-slate-400 hover:text-white">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </a>
                @endif
            </div>

            <div class="text-xs text-slate-400">
                Aujourd'hui : <strong class="text-white">{{ $aujourdhuiCount }} réservation(s)</strong>
            </div>
        </form>
    </div>

    <!-- Reservations Table -->
    <div class="bg-slate-900 rounded-3xl border border-slate-800 p-6 space-y-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-400 border-b border-slate-800 uppercase tracking-wider text-[11px]">
                        <th class="py-3 px-3">Code Réf</th>
                        <th class="py-3 px-3">Nom & Téléphone</th>
                        <th class="py-3 px-3">Date & Heure</th>
                        <th class="py-3 px-3 text-center">Convives</th>
                        <th class="py-3 px-3">Occasion / Remarques</th>
                        <th class="py-3 px-3">Table Assignée</th>
                        <th class="py-3 px-3">Statut</th>
                        <th class="py-3 px-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($reservations as $r)
                        <tr class="hover:bg-slate-800/40 transition">
                            <td class="py-3.5 px-3 font-mono font-bold text-amber-400">
                                {{ $r->code_reservation }}
                            </td>
                            <td class="py-3.5 px-3">
                                <p class="font-bold text-white">{{ $r->nom }}</p>
                                <a href="tel:{{ $r->telephone }}" class="text-[11px] text-slate-400 hover:text-amber-400">
                                    {{ $r->telephone }}
                                </a>
                            </td>
                            <td class="py-3.5 px-3">
                                <p class="font-bold text-slate-200">{{ $r->date_reservation->format('d/m/Y') }}</p>
                                <p class="text-[11px] text-amber-400">{{ $r->heure_reservation }}</p>
                            </td>
                            <td class="py-3.5 px-3 text-center">
                                <span class="px-2 py-1 rounded-lg bg-slate-800 text-white font-bold border border-slate-700">
                                    {{ $r->nombre_personnes }} pers.
                                </span>
                            </td>
                            <td class="py-3.5 px-3 max-w-xs">
                                @if($r->occasion)
                                    <span class="text-[10px] uppercase font-bold text-red-400 block">{{ $r->occasion }}</span>
                                @endif
                                <p class="text-[11px] text-slate-400 truncate">{{ $r->remarques ?? 'Aucune consigne' }}</p>
                            </td>
                            <td class="py-3.5 px-3">
                                <form method="POST" action="{{ route('admin.reservations.update_status', $r->id) }}" class="flex items-center gap-1">
                                    @csrf
                                    <input type="hidden" name="statut" value="{{ $r->statut }}">
                                    <input type="text" name="table_numero" value="{{ $r->table_numero }}" placeholder="Ex: T-04"
                                           class="w-16 px-2 py-1 rounded bg-slate-800 border border-slate-700 text-xs text-white outline-none">
                                    <button type="submit" class="p-1 text-slate-400 hover:text-white" title="Enregistrer table">
                                        <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="py-3.5 px-3">
                                <span class="px-2.5 py-0.5 rounded text-[11px] font-bold border {{ $r->statut_badge_class }}">
                                    {{ $r->statut_label }}
                                </span>
                            </td>
                            <td class="py-3.5 px-3 text-right space-x-1">
                                @if($r->statut === 'en_attente')
                                    <form method="POST" action="{{ route('admin.reservations.update_status', $r->id) }}" class="inline-block">
                                        @csrf
                                        <input type="hidden" name="statut" value="confirmee">
                                        <button type="submit" class="px-2.5 py-1 rounded-lg bg-emerald-900/60 hover:bg-emerald-800 text-emerald-300 font-bold text-xs transition">
                                            Confirmer
                                        </button>
                                    </form>
                                @endif

                                @if($r->statut === 'confirmee')
                                    <form method="POST" action="{{ route('admin.reservations.update_status', $r->id) }}" class="inline-block">
                                        @csrf
                                        <input type="hidden" name="statut" value="terminee">
                                        <button type="submit" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs transition">
                                            Terminer
                                        </button>
                                    </form>
                                @endif

                                <form method="POST" action="{{ route('admin.reservations.destroy', $r->id) }}" class="inline-block"
                                      onsubmit="return confirm('Supprimer cette réservation ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 rounded-lg text-slate-500 hover:text-rose-400 transition" title="Supprimer">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-500">
                                Aucune réservation pour les critères sélectionnés.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-4 border-t border-slate-800">
            {{ $reservations->links() }}
        </div>
    </div>

</div>
@endsection
