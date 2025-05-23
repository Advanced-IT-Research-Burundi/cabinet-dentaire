@extends('layouts.app')

@section('title', 'Administration du Système')

@section('content')
<div class="container-fluid">
    <!-- Statistiques générales -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-wrapper bg-primary bg-opacity-10 text-primary me-3">
                            <i class="bi bi-people"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $totalUsers }}</h3>
                            <small class="text-muted">Utilisateurs actifs</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-wrapper bg-success bg-opacity-10 text-success me-3">
                            <i class="bi bi-activity"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $activeSessions->count() }}</h3>
                            <small class="text-muted">Sessions en cours</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card system-health">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-wrapper bg-white bg-opacity-20 text-white me-3">
                            <i class="bi bi-cpu"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $systemHealth }}%</h3>
                            <small class="opacity-90">Santé système</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-wrapper bg-warning bg-opacity-10 text-warning me-3">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $activeAlerts }}</h3>
                            <small class="text-muted">Alertes actives</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation par onglets -->
    <ul class="nav nav-pills mb-4">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="pill" href="#sessions">
                <i class="bi bi-person-circle me-2"></i>Sessions Utilisateurs
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="pill" href="#system-config">
                <i class="bi bi-gear me-2"></i>Configuration
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="pill" href="#monitoring">
                <i class="bi bi-graph-up me-2"></i>Surveillance
            </a>
        </li>
        {{-- <li class="nav-item">
            <a class="nav-link" data-bs-toggle="pill" href="#logs">
                <i class="bi bi-journal-text me-2"></i>Journaux
            </a>
        </li> --}}
    </ul>

    <!-- Contenu des onglets -->
    <div class="tab-content">
        <!-- Gestion des Sessions -->
        <div class="tab-pane fade show active" id="sessions">
            <div class="row g-4">
                <div class="col-12">
                    <div class="card admin-card">
                        <div class="card-header bg-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="bi bi-person-lines-fill text-primary me-2"></i>
                                    Sessions Utilisateurs Actives
                                </h5>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-outline-danger btn-sm" onclick="logoutAllSessions()">
                                        <i class="bi bi-power me-2"></i>Déconnecter tout
                                    </button>
                                    <button class="btn btn-primary btn-sm" onclick="refreshSessions()">
                                        <i class="bi bi-arrow-clockwise me-2"></i>Actualiser
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover session-table mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Utilisateur</th>
                                            <th>Rôle</th>
                                            <th>Statut</th>
                                            <th>Adresse IP</th>
                                            <th>Navigateur</th>
                                            <th>Dernière activité</th>
                                            <th>Durée</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($activeSessions as $sessionData)
                                            @php
                                                $user = $sessionData['user'];
                                                $session = $sessionData['session'];
                                                $lastActivity = \Carbon\Carbon::createFromTimestamp($session->last_activity);
                                                $isActive = (now()->timestamp - $session->last_activity) <= (config('session.lifetime') * 60);
                                                $duration = $lastActivity->diffForHumans(now(), true);

                                                // Déterminer l'icône du navigateur
                                                $userAgent = strtolower($session->user_agent);
                                                $browserIcon = 'bi-browser-chrome';
                                                $browserName = 'Chrome';

                                                if (str_contains($userAgent, 'firefox')) {
                                                    $browserIcon = 'bi-browser-firefox';
                                                    $browserName = 'Firefox';
                                                } elseif (str_contains($userAgent, 'edge')) {
                                                    $browserIcon = 'bi-browser-edge';
                                                    $browserName = 'Edge';
                                                } elseif (str_contains($userAgent, 'safari')) {
                                                    $browserIcon = 'bi-browser-safari';
                                                    $browserName = 'Safari';
                                                }

                                                // Couleur du badge selon le rôle
                                                $roleColors = [
                                                    'Admin' => 'bg-danger',
                                                    'Dentiste' => 'bg-primary',
                                                    'Secretaire' => 'bg-info',
                                                    'Pharmacist' => 'bg-warning'
                                                ];
                                                $roleColor = $roleColors[$user->role] ?? 'bg-secondary';

                                                // Initiales pour l'avatar
                                                $initials = strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1));
                                            @endphp
                                            <tr data-user-id="{{ $user->id }}" class="session-row">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm {{ str_replace('bg-', 'bg-', $roleColor) }} rounded-circle text-white d-flex align-items-center justify-content-center me-3">
                                                            {{ $initials }}
                                                        </div>
                                                        <div>
                                                            <div class="fw-semibold">{{ $user->getFullNameAttribute() }}</div>
                                                            <small class="text-muted">{{ $user->email }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge {{ $roleColor }} badge-status">{{ $user->role }}</span>
                                                </td>
                                                <td>
                                                    @if($isActive)
                                                        <span class="badge {{ $user->isOnline() ? 'bg-success' : 'bg-secondary'}} badge-status">
                                                            <span class="online-indicator me-1"></span>{{$user->isOnline() ? 'En ligne' : 'Hors ligne'}}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary badge-status">
                                                            Inactif ({{ $lastActivity->diffForHumans() }})
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>{{ $session->ip_address }}</td>
                                                <td>
                                                    <i class="bi {{ $browserIcon }} text-warning me-1"></i>
                                                    {{ $browserName }}
                                                </td>
                                                <td>{{ $lastActivity->diffForHumans() }}</td>
                                                <td>{{ $duration }}</td>
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group" aria-label="Actions utilisateur">
                                                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-outline-secondary" title="Détails">
                                                        <i class="bi bi-info-circle"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-warning" title="Suspendre" onclick="suspendSession('{{ $session->id }}')">
                                                        <i class="bi bi-pause-circle"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger" title="Déconnecter" onclick="logoutUser('{{ $user->id }}')">
                                                        <i class="bi bi-power"></i>
                                                    </button>
                                                </div>

                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-4">
                                                    <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                                                    <p class="text-muted mt-2">Aucune session active</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistiques de session -->
                <div class="col-md-6">
                    <div class="card admin-card">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">
                                <i class="bi bi-clock-history text-info me-2"></i>
                                Activité des Sessions
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-4">
                                    <h4 class="text-success">{{ $sessionStats['active'] }}</h4>
                                    <small class="text-muted">Actives</small>
                                </div>
                                <div class="col-4">
                                    <h4 class="text-warning">{{ $sessionStats['inactive'] }}</h4>
                                    <small class="text-muted">Inactives</small>
                                </div>
                                <div class="col-4">
                                    <h4 class="text-info">{{ $sessionStats['today'] }}</h4>
                                    <small class="text-muted">Aujourd'hui</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="col-md-6">
                    <div class="card admin-card">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">
                                <i class="bi bi-lightning text-warning me-2"></i>
                                Actions Rapides
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button class="btn btn-outline-warning btn-sm quick-action-btn" onclick="suspendInactiveSessions()">
                                    <i class="bi bi-pause-circle me-2"></i>Suspendre inactives
                                </button>
                                <a href="{{ route('admin.sessions.export') }}" class="btn btn-outline-info btn-sm quick-action-btn">
                                    <i class="bi bi-download me-2"></i>Exporter rapport
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Configuration Système -->
        <div class="tab-pane fade" id="system-config">
            <div class="row g-4">
                <!-- Types de traitements -->
                <div class="col-md-6 col-xl-4">
                    <div class="card admin-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-wrapper bg-primary bg-opacity-10 text-primary me-3">
                                    <i class="bi bi-clipboard2-pulse"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">Types de traitements</h6>
                                    <small class="text-muted">{{ $treatmentTypesCount }} types configurés</small>
                                </div>
                            </div>
                            <p class="card-text text-muted mb-3">
                                Configurez les différents types de traitements disponibles, leurs coûts et durées.
                            </p>
                            <a href="{{ route('settings.treatment-types.index') }}" class="btn btn-primary w-100">
                                <i class="bi bi-gear me-2"></i>Gérer
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Méthodes de paiement -->
                <div class="col-md-6 col-xl-4">
                    <div class="card admin-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-wrapper bg-success bg-opacity-10 text-success me-3">
                                    <i class="bi bi-credit-card"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">Méthodes de paiement</h6>
                                    <small class="text-muted">{{ $paymentMethodsCount }} méthodes actives</small>
                                </div>
                            </div>
                            <p class="card-text text-muted mb-3">
                                Gérez les différentes méthodes de paiement acceptées par votre cabinet.
                            </p>
                            <a href="{{ route('settings.payment-methods.index') }}" class="btn btn-success w-100">
                                <i class="bi bi-credit-card me-2"></i>Gérer
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Gestion des utilisateurs -->
                <div class="col-md-6 col-xl-4">
                    <div class="card admin-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-wrapper bg-info bg-opacity-10 text-info me-3">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">Gestion utilisateurs</h6>
                                    <small class="text-muted">{{ $totalUsers }} utilisateurs</small>
                                </div>
                            </div>
                            <p class="card-text text-muted mb-3">
                                Créez, modifiez et gérez les comptes utilisateurs et leurs permissions.
                            </p>
                            <a href="{{ route('users.index') }}" class="btn btn-info w-100">
                                <i class="bi bi-person-gear me-2"></i>Gérer
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Paramètres système -->
                <div class="col-md-6 col-xl-4">
                    <div class="card admin-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-wrapper bg-warning bg-opacity-10 text-warning me-3">
                                    <i class="bi bi-sliders"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">Paramètres système</h6>
                                    <small class="text-muted">Configuration globale</small>
                                </div>
                            </div>
                            <p class="card-text text-muted mb-3">
                                Configurez les paramètres généraux du système et les préférences.
                            </p>
                            <a href="{{ route('admin.settings.index') }}" class="btn btn-warning w-100">
                                <i class="bi bi-sliders me-2"></i>Configurer
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Sauvegardes -->
                <div class="col-md-6 col-xl-4">
                    <div class="card admin-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-wrapper bg-secondary bg-opacity-10 text-secondary me-3">
                                    <i class="bi bi-person-gear"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">Gestion dentistes</h6>
                                    <small class="text-muted">{{ $totalDentistes }} dentistes</small>
                                </div>
                            </div>
                            <p class="card-text text-muted mb-3">
                                Créez, modifiez et gérez les comptes dentistes .
                            </p>
                            <a href="{{ route('dentists.index')}}" class="btn btn-secondary w-100">
                                <i class="bi bi-person-gear me-2"></i></i>Gérer
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Notifications -->
                <div class="col-md-6 col-xl-4">
                    <div class="card admin-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div style="background-color: #d6de5e"  class="icon-wrapper  bg-opacity-10 text-danger me-3">
                                    <i class="bi bi-boxes"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">Notifications</h6>
                                    <small class="text-muted">{{ $unreadNotifications }} non lues</small>
                                </div>
                            </div>
                            <p class="card-text text-muted mb-3">
                                Créez, modifiez et gérez les inventaires de stock.
                            </p>
                            <a href="{{ route('stocks.index')}}" style="background-color: #d6de5e" class="btn w-100">
                                <i class="bi bi-boxes me-2"></i>Gérer
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Surveillance -->
        <div class="tab-pane fade" id="monitoring">
            <div class="row g-4">
                <div class="col-md-8">
                    <div class="card admin-card">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">
                                <i class="bi bi-activity text-primary me-2"></i>
                                Performance du Système
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-3 text-center">
                                    <div class="display-6 text-primary">{{ $systemMetrics['cpu'] }}%</div>
                                    <small class="text-muted">CPU</small>
                                    <div class="progress mt-2" style="height: 4px;">
                                        <div class="progress-bar bg-primary" style="width: {{ $systemMetrics['cpu'] }}%"></div>
                                    </div>
                                </div>
                                <div class="col-md-3 text-center">
                                    <div class="display-6 text-success">{{ $systemMetrics['memory']['percent'] }}%</div>
                                    <small class="text-muted">Mémoire</small>
                                    <div class="progress mt-2" style="height: 4px;">
                                        <div class="progress-bar bg-success" style="width: {{ $systemMetrics['memory']['percent'] }}%"></div>
                                    </div>
                                </div>
                                <div class="col-md-3 text-center">
                                    <div class="display-6 text-warning">{{ $systemMetrics['disk']['percent'] }}%</div>
                                    <small class="text-muted">Disque</small>
                                    <div class="progress mt-2" style="height: 4px;">
                                        <div class="progress-bar bg-warning" style="width: {{ $systemMetrics['disk']['percent'] }}%"></div>
                                    </div>
                                </div>
                                <div class="col-md-3 text-center">
                                    <div class="display-6 text-info">{{ $systemMetrics['connections'] }}</div>
                                    <small class="text-muted">Connexions</small>
                                </div>
                            </div>
                            @if($systemHealth >= 95)
                                <div class="alert alert-success" role="alert">
                                    <i class="bi bi-check-circle me-2"></i>
                                    Tous les services fonctionnent normalement
                                </div>
                            @elseif($systemHealth >= 80)
                                <div class="alert alert-warning" role="alert">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    Performances dégradées détectées
                                </div>
                            @else
                                <div class="alert alert-danger" role="alert">
                                    <i class="bi bi-x-circle me-2"></i>
                                    Problèmes critiques détectés
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card admin-card">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">
                                <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                                Alertes Récentes
                            </h6>
                        </div>
                        <div class="card-body recent-activity">
                            @forelse($recentAlerts as $alert)
                                <div class="activity-item">
                                    <div class="d-flex justify-content-between">
                                        <small class="fw-semibold">{{ $alert['message'] }}</small>
                                        <small class="text-muted">{{ $alert['created_at'] }}</small>
                                    </div>
                                    <small class="text-muted">{{ $alert['details'] }}</small>
                                </div>
                            @empty
                                <div class="text-center text-muted">
                                    <i class="bi bi-check-circle" style="font-size: 2rem;"></i>
                                    <p class="mt-2">Aucune alerte récente</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modales -->
<!-- Modal de confirmation pour déconnexion -->
<div class="modal fade" id="logoutModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                    Confirmation de déconnexion
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir déconnecter cet utilisateur ? Cette action fermera immédiatement sa session.</p>
                <div class="alert alert-warning">
                    <i class="bi bi-info-circle me-2"></i>
                    L'utilisateur sera notifié de cette déconnexion forcée.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" onclick="confirmLogout()">
                    <i class="bi bi-power me-2"></i>Déconnecter
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de détails de session -->
<div class="modal fade" id="sessionDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-person-circle text-primary me-2"></i>
                    Détails de la session
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="sessionDetailsContent">
                <!-- Le contenu sera chargé dynamiquement -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-warning" onclick="suspendCurrentSession()">
                    <i class="bi bi-pause-circle me-2"></i>Suspendre session
                </button>
                <button type="button" class="btn btn-danger" onclick="logoutCurrentSession()">
                    <i class="bi bi-power me-2"></i>Déconnecter
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('styles')
<style>
    :root {
        --primary-color: #4f46e5;
        --secondary-color: #6b7280;
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --info-color: #3b82f6;
    }

    body {
        background-color: #f8fafc;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .stat-card {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15);
    }

    .admin-card {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .admin-card:hover {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }

    .icon-wrapper {
        width: 60px;
        height: 60px;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .online-indicator {
        width: 8px;
        height: 8px;
        background-color: var(--success-color);
        border-radius: 50%;
        display: inline-block;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }

    .session-table {
        font-size: 0.9rem;
    }

    .badge-status {
        padding: 0.5rem 0.8rem;
        border-radius: 0.5rem;
        font-weight: 500;
        font-size: 0.75rem;
    }

    .quick-action-btn {
        border-radius: 0.75rem;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .quick-action-btn:hover {
        transform: scale(1.02);
    }

    .recent-activity {
        max-height: 400px;
        overflow-y: auto;
    }

    .activity-item {
        border-left: 3px solid var(--primary-color);
        padding-left: 1rem;
        margin-bottom: 1rem;
    }

    .system-health {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .nav-pills .nav-link {
        border-radius: 0.75rem;
        padding: 0.75rem 1.25rem;
        margin-right: 0.5rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .nav-pills .nav-link.active {
        background-color: var(--primary-color);
    }

    .table-responsive {
        border-radius: 0.75rem;
        overflow: hidden;
    }

    .session-row {
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .session-row:hover {
        background-color: rgba(79, 70, 229, 0.05);
    }

    .avatar-sm {
        width: 40px;
        height: 40px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    /* Animation pour les cartes */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .admin-card {
        animation: fadeInUp 0.6s ease-out;
    }

    /* Responsive amélioré */
    @media (max-width: 768px) {
        .stat-card {
            margin-bottom: 1rem;
        }

        .icon-wrapper {
            width: 50px;
            height: 50px;
            font-size: 1.25rem;
        }

        .table-responsive {
            font-size: 0.8rem;
        }

        .nav-pills .nav-link {
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
        }
    }

    /* Amélioration de l'accessibilité */
    .btn:focus,
    .nav-link:focus {
        box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.5);
    }
</style>
@endsection

@section('scripts')
<script>
    // Variables globales
    let selectedUserId = null;
    let selectedSessionId = null;

    // Token CSRF pour les requêtes AJAX
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Fonctions de gestion des sessions
    function logoutUser(userId) {
        selectedUserId = userId;
        const modal = new bootstrap.Modal(document.getElementById('logoutModal'));
        modal.show();
    }

    function confirmLogout() {
        if (selectedUserId) {
            fetch(`/admin/sessions/logout/${selectedUserId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('success', 'Utilisateur déconnecté avec succès');
                    refreshSessions();
                } else {
                    showToast('danger', 'Erreur lors de la déconnexion');
                }
            })
            .catch(error => {
                showToast('danger', 'Erreur de connexion');
            });

            // Fermer la modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('logoutModal'));
            modal.hide();
        }
    }

    function logoutAllSessions() {
        if (confirm('Êtes-vous sûr de vouloir déconnecter tous les utilisateurs ? Cette action fermera immédiatement toutes les sessions actives.')) {
            fetch('/admin/sessions/logout-all', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('warning', `${data.count} sessions ont été fermées`);
                    refreshSessions();
                } else {
                    showToast('danger', 'Erreur lors de la déconnexion');
                }
            });
        }
    }

    function suspendSession(sessionId) {
        fetch(`/admin/sessions/suspend/${sessionId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('warning', 'Session suspendue avec succès');
                refreshSessions();
            } else {
                showToast('danger', 'Erreur lors de la suspension');
            }
        });
    }

    function suspendInactiveSessions() {
        if (confirm('Suspendre toutes les sessions inactives depuis plus de 15 minutes ?')) {
            fetch('/admin/sessions/suspend-inactive', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('warning', `${data.count} sessions inactives ont été suspendues`);
                    refreshSessions();
                } else {
                    showToast('danger', 'Erreur lors de la suspension');
                }
            });
        }
    }

    function refreshSessions() {
        // Recharger la page ou actualiser le contenu via AJAX
        location.reload();
    }


    function suspendCurrentSession() {
        if (selectedSessionId) {
            suspendSession(selectedSessionId);
            const modal = bootstrap.Modal.getInstance(document.getElementById('sessionDetailsModal'));
            modal.hide();
        }
    }

    function logoutCurrentSession() {
        if (selectedUserId) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('sessionDetailsModal'));
            modal.hide();
            logoutUser(selectedUserId);
        }
    }



    // Filtrage des journaux
    document.getElementById('logLevelFilter')?.addEventListener('change', function() {
        const selectedLevel = this.value.toLowerCase();
        const rows = document.querySelectorAll('#logs tbody tr[data-level]');

        rows.forEach(row => {
            if (selectedLevel === '' || row.dataset.level === selectedLevel) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Actualisation automatique des données toutes les 30 secondes
    setInterval(function() {
        updateStats();
    }, 30000);

    function updateStats() {
        fetch('/admin/stats/update')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mettre à jour les statistiques sans recharger la page
                updateStatCards(data.stats);
            }
        })
        .catch(() => {
            // Gérer les erreurs silencieusement
        });
    }

    function updateStatCards(stats) {
        // Mettre à jour les cartes de statistiques
        document.querySelectorAll('.stat-card h3').forEach((element, index) => {
            switch(index) {
                case 0:
                    element.textContent = stats.totalUsers || element.textContent;
                    break;
                case 1:
                    element.textContent = stats.activeSessions || element.textContent;
                    break;
                case 2:
                    element.textContent = stats.systemHealth + '%' || element.textContent;
                    break;
                case 3:
                    element.textContent = stats.activeAlerts || element.textContent;
                    break;
            }
        });
    }


</script>
@endsection
