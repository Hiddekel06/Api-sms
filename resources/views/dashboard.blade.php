@extends('layouts.app')

@section('title', 'Dashboard · MFP Plateforme SMS')
@section('page-title', 'Plateforme d\'expédition SMS')

@section('content')
<div class="space-y-6" x-data="smsApp()">

    {{-- Header --}}
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/80 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="p-1.5 bg-slate-50 rounded-2xl border border-slate-200">
                <img src="{{ asset('images/MFPremove.png') }}" alt="Logo" class="h-14 w-14 object-contain">
            </div>
            <div>
                <h1 class="text-lg font-bold text-slate-900">Plateforme d'expédition SMS</h1>
                <p class="text-xs text-slate-500 mt-0.5">Interface interne sécurisée</p>
            </div>
        </div>
        <div class="flex items-center gap-2 text-xs text-slate-600 bg-slate-50 px-3 py-2 rounded-xl border border-slate-200">
            <span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>Passerelle : <strong>Yas Business SN</strong></span>
        </div>
    </div>

    {{-- Onglets --}}
    <div class="flex bg-white p-1.5 rounded-xl border border-slate-200/80 shadow-sm space-x-1">
        <button @click="activeTab = 'single'" :class="activeTab === 'single' ? 'bg-[#00853f] text-white shadow-sm font-semibold' : 'text-slate-600 hover:bg-slate-50 font-medium'" class="flex-1 py-2.5 px-4 text-xs sm:text-sm rounded-lg transition-all flex items-center justify-center gap-2">
            <span>💬</span> Envoi Unique
        </button>
        <button @click="activeTab = 'bulk'" :class="activeTab === 'bulk' ? 'bg-[#00853f] text-white shadow-sm font-semibold' : 'text-slate-600 hover:bg-slate-50 font-medium'" class="flex-1 py-2.5 px-4 text-xs sm:text-sm rounded-lg transition-all flex items-center justify-center gap-2">
            <span>🚀</span> Envoi en Masse
        </button>
        <button @click="activeTab = 'status'" :class="activeTab === 'status' ? 'bg-[#00853f] text-white shadow-sm font-semibold' : 'text-slate-600 hover:bg-slate-50 font-medium'" class="flex-1 py-2.5 px-4 text-xs sm:text-sm rounded-lg transition-all flex items-center justify-center gap-2">
            <span>🔍</span> Suivi de Statut
        </button>
    </div>

    {{-- Alerte --}}
    <div x-show="alert.show" x-transition class="p-4 rounded-xl border shadow-sm"
        :class="{ 'bg-emerald-50 border-emerald-200 text-emerald-900': alert.type === 'success', 'bg-rose-50 border-rose-200 text-rose-900': alert.type === 'error', 'bg-sky-50 border-sky-200 text-sky-900': alert.type === 'info' }">
        <div class="flex items-start justify-between gap-3">
            <div class="flex items-start gap-3">
                <span class="text-xl" x-text="alert.type === 'success' ? '✅' : (alert.type === 'error' ? '❌' : 'ℹ️')"></span>
                <div>
                    <h4 class="font-bold text-sm" x-text="alert.title"></h4>
                    <p class="text-xs sm:text-sm opacity-90 mt-0.5" x-text="alert.message"></p>
                </div>
            </div>
            <button @click="alert.show = false" class="text-slate-400 hover:text-slate-700 font-bold">&times;</button>
        </div>
    </div>

    {{-- TAB 1: SMS Unique --}}
    <div x-show="activeTab === 'single'" class="bg-white border border-slate-200/80 rounded-2xl p-6 sm:p-7 shadow-sm space-y-5">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h2 class="text-base font-bold text-slate-900 flex items-center gap-2"><span>💬</span> Envoi d'un message unique</h2>
            <span class="text-xs text-slate-500">Tous les champs avec * sont obligatoires</span>
        </div>
        <form @submit.prevent="sendSingleSms()" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700">Expéditeur</label>
                        <span class="text-[11px] font-medium text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">🔒 Officiel</span>
                    </div>
                    <input type="text" value="E-fPublique" readonly disabled class="w-full bg-slate-100/80 border border-slate-300 text-slate-700 font-semibold rounded-lg px-3.5 py-2.5 text-sm cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Numéro destinataire *</label>
                    <input type="text" x-model="singleForm.to" required placeholder="ex: 221774517228" class="w-full bg-white border border-slate-300 rounded-lg px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-[#00853f] focus:ring-2 focus:ring-[#00853f]/20 transition">
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700">Contenu du message *</label>
                    <span class="text-xs text-slate-500"><span :class="singleForm.text.length > 160 ? 'text-amber-600 font-bold' : 'text-slate-600'" x-text="singleForm.text.length"></span>/160 car.</span>
                </div>
                <textarea x-model="singleForm.text" required rows="4" placeholder="Saisissez le texte du message..." class="w-full bg-white border border-slate-300 rounded-lg p-3 text-sm text-slate-900 focus:outline-none focus:border-[#00853f] focus:ring-2 focus:ring-[#00853f]/20 transition"></textarea>
            </div>
            <div class="flex items-center justify-end pt-2">
                <button type="submit" :disabled="loading" class="bg-[#00853f] hover:bg-[#006e34] disabled:opacity-50 text-white font-semibold text-sm px-6 py-2.5 rounded-lg shadow-sm transition flex items-center gap-2">
                    <svg x-show="loading" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                    <span x-text="loading ? 'Envoi en cours...' : 'Envoyer le SMS'"></span>
                </button>
            </div>
        </form>
    </div>

    {{-- TAB 2: Bulk SMS --}}
    <div x-show="activeTab === 'bulk'" class="bg-white border border-slate-200/80 rounded-2xl p-6 sm:p-7 shadow-sm space-y-5">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h2 class="text-base font-bold text-slate-900 flex items-center gap-2"><span>🚀</span> Campagne d'envoi en masse</h2>
        </div>
        <form @submit.prevent="sendBulkSms()" class="space-y-4">
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700">Liste des numéros *</label>
                    <span class="text-xs bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200 text-emerald-800 font-bold" x-text="detectedNumbersCount + ' numéro(s) valide(s)'"></span>
                </div>
                <textarea x-model="bulkForm.recipientsInput" required rows="5" placeholder="221774517228&#10;221771234567&#10;221760001122" class="w-full bg-white border border-slate-300 rounded-lg p-3 text-sm text-slate-900 font-mono focus:outline-none focus:border-[#00853f] focus:ring-2 focus:ring-[#00853f]/20 transition"></textarea>
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Message commun *</label>
                <textarea x-model="bulkForm.text" required rows="3" placeholder="Saisissez le texte à diffuser..." class="w-full bg-white border border-slate-300 rounded-lg p-3 text-sm text-slate-900 focus:outline-none focus:border-[#00853f] focus:ring-2 focus:ring-[#00853f]/20 transition"></textarea>
            </div>
            <div class="flex justify-end pt-2">
                <button type="submit" :disabled="loading || detectedNumbersCount === 0" class="bg-[#00853f] hover:bg-[#006e34] disabled:opacity-50 text-white font-semibold text-sm px-6 py-2.5 rounded-lg shadow-sm transition flex items-center gap-2">
                    <svg x-show="loading" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                    <span x-text="loading ? 'Envoi en masse...' : 'Diffuser aux ' + detectedNumbersCount + ' destinataires'"></span>
                </button>
            </div>
        </form>
    </div>

    {{-- TAB 3: Statut --}}
    <div x-show="activeTab === 'status'" class="bg-white border border-slate-200/80 rounded-2xl p-6 sm:p-7 shadow-sm space-y-5">
        <div class="border-b border-slate-100 pb-3">
            <h2 class="text-base font-bold text-slate-900 flex items-center gap-2"><span>🔍</span> Suivi et vérification de livraison</h2>
        </div>
        <form @submit.prevent="checkStatus()" class="flex flex-col sm:flex-row gap-3">
            <input type="text" x-model="statusMessageId" required placeholder="Collez l'ID du message (ex: 45781290382...)" class="flex-1 bg-white border border-slate-300 rounded-lg px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-[#00853f] focus:ring-2 focus:ring-[#00853f]/20 transition">
            <button type="submit" :disabled="loading || !statusMessageId" class="bg-slate-800 hover:bg-slate-700 disabled:opacity-50 text-white font-semibold text-sm px-6 py-2.5 rounded-lg transition">Consulter</button>
        </form>
    </div>

    {{-- Réponse JSON --}}
    <div x-show="lastApiResponse" class="bg-slate-900 rounded-2xl p-5 text-white space-y-2 shadow-sm">
        <div class="flex items-center justify-between">
            <span class="text-xs font-mono text-slate-400 uppercase tracking-wider flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span> Réponse brute API (JSON)
            </span>
            <button @click="copyJson()" class="text-xs text-emerald-400 hover:underline" x-text="copied ? '✓ Copié !' : 'Copier JSON'"></button>
        </div>
        <pre class="bg-slate-950 rounded-lg p-4 text-xs font-mono text-emerald-300 overflow-x-auto max-h-60 border border-slate-800" x-text="JSON.stringify(lastApiResponse, null, 2)"></pre>
    </div>

    {{-- Logs récents --}}
    @if ($recentLogs->isNotEmpty())
    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-2">
                <span>🕒</span> Derniers envois récents
            </h3>
            <a href="{{ route('logs.index') }}" class="text-xs text-[#00853f] hover:text-[#006e34] font-semibold flex items-center gap-1 hover:underline">
                <span>Voir le journal complet avec filtres</span>
                <span>&rarr;</span>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="text-[11px] uppercase bg-slate-50 text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="py-2.5 px-3">Date</th>
                        <th class="py-2.5 px-3">Source</th>
                        <th class="py-2.5 px-3">Type</th>
                        <th class="py-2.5 px-3">Destinataire(s)</th>
                        <th class="py-2.5 px-3">Message</th>
                        <th class="py-2.5 px-3">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($recentLogs as $log)
                    <tr class="hover:bg-slate-50/80">
                        <td class="py-2.5 px-3 text-slate-500 whitespace-nowrap">{{ $log->created_at->format('d/m H:i') }}</td>
                        <td class="py-2.5 px-3">
                            @if ($log->source === 'api')
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold border bg-purple-50 text-purple-800 border-purple-200">
                                    API {{ $log->apiToken?->name ? '· '.$log->apiToken->name : '' }}
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold border bg-sky-50 text-sky-800 border-sky-200">Web</span>
                            @endif
                        </td>
                        <td class="py-2.5 px-3">
                            <span class="{{ $log->type === 'bulk' ? 'bg-amber-50 text-amber-800 border-amber-200' : 'bg-emerald-50 text-emerald-800 border-emerald-200' }} px-2 py-0.5 rounded text-[10px] font-semibold border">
                                {{ $log->type === 'bulk' ? 'Masse ('.$log->recipient_count.')' : 'Unique' }}
                            </span>
                        </td>
                        <td class="py-2.5 px-3 font-mono font-medium text-slate-800">{{ $log->recipient ?? $log->recipient_count.' destinataires' }}</td>
                        <td class="py-2.5 px-3 truncate max-w-xs text-slate-600">{{ Str::limit($log->message, 60) }}</td>
                        <td class="py-2.5 px-3">
                            <span class="{{ $log->success ? 'text-emerald-700 bg-emerald-50 border-emerald-200' : 'text-rose-700 bg-rose-50 border-rose-200' }} font-semibold text-[11px] px-2 py-0.5 rounded border">
                                {{ $log->success ? '✓ Envoyé' : '✕ Échec' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>

<script>
function smsApp() {
    return {
        activeTab: 'single',
        loading: false,
        copied: false,
        statusMessageId: '',
        lastApiResponse: null,
        alert: { show: false, type: 'success', title: '', message: '' },
        singleForm: { from: 'E-fPublique', to: '', text: '' },
        bulkForm: { from: 'E-fPublique', recipientsInput: '', text: '' },

        get detectedNumbersCount() {
            if (!this.bulkForm.recipientsInput.trim()) return 0;
            const items = this.bulkForm.recipientsInput.split(/[\r\n,;]+/).map(s => s.trim().replace(/[\s\-]/g, '')).filter(s => s.length > 0);
            return [...new Set(items)].length;
        },

        showAlert(type, title, message) {
            this.alert = { show: true, type, title, message };
        },

        async sendSingleSms() {
            this.loading = true;
            this.alert.show = false;
            try {
                const response = await fetch('/web/sms/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ to: this.singleForm.to, text: this.singleForm.text, from: 'E-fPublique' })
                });
                const data = await response.json();
                this.lastApiResponse = data;
                if (response.ok && data.success) {
                    this.showAlert('success', 'SMS expédié avec succès !', 'Le message a été transmis à l\'opérateur.');
                    setTimeout(() => window.location.reload(), 2000);
                } else {
                    this.showAlert('error', 'Échec de l\'envoi', data.error || 'Erreur lors de l\'envoi du SMS.');
                }
            } catch (err) {
                this.showAlert('error', 'Erreur réseau', err.message);
            } finally {
                this.loading = false;
            }
        },

        async sendBulkSms() {
            this.loading = true;
            this.alert.show = false;
            const recipients = [...new Set(this.bulkForm.recipientsInput.split(/[\r\n,;]+/).map(s => s.trim().replace(/[\s\-]/g, '')).filter(s => s.length > 0))];
            try {
                const response = await fetch('/web/sms/send-bulk', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ from: 'E-fPublique', recipients, text: this.bulkForm.text })
                });
                const data = await response.json();
                this.lastApiResponse = data;
                if (response.ok && data.success) {
                    this.showAlert('success', 'Campagne envoyée !', `${data.summary.sent}/${data.summary.total} SMS expédiés.`);
                    setTimeout(() => window.location.reload(), 2000);
                } else {
                    this.showAlert('error', 'Envoi incomplet', `${data.summary?.sent || 0}/${data.summary?.total || recipients.length} messages envoyés.`);
                }
            } catch (err) {
                this.showAlert('error', 'Erreur réseau', err.message);
            } finally {
                this.loading = false;
            }
        },

        async checkStatus() {
            this.loading = true;
            this.alert.show = false;
            try {
                const response = await fetch(`/web/sms/status/${encodeURIComponent(this.statusMessageId.trim())}`, {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await response.json();
                this.lastApiResponse = data;
                if (response.ok && data.success) {
                    this.showAlert('info', 'Statut récupéré', 'Consultez le résultat dans le panneau JSON.');
                } else {
                    this.showAlert('error', 'Erreur', data.error || 'Message introuvable.');
                }
            } catch (err) {
                this.showAlert('error', 'Erreur réseau', err.message);
            } finally {
                this.loading = false;
            }
        },

        copyJson() {
            if (!this.lastApiResponse) return;
            navigator.clipboard.writeText(JSON.stringify(this.lastApiResponse, null, 2));
            this.copied = true;
            setTimeout(() => this.copied = false, 2000);
        }
    }
}
</script>
@endsection

