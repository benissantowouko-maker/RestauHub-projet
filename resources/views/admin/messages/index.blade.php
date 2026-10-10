@extends('layouts.admin')

@section('title', 'Messages Clients')
@section('page_title', 'Messages & Demandes du Formulaire de Contact')

@section('content')
<div class="space-y-6" x-data="{ selectedMessage: null }">

    <div class="bg-slate-900 rounded-3xl border border-slate-800 p-6 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <div>
                <h3 class="font-bold text-white text-base">Boîte de Réception</h3>
                <p class="text-xs text-slate-400">Demandes de devis, réservations de groupe, avis</p>
            </div>
            <span class="px-3 py-1 rounded-full bg-slate-800 text-amber-400 text-xs font-bold">
                {{ $nonLusCount }} non lu(s)
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="text-slate-400 border-b border-slate-800 uppercase tracking-wider text-[11px]">
                        <th class="py-3 px-3">Date</th>
                        <th class="py-3 px-3">Expéditeur</th>
                        <th class="py-3 px-3">Contact</th>
                        <th class="py-3 px-3">Objet du Message</th>
                        <th class="py-3 px-3">Aperçu</th>
                        <th class="py-3 px-3">Statut</th>
                        <th class="py-3 px-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($messages as $msg)
                        <tr class="hover:bg-slate-800/40 transition {{ !$msg->lu ? 'bg-amber-950/20 font-semibold' : '' }}">
                            <td class="py-3.5 px-3 text-slate-400 whitespace-nowrap">
                                {{ $msg->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-3.5 px-3 font-bold text-white">
                                {{ $msg->nom }}
                            </td>
                            <td class="py-3.5 px-3">
                                <a href="mailto:{{ $msg->email }}" class="text-slate-300 hover:text-amber-400 block">{{ $msg->email }}</a>
                                <span class="text-[11px] text-slate-500">{{ $msg->telephone ?? 'Pas de tél' }}</span>
                            </td>
                            <td class="py-3.5 px-3 text-slate-200">
                                {{ $msg->sujet }}
                            </td>
                            <td class="py-3.5 px-3 max-w-xs text-slate-400 truncate">
                                {{ $msg->message }}
                            </td>
                            <td class="py-3.5 px-3">
                                @if(!$msg->lu)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-950 text-amber-300 border border-amber-700">Nouveau</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] text-slate-500">Lu</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-3 text-right space-x-1 whitespace-nowrap">
                                <button type="button" @click="selectedMessage = {{ json_encode($msg) }}"
                                        class="px-2.5 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs inline-flex items-center gap-1">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                    <span>Lire</span>
                                </button>

                                <form method="POST" action="{{ route('admin.messages.destroy', $msg->id) }}" class="inline-block"
                                      onsubmit="return confirm('Supprimer ce message ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg text-slate-500 hover:text-rose-400 transition" title="Supprimer">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-500">Aucun message pour le moment.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pt-4 border-t border-slate-800">
            {{ $messages->links() }}
        </div>
    </div>

    <!-- Message Reader Modal -->
    <div x-show="selectedMessage" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 max-w-xl w-full shadow-2xl space-y-4" @click.outside="selectedMessage = null">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <div>
                    <span class="text-[10px] uppercase font-bold text-amber-400">Message reçu</span>
                    <h3 class="font-bold text-white text-base mt-0.5" x-text="selectedMessage?.sujet"></h3>
                </div>
                <button type="button" @click="selectedMessage = null" class="text-slate-400 hover:text-white"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>

            <div class="p-4 rounded-2xl bg-slate-800/80 border border-slate-750 text-xs space-y-1.5">
                <p class="text-slate-200"><strong>Expéditeur :</strong> <span x-text="selectedMessage?.nom"></span></p>
                <p class="text-slate-200"><strong>Email :</strong> <a :href="'mailto:' + selectedMessage?.email" class="text-amber-400 underline" x-text="selectedMessage?.email"></a></p>
                <p class="text-slate-200"><strong>Téléphone :</strong> <span x-text="selectedMessage?.telephone || 'Non spécifié'"></span></p>
            </div>

            <div class="p-4 rounded-2xl bg-slate-950 border border-slate-800 text-xs text-slate-300 whitespace-pre-wrap leading-relaxed max-h-60 overflow-y-auto"
                 x-text="selectedMessage?.message"></div>

            <div class="flex items-center justify-between pt-2 border-t border-slate-800">
                <template x-if="selectedMessage && !selectedMessage.lu">
                    <form method="POST" :action="'{{ url('admin/messages') }}/' + selectedMessage?.id + '/lu'">
                        @csrf
                        <button type="submit" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold transition">
                            Marquer comme lu
                        </button>
                    </form>
                </template>
                <a :href="'mailto:' + selectedMessage?.email" class="ml-auto px-5 py-2.5 rounded-xl bg-red-800 hover:bg-red-700 text-white text-xs font-bold shadow transition flex items-center gap-1.5">
                    <i data-lucide="reply" class="w-3.5 h-3.5"></i>
                    <span>Répondre par email</span>
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
