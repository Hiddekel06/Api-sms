<!DOCTYPE html>
<html lang="fr" class="h-full bg-slate-100 text-slate-800">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ministère de la Fonction Publique - Plateforme SMS</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/MFPremove.png') }}">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        mfp: {
                            green: '#00853f',
                            greenHover: '#006e34',
                            yellow: '#fbbd08',
                            red: '#e11d48',
                            dark: '#0f172a',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-full flex flex-col justify-between py-8 px-4 sm:px-6 lg:px-8 font-sans antialiased bg-slate-100 text-slate-800" x-data="smsApp()" x-cloak>

    <div class="max-w-4xl w-full mx-auto space-y-6">
        
        <!-- Header with Official Logo -->
        <header class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/80 flex flex-col sm:flex-row items-center justify-between gap-5">
            <div class="flex items-center gap-4 text-center sm:text-left flex-col sm:flex-row">
                <div class="p-1.5 bg-slate-50 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-center">
                    <img src="{{ asset('images/MFPremove.png') }}" alt="Logo Fonction Publique" class="h-16 w-16 object-contain">
                </div>
                <div>
                    <div class="flex items-center justify-center sm:justify-start gap-2">
                        <h1 class="text-xl font-bold text-slate-900 tracking-tight">
                            Ministère de la Fonction Publique
                        </h1>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                            En ligne
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Plateforme d'expédition et de suivi des notifications SMS</p>
                </div>
            </div>

            <div class="flex items-center gap-2 text-xs text-slate-600 bg-slate-50 px-3 py-2 rounded-xl border border-slate-200">
                <span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Passerelle : <strong class="text-slate-800 font-semibold">Yas Business SN</strong></span>
            </div>
        </header>

        <!-- Navigation Tabs -->
        <div class="flex bg-white p-1.5 rounded-xl border border-slate-200/80 shadow-sm space-x-1">
            <button 
                @click="activeTab = 'single'"
                :class="activeTab === 'single' ? 'bg-[#00853f] text-white shadow-sm font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-medium'"
                class="flex-1 py-2.5 px-4 text-xs sm:text-sm rounded-lg transition-all flex items-center justify-center gap-2">
                <span>💬</span> Envoi Unique
            </button>
            <button 
                @click="activeTab = 'bulk'"
                :class="activeTab === 'bulk' ? 'bg-[#00853f] text-white shadow-sm font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-medium'"
                class="flex-1 py-2.5 px-4 text-xs sm:text-sm rounded-lg transition-all flex items-center justify-center gap-2">
                <span>🚀</span> Envoi en Masse
            </button>
            <button 
                @click="activeTab = 'status'"
                :class="activeTab === 'status' ? 'bg-[#00853f] text-white shadow-sm font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50 font-medium'"
                class="flex-1 py-2.5 px-4 text-xs sm:text-sm rounded-lg transition-all flex items-center justify-center gap-2">
                <span>🔍</span> Suivi de Statut
            </button>
        </div>

        <!-- Alert Notification Box -->
        <div x-show="alert.show" x-transition class="p-4 rounded-xl border shadow-sm transition"
            :class="{
                'bg-emerald-50 border-emerald-200 text-emerald-900': alert.type === 'success',
                'bg-rose-50 border-rose-200 text-rose-900': alert.type === 'error',
                'bg-sky-50 border-sky-200 text-sky-900': alert.type === 'info'
            }">
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

        <!-- TAB 1: Single SMS -->
        <div x-show="activeTab === 'single'" class="bg-white border border-slate-200/80 rounded-2xl p-6 sm:p-7 shadow-sm space-y-5">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <span class="text-lg">💬</span> Envoi d'un message unique
                </h2>
                <span class="text-xs text-slate-500">Tous les champs avec * sont obligatoires</span>
            </div>

            <form @submit.prevent="sendSingleSms()" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Locked Sender ID -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700">Expéditeur (Sender ID)</label>
                            <span class="inline-flex items-center gap-1 text-[11px] font-medium text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                Officiel & Validé
                            </span>
                        </div>
                        <div class="relative">
                            <input type="text" value="E-fPublique" readonly disabled
                                class="w-full bg-slate-100/80 border border-slate-300 text-slate-700 font-semibold rounded-lg px-3.5 py-2.5 text-sm cursor-not-allowed select-none">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                                🔒
                            </div>
                        </div>
                        <span class="text-[11px] text-slate-500 mt-1 block">Identifiant approuvé par l'opérateur</span>
                    </div>

                    <!-- Destination Phone Number -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">Numéro destinataire *</label>
                        <input type="text" x-model="singleForm.to" required placeholder="ex: 221774517228"
                            class="w-full bg-white border border-slate-300 rounded-lg px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-[#00853f] focus:ring-2 focus:ring-[#00853f]/20 transition">
                        <span class="text-[11px] text-slate-500 mt-1 block">Format international (ex: 221774517228)</span>
                    </div>
                </div>

                <!-- Message Body -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700">Contenu du message *</label>
                        <span class="text-xs text-slate-500 font-medium">
                            <span :class="singleForm.text.length > 160 ? 'text-amber-600 font-bold' : 'text-slate-600'" x-text="singleForm.text.length"></span>/160 car.
                            (<span x-text="Math.ceil(singleForm.text.length / 160) || 1"></span> SMS)
                        </span>
                    </div>
                    <textarea x-model="singleForm.text" required rows="4" placeholder="Saisissez le texte du message à envoyer..."
                        class="w-full bg-white border border-slate-300 rounded-lg p-3 text-sm text-slate-900 focus:outline-none focus:border-[#00853f] focus:ring-2 focus:ring-[#00853f]/20 transition"></textarea>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <button type="button" @click="prefillSingleDemo()" class="text-xs font-medium text-[#00853f] hover:underline transition">
                        + Exemple de test
                    </button>

                    <button type="submit" :disabled="loading"
                        class="bg-[#00853f] hover:bg-[#006e34] disabled:opacity-50 text-white font-semibold text-sm px-6 py-2.5 rounded-lg shadow-sm transition flex items-center gap-2">
                        <svg x-show="loading" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <span x-text="loading ? 'Envoi en cours...' : 'Envoyer le SMS'"></span>
                    </button>
                </div>
            </form>
        </div>

        <!-- TAB 2: Bulk SMS -->
        <div x-show="activeTab === 'bulk'" class="bg-white border border-slate-200/80 rounded-2xl p-6 sm:p-7 shadow-sm space-y-5">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <span class="text-lg">🚀</span> Campagne d'envoi en masse
                </h2>
                <span class="text-xs text-slate-500">Diffusion groupée</span>
            </div>

            <form @submit.prevent="sendBulkSms()" class="space-y-4">
                <!-- Locked Sender ID in Bulk -->
                <div class="w-full sm:w-1/2">
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700">Expéditeur (Sender ID)</label>
                        <span class="inline-flex items-center gap-1 text-[11px] font-medium text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                            Officiel & Validé
                        </span>
                    </div>
                    <div class="relative">
                        <input type="text" value="E-fPublique" readonly disabled
                            class="w-full bg-slate-100/80 border border-slate-300 text-slate-700 font-semibold rounded-lg px-3.5 py-2.5 text-sm cursor-not-allowed">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                            🔒
                        </div>
                    </div>
                </div>

                <!-- Recipients Area -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700">Liste des numéros destinataires *</label>
                        <span class="text-xs bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200 text-emerald-800 font-bold"
                            x-text="detectedNumbersCount + ' numéro(s) valide(s)'"></span>
                    </div>
                    <textarea x-model="bulkForm.recipientsInput" required rows="5"
                        placeholder="Collez vos numéros ici (un par ligne ou séparés par des virgules) :&#10;221774517228&#10;221771234567&#10;221760001122"
                        class="w-full bg-white border border-slate-300 rounded-lg p-3 text-sm text-slate-900 font-mono focus:outline-none focus:border-[#00853f] focus:ring-2 focus:ring-[#00853f]/20 transition"></textarea>
                    <span class="text-[11px] text-slate-500 mt-1 block">Les doublons, tirets et espaces superflus sont nettoyés automatiquement.</span>
                </div>

                <!-- Common Message Body -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700">Message commun *</label>
                        <span class="text-xs text-slate-500 font-medium">
                            <span :class="bulkForm.text.length > 160 ? 'text-amber-600 font-bold' : 'text-slate-600'" x-text="bulkForm.text.length"></span>/160 car.
                        </span>
                    </div>
                    <textarea x-model="bulkForm.text" required rows="3" placeholder="Saisissez le texte du message à diffuser à tous..."
                        class="w-full bg-white border border-slate-300 rounded-lg p-3 text-sm text-slate-900 focus:outline-none focus:border-[#00853f] focus:ring-2 focus:ring-[#00853f]/20 transition"></textarea>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <button type="button" @click="prefillBulkDemo()" class="text-xs font-medium text-[#00853f] hover:underline transition">
                        + Exemple de lot
                    </button>

                    <button type="submit" :disabled="loading || detectedNumbersCount === 0"
                        class="bg-[#00853f] hover:bg-[#006e34] disabled:opacity-50 text-white font-semibold text-sm px-6 py-2.5 rounded-lg shadow-sm transition flex items-center gap-2">
                        <svg x-show="loading" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <span x-text="loading ? 'Envoi en masse...' : 'Diffuser aux ' + detectedNumbersCount + ' destinataires'"></span>
                    </button>
                </div>
            </form>
        </div>

        <!-- TAB 3: Status Checker -->
        <div x-show="activeTab === 'status'" class="bg-white border border-slate-200/80 rounded-2xl p-6 sm:p-7 shadow-sm space-y-5">
            <div class="border-b border-slate-100 pb-3">
                <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <span class="text-lg">🔍</span> Suivi et vérification de livraison
                </h2>
            </div>

            <form @submit.prevent="checkStatus()" class="flex flex-col sm:flex-row gap-3">
                <input type="text" x-model="statusMessageId" required placeholder="Collez l'ID du message retourné par l'API (ex: 45781290382...)"
                    class="flex-1 bg-white border border-slate-300 rounded-lg px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-[#00853f] focus:ring-2 focus:ring-[#00853f]/20 transition">
                <button type="submit" :disabled="loading || !statusMessageId"
                    class="bg-slate-800 hover:bg-slate-700 disabled:opacity-50 text-white font-semibold text-sm px-6 py-2.5 rounded-lg transition flex items-center justify-center gap-2">
                    <svg x-show="loading" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span>Consulter le statut</span>
                </button>
            </form>
        </div>

        <!-- Raw JSON API Inspector -->
        <div x-show="lastApiResponse" class="bg-slate-900 rounded-2xl p-5 text-white space-y-2 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-mono text-slate-400 uppercase tracking-wider flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span> Réponse brute de l'API (JSON)
                </span>
                <button @click="copyJson()" class="text-xs text-emerald-400 hover:underline">
                    <span x-text="copied ? '✓ Copié !' : 'Copier JSON'"></span>
                </button>
            </div>
            <pre class="bg-slate-950 rounded-lg p-4 text-xs font-mono text-emerald-300 overflow-x-auto max-h-60 border border-slate-800" x-text="JSON.stringify(lastApiResponse, null, 2)"></pre>
        </div>

        <!-- Recent History Section -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-2">
                    <span>🕒</span> Historique des envois (Session locale)
                </h3>
                <button x-show="history.length > 0" @click="clearHistory()" class="text-xs text-rose-600 hover:underline font-medium transition">
                    Effacer l'historique
                </button>
            </div>

            <div x-show="history.length === 0" class="text-center py-6 text-slate-400 text-xs sm:text-sm">
                Aucun envoi enregistré dans cette session.
            </div>

            <div x-show="history.length > 0" class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="text-[11px] uppercase bg-slate-50 text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="py-2.5 px-3">Heure</th>
                            <th class="py-2.5 px-3">Type</th>
                            <th class="py-2.5 px-3">Destinataire(s)</th>
                            <th class="py-2.5 px-3">Message</th>
                            <th class="py-2.5 px-3">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-sans">
                        <template x-for="(item, idx) in history" :key="idx">
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-2.5 px-3 text-slate-500" x-text="item.time"></td>
                                <td class="py-2.5 px-3">
                                    <span :class="item.type === 'bulk' ? 'bg-amber-50 text-amber-800 border-amber-200' : 'bg-emerald-50 text-emerald-800 border-emerald-200'"
                                        class="px-2 py-0.5 rounded text-[10px] font-semibold border"
                                        x-text="item.type === 'bulk' ? 'Masse (' + item.count + ')' : 'Unique'"></span>
                                </td>
                                <td class="py-2.5 px-3 font-mono font-medium text-slate-800" x-text="item.to"></td>
                                <td class="py-2.5 px-3 truncate max-w-xs text-slate-600" x-text="item.text"></td>
                                <td class="py-2.5 px-3">
                                    <span :class="item.success ? 'text-emerald-700 bg-emerald-50 border-emerald-200' : 'text-rose-700 bg-rose-50 border-rose-200'"
                                        class="font-semibold text-[11px] px-2 py-0.5 rounded border inline-flex items-center gap-1"
                                        x-text="item.success ? '✓ Envoyé' : '✕ Échec'"></span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Footer -->
    <footer class="mt-10 text-center text-xs text-slate-500 border-t border-slate-200 pt-6">
        <p class="font-medium text-slate-600">Ministère de la Fonction Publique &bull; République du Sénégal</p>
        <p class="mt-1 text-slate-400">Intégration API SMS Sécurisée &bull; Gateway Yas Business</p>
    </footer>

    <!-- Alpine App Logic -->
    <script>
        function smsApp() {
            return {
                activeTab: 'single',
                loading: false,
                copied: false,
                statusMessageId: '',
                lastApiResponse: null,
                alert: {
                    show: false,
                    type: 'success',
                    title: '',
                    message: ''
                },
                singleForm: {
                    from: 'E-fPublique',
                    to: '',
                    text: ''
                },
                bulkForm: {
                    from: 'E-fPublique',
                    recipientsInput: '',
                    text: ''
                },
                history: JSON.parse(localStorage.getItem('yas_sms_history') || '[]'),

                get detectedNumbersCount() {
                    if (!this.bulkForm.recipientsInput.trim()) return 0;
                    const items = this.bulkForm.recipientsInput.split(/[\r\n,;]+/).map(s => s.trim().replace(/[\s\-]/g, '')).filter(s => s.length > 0);
                    return [...new Set(items)].length;
                },

                showAlert(type, title, message) {
                    this.alert = { show: true, type, title, message };
                },

                prefillSingleDemo() {
                    this.singleForm.to = '221774517228';
                    this.singleForm.text = 'Bonjour, ceci est une notification officielle du Ministère de la Fonction Publique.';
                },

                prefillBulkDemo() {
                    this.bulkForm.recipientsInput = "221774517228\n221771234567\n221768889900";
                    this.bulkForm.text = 'Ministère de la Fonction Publique : Votre demande a été traitée avec succès.';
                },

                async sendSingleSms() {
                    this.loading = true;
                    this.alert.show = false;
                    try {
                        const response = await fetch('/api/sms/send', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                to: this.singleForm.to,
                                text: this.singleForm.text,
                                from: 'E-fPublique'
                            })
                        });

                        const data = await response.json();
                        this.lastApiResponse = data;

                        if (response.ok && data.success) {
                            this.showAlert('success', 'SMS expédié avec succès !', 'Le message a été transmis à l\'opérateur.');
                            this.saveToHistory({
                                type: 'single',
                                to: this.singleForm.to,
                                text: this.singleForm.text,
                                success: true,
                                time: new Date().toLocaleTimeString()
                            });
                        } else {
                            this.showAlert('error', 'Échec de l\'envoi', data.error || 'Erreur lors de l\'envoi du SMS.');
                            this.saveToHistory({
                                type: 'single',
                                to: this.singleForm.to,
                                text: this.singleForm.text,
                                success: false,
                                time: new Date().toLocaleTimeString()
                            });
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
                    const recipients = this.bulkForm.recipientsInput.split(/[\r\n,;]+/).map(s => s.trim().replace(/[\s\-]/g, '')).filter(s => s.length > 0);
                    const uniqueRecipients = [...new Set(recipients)];

                    try {
                        const response = await fetch('/api/sms/send-bulk', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                from: 'E-fPublique',
                                recipients: uniqueRecipients,
                                text: this.bulkForm.text
                            })
                        });

                        const data = await response.json();
                        this.lastApiResponse = data;

                        if (response.ok && data.success) {
                            this.showAlert('success', 'Campagne envoyée avec succès !', `${data.summary.sent}/${data.summary.total} SMS expédiés.`);
                            this.saveToHistory({
                                type: 'bulk',
                                count: uniqueRecipients.length,
                                to: `${uniqueRecipients.length} destinataires`,
                                text: this.bulkForm.text,
                                success: true,
                                time: new Date().toLocaleTimeString()
                            });
                        } else {
                            const sent = data.summary?.sent || 0;
                            const total = data.summary?.total || uniqueRecipients.length;
                            this.showAlert('error', 'Envoi incomplet ou échoué', `${sent}/${total} messages envoyés.`);
                            this.saveToHistory({
                                type: 'bulk',
                                count: uniqueRecipients.length,
                                to: `${uniqueRecipients.length} destinataires`,
                                text: this.bulkForm.text,
                                success: false,
                                time: new Date().toLocaleTimeString()
                            });
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
                        const response = await fetch(`/api/sms/status/${encodeURIComponent(this.statusMessageId.trim())}`, {
                            headers: { 'Accept': 'application/json' }
                        });

                        const data = await response.json();
                        this.lastApiResponse = data;

                        if (response.ok && data.success) {
                            this.showAlert('info', 'Statut récupéré', 'Consultez le résultat complet dans le panneau JSON.');
                        } else {
                            this.showAlert('error', 'Erreur de consultation', data.error || 'Message introuvable.');
                        }
                    } catch (err) {
                        this.showAlert('error', 'Erreur réseau', err.message);
                    } finally {
                        this.loading = false;
                    }
                },

                saveToHistory(entry) {
                    this.history.unshift(entry);
                    if (this.history.length > 20) this.history.pop();
                    localStorage.setItem('yas_sms_history', JSON.stringify(this.history));
                },

                clearHistory() {
                    this.history = [];
                    localStorage.removeItem('yas_sms_history');
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
</body>
</html>


