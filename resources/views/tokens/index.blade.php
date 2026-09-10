@extends('layouts.app')

@section('title', 'Tokens API · MFP Plateforme SMS')
@section('page-title', 'Gestion des Tokens Clients & API')

@section('content')
<div class="space-y-6">

    {{-- Token généré (affiché une seule fois) --}}
    @if (session('generated_token'))
    <div class="bg-amber-50 border border-amber-300 rounded-2xl p-6 shadow-sm space-y-3">
        <div class="flex items-center gap-2">
            <span class="text-xl">🎉</span>
            <h2 class="font-bold text-amber-900">Token créé — copiez-le maintenant !</h2>
        </div>
        <p class="text-xs text-amber-800">Ce token ne sera <strong>jamais affiché à nouveau</strong>. Transmettez-le au client de façon sécurisée.</p>
        <div class="flex items-center gap-2">
            <code class="flex-1 bg-white border border-amber-300 rounded-lg px-4 py-3 text-sm font-mono text-slate-900 break-all select-all">{{ session('generated_token') }}</code>
            <button onclick="navigator.clipboard.writeText('{{ session('generated_token') }}'); this.textContent='✓ Copié !'; setTimeout(()=>this.textContent='Copier',2000);"
                class="shrink-0 bg-amber-500 hover:bg-amber-600 text-white font-semibold text-sm px-4 py-3 rounded-lg transition">Copier</button>
        </div>
    </div>
    @endif

    {{-- Créer un nouveau token --}}
    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm">
        <h2 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2"><span>➕</span> Créer un nouveau token</h2>
        <form method="POST" action="{{ route('tokens.store') }}" class="flex flex-col sm:flex-row gap-3">
            @csrf
            <input
                type="text"
                name="name"
                required
                placeholder="Nom du client ou du service (ex: Prestataire RH)"
                value="{{ old('name') }}"
                class="flex-1 border border-slate-300 rounded-lg px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-[#00853f] focus:ring-2 focus:ring-[#00853f]/20 transition"
            >
            <button type="submit" class="bg-[#00853f] hover:bg-[#006e34] text-white font-semibold text-sm px-6 py-2.5 rounded-lg shadow-sm transition">
                Générer le token
            </button>
        </form>
        @error('name')
            <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Liste des tokens --}}
    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm space-y-4">
        <h2 class="text-base font-bold text-slate-900 flex items-center gap-2"><span>🔑</span> Tokens existants</h2>

        @if ($tokens->isEmpty())
            <p class="text-center py-8 text-slate-400 text-sm">Aucun token créé pour l'instant.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="text-[11px] uppercase bg-slate-50 text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="py-2.5 px-3">Nom / Client</th>
                            <th class="py-2.5 px-3">Token (masqué)</th>
                            <th class="py-2.5 px-3">Statut</th>
                            <th class="py-2.5 px-3">Dernier usage</th>
                            <th class="py-2.5 px-3">Créé le</th>
                            <th class="py-2.5 px-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($tokens as $token)
                        <tr class="hover:bg-slate-50/80 {{ $token->revoked_at ? 'opacity-50' : '' }}">
                            <td class="py-3 px-3 font-semibold text-slate-900">{{ $token->name }}</td>
                            <td class="py-3 px-3 font-mono text-slate-500">{{ substr($token->token, 0, 8) }}••••••••••••••••••••••••••••••••</td>
                            <td class="py-3 px-3">
                                @if ($token->revoked_at)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold border bg-rose-50 text-rose-800 border-rose-200">Révoqué</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold border bg-emerald-50 text-emerald-800 border-emerald-200">Actif</span>
                                @endif
                            </td>
                            <td class="py-3 px-3 text-slate-500">{{ $token->last_used_at?->diffForHumans() ?? 'Jamais' }}</td>
                            <td class="py-3 px-3 text-slate-500">{{ $token->created_at->format('d/m/Y') }}</td>
                            <td class="py-3 px-3">
                                <div class="flex items-center gap-2">
                                    @if (! $token->revoked_at)
                                    <form method="POST" action="{{ route('tokens.revoke', $token) }}" onsubmit="return confirm('Révoquer ce token ?')">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="text-amber-600 hover:underline font-medium">Révoquer</button>
                                    </form>
                                    @endif
                                    <form method="POST" action="{{ route('tokens.destroy', $token) }}" onsubmit="return confirm('Supprimer définitivement ce token ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:underline font-medium">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection

