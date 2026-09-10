<!DOCTYPE html>
<html lang="fr" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MFP · Plateforme SMS')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/MFPremove.png') }}">
    
    {{-- Bootstrap Icons CDN --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        mfp: {
                            green: '#00853f',
                            greenHover: '#006e34',
                            dark: '#0f172a',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }

        /* ==========================================================================
           Admin Sidebar Component Styles (Thème Clair / Blanc)
           ========================================================================== */
        .admin-sidebar {
            width: 260px;
            background-color: #ffffff;
            color: #1e293b;
            display: flex;
            flex-direction: column;
            height: 100vh;
            position: sticky;
            top: 0;
            flex-shrink: 0;
            border-right: 1px solid #e2e8f0;
            transition: width 0.25s ease-in-out;
            z-index: 50;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        }

        .admin-sidebar--collapsed {
            width: 76px;
        }

        .admin-sidebar__brand {
            padding: 1.25rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            background-color: #ffffff;
        }

        .admin-sidebar__brand-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: inherit;
        }

        .admin-sidebar__brand-logo {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4px;
            flex-shrink: 0;
        }

        .admin-sidebar__brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .admin-sidebar__brand-title {
            font-size: 0.875rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            line-height: 1.2;
        }

        .admin-sidebar__brand-subtitle {
            font-size: 0.6875rem;
            color: #00853f;
            margin: 0;
            font-weight: 600;
        }

        .admin-sidebar__nav {
            flex: 1;
            padding: 1rem 0.75rem;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .admin-sidebar__section-title {
            font-size: 0.6875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #94a3b8;
            padding: 0.875rem 0.75rem 0.25rem;
        }

        .admin-sidebar__link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 0.75rem;
            border-radius: 0.5rem;
            color: #475569;
            text-decoration: none;
            font-size: 0.8125rem;
            font-weight: 500;
            transition: all 0.15s ease;
            width: 100%;
            border: none;
            background: transparent;
            text-align: left;
            cursor: pointer;
        }

        .admin-sidebar__link i {
            font-size: 1.125rem;
            flex-shrink: 0;
            width: 20px;
            text-align: center;
            color: #64748b;
        }

        .admin-sidebar__link:hover {
            color: #0f172a;
            background-color: #f1f5f9;
        }

        .admin-sidebar__link:hover i {
            color: #0f172a;
        }

        .admin-sidebar__link.active {
            color: #ffffff;
            background-color: #00853f;
            font-weight: 600;
            box-shadow: 0 1px 3px rgba(0, 133, 63, 0.3);
        }

        .admin-sidebar__link.active i {
            color: #ffffff;
        }

        .admin-sidebar__footer {
            padding: 0.75rem;
            border-top: 1px solid #f1f5f9;
            background-color: #f8fafc;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .admin-sidebar__collapse-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            color: #64748b;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            font-size: 0.75rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.15s ease;
            width: 100%;
            justify-content: center;
        }

        .admin-sidebar__collapse-btn:hover {
            color: #0f172a;
            border-color: #cbd5e1;
            background-color: #f1f5f9;
        }

        .admin-sidebar__logout-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            color: #dc2626;
            background: #fef2f2;
            border: 1px solid #fee2e2;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
            width: 100%;
        }

        .admin-sidebar__logout-btn:hover {
            background: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-slate-100" x-data="{ sidebarCollapsed: false, mobileSidebarOpen: false }">

    <div class="min-h-full flex">
        {{-- Mobile Overlay --}}
        <div x-show="mobileSidebarOpen" x-transition.opacity class="fixed inset-0 z-40 bg-slate-900/60 lg:hidden" @click="mobileSidebarOpen = false"></div>

        {{-- Sidebar Component --}}
        <div :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'" class="fixed inset-y-0 left-0 z-50 transition-transform duration-200 ease-in-out lg:static shrink-0">
            @include('layouts.partials.sidebar')
        </div>

        {{-- Main Content Container --}}
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            {{-- Top Navbar --}}
            <header class="h-16 bg-white border-b border-slate-200/80 flex items-center justify-between px-4 sm:px-6 lg:px-8 shrink-0">
                <div class="flex items-center gap-3">
                    <button @click="mobileSidebarOpen = true" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 lg:hidden">
                        <i class="bi bi-list text-xl"></i>
                    </button>
                    <h1 class="text-sm sm:text-base font-bold text-slate-800 truncate">
                        @yield('page-title', 'Tableau de bord')
                    </h1>
                </div>

                <div class="flex items-center gap-3">
                    <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Passerelle Yas Business SN
                    </span>
                    <div class="w-8 h-8 rounded-lg bg-[#00853f] text-white flex items-center justify-center font-bold text-xs">
                        {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                    </div>
                </div>
            </header>

            {{-- Main Content View --}}
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                <div class="max-w-6xl mx-auto space-y-6">
                    @if (session('success'))
                        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-sm font-medium flex items-center gap-2 shadow-sm">
                            <i class="bi bi-check-circle-fill text-emerald-600 text-base"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>

        </div>
    </div>

</body>
</html>


