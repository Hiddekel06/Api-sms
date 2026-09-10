@php
    $currentRoute = request()->route()?->getName();
@endphp

<aside class="admin-sidebar" :class="sidebarCollapsed ? 'admin-sidebar--collapsed' : ''">
    {{-- Brand Header --}}
    <div class="admin-sidebar__brand">
        <a href="{{ route('dashboard') }}" class="admin-sidebar__brand-link">
            <div class="admin-sidebar__brand-logo">
                <img src="{{ asset('images/MFPremove.png') }}" alt="Logo MFP">
            </div>
            <div class="admin-sidebar__brand-text" x-show="!sidebarCollapsed" x-transition.opacity>
                <p class="admin-sidebar__brand-title">Plateforme SMS</p>
                <p class="admin-sidebar__brand-subtitle">Ministère Fonction Publique</p>
            </div>
        </a>
    </div>

    {{-- Navigation --}}
    <nav class="admin-sidebar__nav">
        <div class="admin-sidebar__section-title" x-show="!sidebarCollapsed">Messagerie</div>

        {{-- Envoi SMS --}}
        <a href="{{ route('dashboard') }}" class="admin-sidebar__link {{ $currentRoute === 'dashboard' ? 'active' : '' }}" title="Envoi SMS">
            <i class="bi bi-chat-dots"></i>
            <span class="admin-sidebar__link-label" x-show="!sidebarCollapsed">Envoi SMS</span>
        </a>

        {{-- Journal des Logs --}}
        <a href="{{ route('logs.index') }}" class="admin-sidebar__link {{ $currentRoute === 'logs.index' ? 'active' : '' }}" title="Journal des Logs">
            <i class="bi bi-file-earmark-text"></i>
            <span class="admin-sidebar__link-label" x-show="!sidebarCollapsed">Journal des Logs</span>
        </a>

        <div class="admin-sidebar__section-title" x-show="!sidebarCollapsed">Administration</div>

        {{-- Liste des tokens --}}
        <a href="{{ route('tokens.index') }}" class="admin-sidebar__link {{ $currentRoute === 'tokens.index' ? 'active' : '' }}" title="Liste des tokens">
            <i class="bi bi-key"></i>
            <span class="admin-sidebar__link-label" x-show="!sidebarCollapsed">Liste des tokens</span>
        </a>

        {{-- Passerelle Opérateur --}}
        <div class="admin-sidebar__section-title" x-show="!sidebarCollapsed">Statut Opérateur</div>
        <div class="admin-sidebar__status-badge" x-show="!sidebarCollapsed">
            <div class="flex items-center gap-2 px-3 py-2 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Yas Business (Actif)</span>
            </div>
        </div>
    </nav>

    {{-- Footer Actions --}}
    <div class="admin-sidebar__footer">
        {{-- Bouton Réduire / Étendre --}}
        <button type="button" @click="sidebarCollapsed = !sidebarCollapsed" class="admin-sidebar__collapse-btn" aria-label="Réduire ou étendre la sidebar">
            <i class="bi bi-layout-sidebar"></i>
            <span class="admin-sidebar__collapse-label" x-show="!sidebarCollapsed" x-text="sidebarCollapsed ? '' : 'Réduire le menu'"></span>
        </button>

        {{-- Formulaire Déconnexion --}}
        <form action="{{ route('logout') }}" method="POST" class="admin-sidebar__logout-form">
            @csrf
            <button type="submit" class="admin-sidebar__logout-btn" title="Déconnexion">
                <i class="bi bi-box-arrow-right"></i>
                <span x-show="!sidebarCollapsed">Déconnexion</span>
            </button>
        </form>
    </div>
</aside>

