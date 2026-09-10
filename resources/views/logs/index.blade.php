@extends('layouts.app')

@section('title', 'Journal des SMS · MFP Plateforme SMS')
@section('page-title', 'Journal des SMS & Historique des envois')

@section('content')
<div class="space-y-6" x-data="{ jsonModalOpen: false, modalData: null }">

    {{-- Cartes de Statistiques --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
            <span class="text-xs font-semibold uppercase text-slate-500 block">Total Envoyés</span>
            <span class="text-2xl font-bold text-slate-900 mt-1 block">{{ number_format($stats['total']) }}</span>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
            <span class="text-xs font-semibold uppercase text-purple-600 block">Via API (Clients)</span>
            <span class="text-2xl font-bold text-purple-900 mt-1 block">{{ number_format($stats['api']) }}</span>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
            <span class="text-xs font-semibold uppercase text-emerald-600 block">Succès</span>
            <span class="text-2xl font-bold text-emerald-700 mt-1 block">{{ number_format($stats['success']) }}</span>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-200 shadow-sm">
            <span class="text-xs font-semibold uppercase text-rose-600 block">Échecs</span>
            <span class="text-2xl font-bold text-rose-700 mt-1 block">{{ number_format($stats['failed']) }}</span>
        </div>
    </div>

    {{-- Filtres & Recherche --}}
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm space-y-4">
        <form method="GET" action="{{ route('logs.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            
            {{-- Filtre par Client / Token --}}
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Client / Prestataire</label>
                <select name="token_id" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-[#00853f] transition">
                    <option value="">Tous les clients</option>
                    @foreach ($tokens as $token)
                        <option value="{{ $token->id }}" {{ request('token_id') == $token->id ? 'selected' : '' }}>
                            {{ $token->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filtre par Source --}}
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Source d'envoi</label>
                <select name="source" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-[#00853f] transition">
                    <option value="">Toutes les sources</option>
                    <option value="api" {{ request('source') === 'api' ? 'selected' : '' }}>API Externe (Prestataire)</option>
                    <option value="web" {{ request('source') === 'web' ? 'selected' : '' }}>Interface Web (Admin)</option>
                </select>
            </div>

            {{-- Filtre par Statut --}}
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Statut de livraison</label>
                <select name="status" class="w-full bg-slate-50 border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-[#00853f] transition">
                    <option value="">Tous les statuts</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>✓ Succès</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>✕ Échec</option>
                </select>
            </div>

            {{-- Recherche texte / destinataire --}}
            <div>
                <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Recherche</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Numéro ou mot-clé..." class="w-full bg-slate-50 border border-slate-300 rounded-lg px-3 py-2 text-xs text-slate-800 focus:outline-none focus:border-[#00853f] transition">
            </div>

            {{-- Boutons Filtrer / Réinitialiser --}}
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-[#00853f] hover:bg-[#006e34] text-white font-semibold text-xs py-2 px-3 rounded-lg transition shadow-sm">
                    Filtrer
                </button>
                <a href="{{ route('logs.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium text-xs py-2 px-3 rounded-lg transition">
                    Effacer
                </a>
            </div>

        </form>
    </div>

    {{-- Tableau des Logs --}}
    <div class="bg-white border border-slate-200/80 rounded-2xl shadow-sm overflow-hidden">
        @if ($logs->isEmpty())
            <div class="text-center py-12 text-slate-400 text-sm">
                Aucun journal de message ne correspond à vos critères.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="text-[11px] uppercase bg-slate-50 text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4">Date / Heure</th>
                            <th class="py-3 px-4">Source & Émetteur</th>
                            <th class="py-3 px-4">Type</th>
                            <th class="py-3 px-4">Destinataire(s)</th>
                            <th class="py-3 px-4">Message</th>
                            <th class="py-3 px-4">Statut</th>
                            <th class="py-3 px-4 text-right">Détails</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($logs as $log)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4 text-slate-500 whitespace-nowrap font-mono text-[11px]">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="py-3 px-4">
                                @if ($log->source === 'api')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-semibold border bg-purple-50 text-purple-800 border-purple-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-purple-600"></span>
                                        API · {{ $log->apiToken?->name ?? 'Token Inconnu' }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-semibold border bg-sky-50 text-sky-800 border-sky-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-sky-600"></span>
                                        Interface Web
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <span class="{{ $log->type === 'bulk' ? 'bg-amber-50 text-amber-800 border-amber-200' : 'bg-slate-100 text-slate-700 border-slate-200' }} px-2 py-0.5 rounded text-[10px] font-semibold border">
                                    {{ $log->type === 'bulk' ? 'Masse ('.$log->recipient_count.')' : 'Unique' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 font-mono font-medium text-slate-900">
                                {{ $log->recipient ?? $log->recipient_count . ' destinataires' }}
                            </td>
                            <td class="py-3 px-4 max-w-sm">
                                <p class="text-slate-700 truncate" title="{{ $log->message }}">
                                    {{ $log->message }}
                                </p>
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="{{ $log->success ? 'text-emerald-700 bg-emerald-50 border-emerald-200' : 'text-rose-700 bg-rose-50 border-rose-200' }} font-semibold text-[11px] px-2.5 py-0.5 rounded-full border inline-flex items-center gap-1">
                                    {{ $log->success ? '✓ Envoyé' : '✕ Échec' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                @if ($log->raw_response)
                                    <button 
                                        type="button" 
                                        @click="modalData = {{ json_encode($log->raw_response) }}; jsonModalOpen = true"
                                        class="text-xs text-[#00853f] hover:text-[#006e34] font-medium hover:underline">
                                        Voir JSON
                                    </button>
                                @else
                                    <span class="text-slate-400 text-xs">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="p-4 border-t border-slate-200 bg-slate-50/50">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

    {{-- Modal Détails JSON --}}
    <div x-show="jsonModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60" @keydown.escape.window="jsonModalOpen = false">
        <div class="bg-slate-900 text-white rounded-2xl max-w-2xl w-full p-6 shadow-xl border border-slate-800 space-y-4" @click.outside="jsonModalOpen = false">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <h3 class="text-sm font-bold text-slate-200 flex items-center gap-2 font-mono">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span> Réponse brute Opérateur
                </h3>
                <button @click="jsonModalOpen = false" class="text-slate-400 hover:text-white font-bold text-lg">&times;</button>
            </div>
            <pre class="bg-slate-950 rounded-xl p-4 text-xs font-mono text-emerald-400 overflow-x-auto max-h-96 border border-slate-800 select-all" x-text="JSON.stringify(modalData, null, 2)"></pre>
            <div class="flex justify-end pt-2">
                <button @click="jsonModalOpen = false" class="bg-slate-800 hover:bg-slate-700 text-white text-xs font-semibold px-4 py-2 rounded-lg transition">
                    Fermer
                </button>
            </div>
        </div>
    </div>

</div>
@endsection

