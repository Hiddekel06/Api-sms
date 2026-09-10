<!DOCTYPE html>
<html lang="fr" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion · MFP Plateforme SMS</title>
    <link rel="icon" type="image/png" href="{{ asset('images/MFPremove.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-full flex items-center justify-center bg-slate-100 py-12 px-4">
    <div class="w-full max-w-md space-y-8">

        {{-- Logo et titre --}}
        <div class="text-center">
            <img src="{{ asset('images/MFPremove.png') }}" alt="MFP" class="h-20 w-20 object-contain mx-auto mb-4">
            <h1 class="text-2xl font-bold text-slate-900">Plateforme SMS</h1>
            <p class="text-sm text-slate-500 mt-1">Ministère de la Fonction Publique · République du Sénégal</p>
        </div>

        {{-- Formulaire --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
            <h2 class="text-base font-semibold text-slate-800 mb-6">Connexion administrateur</h2>

            @if ($errors->any())
                <div class="mb-5 p-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                        Adresse e-mail
                    </label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="email"
                        class="w-full border border-slate-300 rounded-lg px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-[#00853f] focus:ring-2 focus:ring-[#00853f]/20 transition"
                        placeholder="admin@exemple.sn"
                    >
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                        Mot de passe
                    </label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        class="w-full border border-slate-300 rounded-lg px-3.5 py-2.5 text-sm text-slate-900 focus:outline-none focus:border-[#00853f] focus:ring-2 focus:ring-[#00853f]/20 transition"
                        placeholder="••••••••"
                    >
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" id="remember" name="remember" class="rounded border-slate-300 text-[#00853f]">
                    <label for="remember" class="text-sm text-slate-600">Se souvenir de moi</label>
                </div>

                <button
                    type="submit"
                    class="w-full bg-[#00853f] hover:bg-[#006e34] text-white font-semibold text-sm py-2.5 rounded-lg shadow-sm transition mt-2"
                >
                    Se connecter
                </button>
            </form>
        </div>
    </div>
</body>
</html>

