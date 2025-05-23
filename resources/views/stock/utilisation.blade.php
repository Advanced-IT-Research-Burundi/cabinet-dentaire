@extends('layouts.app')

@section('title', 'Tableau de Bord Stock')

@section('content')
<div class="min-vh-100" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
    <!-- Header avec fond dégradé -->
    <div class="bg-white shadow-lg border-bottom" style="background: linear-gradient(90deg, #ffffff 0%, #f8fafc 100%);">
        <div class="container-xxl py-4">
            <div class="row align-items-center">
                <div class="col-md-8 mb-3 mb-md-0">
                    <h1 class="display-4 fw-semibold text-dark mb-2">
                        <span class="bg-gradient text-primary">
                            Tableau de Bord Stock
                        </span>
                    </h1>
                    <p class="fs-5 text-muted fw-normal">Vue d'ensemble de votre inventaire et des mouvements</p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="bg-light p-3 rounded-3 border border-primary border-opacity-25">
                        <p class="small fw-medium text-muted mb-1">Dernière mise à jour</p>
                        <p class="fs-5 fw-semibold text-dark mb-0">{{ now()->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-xxl py-4">
        <!-- Statistiques principales avec animations -->
        <div class="row g-4 mb-4">
            <!-- Total Produits -->
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 shadow-lg border-0 hover-card">
                    <div class="card-body p-4 border-start border-primary border-4 position-relative overflow-hidden">
                        <div class="bg-primary position-absolute top-0 end-0 opacity-10" style="width: 60px; height: 60px; transform: translate(20px, -20px); border-radius: 50%;"></div>
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-primary bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center hover-icon" style="width: 56px; height: 56px;">
                                    <svg class="text-primary" width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ms-3 flex-fill">
                                <p class="small fw-medium text-muted text-uppercase mb-1">Total Produits</p>
                                <p class="h3 fw-semibold text-dark mb-2">{{ number_format($totalStock) }}</p>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 85%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock Faible -->
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 shadow-lg border-0 hover-card">
                    <div class="card-body p-4 border-start border-warning border-4 position-relative overflow-hidden">
                        <div class="bg-warning position-absolute top-0 end-0 opacity-10" style="width: 60px; height: 60px; transform: translate(20px, -20px); border-radius: 50%;"></div>
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-warning bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center hover-icon" style="width: 56px; height: 56px;">
                                    <svg class="text-warning" width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ms-3 flex-fill">
                                <p class="small fw-medium text-muted text-uppercase mb-1">Stock Faible</p>
                                <p class="h3 fw-semibold text-warning mb-2">{{ number_format($stockFaible) }}</p>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 65%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Expire Bientôt -->
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 shadow-lg border-0 hover-card">
                    <div class="card-body p-4 border-start border-danger border-4 position-relative overflow-hidden">
                        <div class="bg-danger position-absolute top-0 end-0 opacity-10" style="width: 60px; height: 60px; transform: translate(20px, -20px); border-radius: 50%;"></div>
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-danger bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center hover-icon" style="width: 56px; height: 56px;">
                                    <svg class="text-danger" width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ms-3 flex-fill">
                                <p class="small fw-medium text-muted text-uppercase mb-1">Expire Bientôt</p>
                                <p class="h3 fw-semibold text-danger mb-2">{{ number_format($stockExpire) }}</p>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 45%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Valeur Totale -->
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 shadow-lg border-0 hover-card">
                    <div class="card-body p-4 border-start border-success border-4 position-relative overflow-hidden">
                        <div class="bg-success position-absolute top-0 end-0 opacity-10" style="width: 60px; height: 60px; transform: translate(20px, -20px); border-radius: 50%;"></div>
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-success bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center hover-icon" style="width: 56px; height: 56px;">
                                    <svg class="text-success" width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ms-3 flex-fill">
                                <p class="small fw-medium text-muted text-uppercase mb-1">Valeur Totale</p>
                                <p class="h3 fw-semibold text-success mb-2">{{ number_format($valeurTotale, 0, ',', ' ') }} BIF</p>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 90%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Grid avec design amélioré -->
        <div class="row g-4 mb-4">
            <!-- Chart 1: Évolution du Stock -->
            <div class="col-lg-6">
                <div class="card shadow-lg border-0 hover-card h-100">
                    <div class="card-header bg-light border-0 p-4">
                        <h3 class="h5 fw-semibold text-dark mb-0 d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center me-3 hover-icon" style="width: 40px; height: 40px;">
                                <svg class="text-primary" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            Évolution Stock (6 derniers mois)
                        </h3>
                    </div>
                    <div class="card-body p-4">
                        <canvas id="evolutionChart" class="w-100" height="250"></canvas>
                    </div>
                </div>
            </div>

            <!-- Chart 2: Types de Mouvements -->
            <div class="col-lg-6">
                <div class="card shadow-lg border-0 hover-card h-100">
                    <div class="card-header bg-light border-0 p-4">
                        <h3 class="h5 fw-semibold text-dark mb-0 d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center me-3 hover-icon" style="width: 40px; height: 40px;">
                                <svg class="text-info" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                                </svg>
                            </div>
                            Types de Mouvements (30 derniers jours)
                        </h3>
                    </div>
                    <div class="card-body p-4">
                        <canvas id="mouvementsChart" class="w-100" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <!-- Chart 3: Produits Populaires -->
            <div class="col-lg-6">
                <div class="card shadow-lg border-0 hover-card h-100">
                    <div class="card-header bg-light border-0 p-4">
                        <h3 class="h5 fw-semibold text-dark mb-0 d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center me-3 hover-icon" style="width: 40px; height: 40px;">
                                <svg class="text-success" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                            </div>
                            Top 10 Produits les Plus Utilisés
                        </h3>
                    </div>
                    <div class="card-body p-4">
                        <canvas id="produitsChart" class="w-100" height="250"></canvas>
                    </div>
                </div>
            </div>

            <!-- Chart 4: Stock par Catégorie -->
            <div class="col-lg-6">
                <div class="card shadow-lg border-0 hover-card h-100">
                    <div class="card-header bg-light border-0 p-4">
                        <h3 class="h5 fw-semibold text-dark mb-0 d-flex align-items-center">
                            <div class="bg-warning bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center me-3 hover-icon" style="width: 40px; height: 40px;">
                                <svg class="text-warning" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            Valeur Stock par Catégorie
                        </h3>
                    </div>
                    <div class="card-body p-4">
                        <canvas id="categoriesChart" class="w-100" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alertes et Mouvements Récents -->
        <div class="row g-4">
            <!-- Alertes -->
            <div class="col-lg-8">
                <div class="card shadow-lg border-0 hover-card h-100">
                    <div class="card-header bg-light border-0 p-4">
                        <h3 class="h5 fw-semibold text-dark mb-0 d-flex align-items-center">
                            <div class="bg-danger bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <svg class="text-danger" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                            </div>
                            Alertes Stock
                        </h3>
                    </div>

                    <div class="card-body p-4">
                        @if(count($alertes['stock_faible']) > 0)
                            <div class="alert alert-warning border-0 shadow-sm mb-4 position-relative overflow-hidden">
                                <div class="bg-warning position-absolute top-0 end-0 opacity-10" style="width: 80px; height: 80px; transform: translate(40px, -40px); border-radius: 50%;"></div>
                                <div class="d-flex position-relative">
                                    <div class="flex-shrink-0">
                                        <div class="bg-warning bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                            <svg class="text-warning" width="24" height="24" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ms-3 flex-fill">
                                        <h4 class="h6 fw-semibold text-warning mb-3">Stock Faible ({{ count($alertes['stock_faible']) }} produits)</h4>
                                        <div class="row g-2">
                                            @foreach($alertes['stock_faible']->take(3) as $stock)
                                                <div class="col-12">
                                                    <div class="d-flex align-items-center justify-content-between bg-white bg-opacity-75 rounded p-3">
                                                        <span class="fw-medium">{{ $stock->product_name }}</span>
                                                        <span class="badge bg-warning text-dark">
                                                            {{ $stock->quantite }}/{{ $stock->quantite_alert }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @endforeach
                                            @if(count($alertes['stock_faible']) > 3)
                                                <div class="col-12 text-center mt-2">
                                                    <small class="text-warning fw-medium">... et {{ count($alertes['stock_faible']) - 3 }} autres produits</small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(count($alertes['expiration_proche']) > 0)
                            <div class="alert alert-danger border-0 shadow-sm mb-4 position-relative overflow-hidden">
                                <div class="bg-danger position-absolute top-0 end-0 opacity-10" style="width: 80px; height: 80px; transform: translate(40px, -40px); border-radius: 50%;"></div>
                                <div class="d-flex position-relative">
                                    <div class="flex-shrink-0">
                                        <div class="bg-danger bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                            <svg class="text-danger" width="24" height="24" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ms-3 flex-fill">
                                        <h4 class="h6 fw-semibold text-danger mb-3">Expiration Proche ({{ count($alertes['expiration_proche']) }} produits)</h4>
                                        <div class="row g-2">
                                            @foreach($alertes['expiration_proche']->take(3) as $stock)
                                                <div class="col-12">
                                                    <div class="d-flex align-items-center justify-content-between bg-white bg-opacity-75 rounded p-3">
                                                        <span class="fw-medium">{{ $stock->product_name }}</span>
                                                        <span class="badge bg-danger">
                                                            {{ $stock->date_expiration->format('d/m/Y') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(count($alertes['stock_faible']) == 0 && count($alertes['expiration_proche']) == 0)
                            <div class="text-center py-5">
                                <div class="bg-success bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 64px; height: 64px;">
                                    <svg class="text-success" width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <h3 class="h6 fw-medium text-dark mb-2">Aucune alerte</h3>
                                <p class="text-muted mb-0">Votre stock est en bon état !</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Mouvements Récents -->
            <div class="col-lg-4">
                <div class="card shadow-lg border-0 hover-card h-100">
                    <div class="card-header bg-light border-0 p-4">
                        <h3 class="h5 fw-semibold text-dark mb-0 d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <svg class="text-info" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            Mouvements Récents
                        </h3>
                    </div>

                    <div class="card-body p-4">
                        <div class="overflow-auto custom-scrollbar" style="max-height: 400px;">
                            @foreach($mouvementsRecents as $mouvement)
                                <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded mb-3 hover-movement">
                                    <div class="flex-fill overflow-hidden me-3">
                                        <p class="small fw-medium text-dark mb-1 text-truncate">
                                            {{ $mouvement->item_designation }}
                                        </p>
                                        <p class="text-muted mb-0" style="font-size: 0.75rem;">
                                            {{ Carbon\Carbon::parse($mouvement->item_movement_date)->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <span class="badge {{ str_starts_with($mouvement->item_movement_type, 'E') ? 'bg-success' : 'bg-danger' }}">
                                            {{ str_starts_with($mouvement->item_movement_type, 'E') ? '+' : '-' }}{{ $mouvement->item_quantity }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Police plus douce et moins agressive */
    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        font-weight: 400;
        line-height: 1.6;
    }

    /* Ajustement des poids de police */
    .fw-bold {
        font-weight: 600 !important; /* Moins agressif que 700 */
    }

    .fw-semibold {
        font-weight: 500 !important; /* Plus doux que 600 */
    }

    .fw-medium {
        font-weight: 500 !important;
    }

    .fw-normal {
        font-weight: 400 !important;
    }

    /* Style de scrollbar personnalisé */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #3b82f6, #1d4ed8);
        border-radius: 3px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #2563eb, #1e40af);
    }

    /* Animations fluides */
    .hover-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .hover-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
    }

    .hover-icon {
        transition: all 0.3s ease;
    }

    .hover-card:hover .hover-icon {
        transform: scale(1.1);
    }

    .hover-movement {
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }

    .hover-movement:hover {
        border-color: #e5e7eb;
        transform: translateX(5px);
        background-color: #f9fafb !important;
    }

    /* Animation d'entrée */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .card {
        animation: fadeInUp 0.6s ease-out;
    }

    .text-dark {
        color: #1f2937 !important;
    }
</style>
@endpush

@push('scripts')
<script src=""></script>

<script>
document.addEventListener('DOMContentLoaded', function() {

    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#374151';
    Chart.defaults.plugins.legend.labels.usePointStyle = true;
    Chart.defaults.plugins.legend.labels.padding = 20;

    // Couleurs personnalisées
    const colors = {
        primary: ['#3B82F6', '#1D4ED8', '#1E40AF'],
        success: ['#10B981', '#059669', '#047857'],
        warning: ['#F59E0B', '#D97706', '#B45309'],
        danger: ['#EF4444', '#DC2626', '#B91C1C'],
        purple: ['#8B5CF6', '#7C3AED', '#6D28D9'],
        indigo: ['#6366F1', '#4F46E5', '#4338CA']
    };

    // Chart 1: Évolution du Stock
    const evolutionCtx = document.getElementById('evolutionChart').getContext('2d');
    const evolutionGradientEntry = evolutionCtx.createLinearGradient(0, 0, 0, 300);
    evolutionGradientEntry.addColorStop(0, 'rgba(16, 185, 129, 0.3)');
    evolutionGradientEntry.addColorStop(1, 'rgba(16, 185, 129, 0.05)');

    const evolutionGradientExit = evolutionCtx.createLinearGradient(0, 0, 0, 300);
    evolutionGradientExit.addColorStop(0, 'rgba(239, 68, 68, 0.3)');
    evolutionGradientExit.addColorStop(1, 'rgba(239, 68, 68, 0.05)');

    new Chart(evolutionCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(collect($evolutionStock)->pluck('mois')) !!},
            datasets: [{
                label: 'Entrées',
                data: {!! json_encode(collect($evolutionStock)->pluck('entrees')) !!},
                borderColor: colors.success[0],
                backgroundColor: evolutionGradientEntry,
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: colors.success[0],
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }, {
                label: 'Sorties',
                data: {!! json_encode(collect($evolutionStock)->pluck('sorties')) !!},
                borderColor: colors.danger[0],
                backgroundColor: evolutionGradientExit,
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: colors.danger[0],
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        font: {
                            size: 14,
                            weight: '600'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: '#374151',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: true,
                    titleFont: {
                        size: 14,
                        weight: '600'
                    },
                    bodyFont: {
                        size: 13
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#6B7280',
                        font: {
                            size: 12,
                            weight: '500'
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#F3F4F6',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#6B7280',
                        font: {
                            size: 12,
                            weight: '500'
                        }
                    }
                }
            }
        }
    });

    // Chart 2: Types de Mouvements
    const mouvementsCtx = document.getElementById('mouvementsChart').getContext('2d');
    new Chart(mouvementsCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(collect($typesMouvements)->pluck('type')) !!},
            datasets: [{
                data: {!! json_encode(collect($typesMouvements)->pluck('count')) !!},
                backgroundColor: [
                    colors.primary[0], colors.danger[0], colors.success[0], colors.warning[0],
                    colors.purple[0], colors.indigo[0], '#06B6D4', '#84CC16',
                    '#EC4899', '#6B7280'
                ],
                borderWidth: 0,
                hoverBorderWidth: 3,
                hoverBorderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: {
                            size: 12,
                            weight: '500'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: '#374151',
                    borderWidth: 1,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });

    // Chart 3: Produits Populaires
    const produitsCtx = document.getElementById('produitsChart').getContext('2d');
    const produitsGradient = produitsCtx.createLinearGradient(0, 0, 0, 300);
    produitsGradient.addColorStop(0, colors.success[0]);
    produitsGradient.addColorStop(1, colors.success[1]);

    new Chart(produitsCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(collect($produitsPopulaires)->pluck('item_designation')->map(function($item) { return substr($item, 0, 20) . (strlen($item) > 20 ? '...' : ''); })) !!},
            datasets: [{
                label: 'Quantité Utilisée',
                data: {!! json_encode(collect($produitsPopulaires)->pluck('total_quantity')) !!},
                backgroundColor: produitsGradient,
                borderColor: colors.success[0],
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
                hoverBackgroundColor: colors.success[1],
                hoverBorderColor: colors.success[2]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: '#374151',
                    borderWidth: 1,
                    cornerRadius: 8
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#6B7280',
                        font: {
                            size: 11,
                            weight: '500'
                        },
                        maxRotation: 45
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#F3F4F6',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#6B7280',
                        font: {
                            size: 12,
                            weight: '500'
                        }
                    }
                }
            }
        }
    });

    // Chart 4: Stock par Catégorie
    const categoriesCtx = document.getElementById('categoriesChart').getContext('2d');
    new Chart(categoriesCtx, {
        type: 'polarArea',
        data: {
            labels: {!! json_encode(collect($stockParCategorie)->pluck('categorie')) !!},
            datasets: [{
                data: {!! json_encode(collect($stockParCategorie)->pluck('valeur')) !!},
                backgroundColor: [
                    'rgba(59, 130, 246, 0.7)',
                    'rgba(239, 68, 68, 0.7)',
                    'rgba(16, 185, 129, 0.7)',
                    'rgba(245, 158, 11, 0.7)',
                    'rgba(139, 92, 246, 0.7)',
                    'rgba(6, 182, 212, 0.7)'
                ],
                borderColor: [
                    colors.primary[0],
                    colors.danger[0],
                    colors.success[0],
                    colors.warning[0],
                    colors.purple[0],
                    '#06B6D4'
                ],
                borderWidth: 2,
                hoverBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: {
                            size: 12,
                            weight: '500'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#ffffff',
                    bodyColor: '#ffffff',
                    borderColor: '#374151',
                    borderWidth: 1,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + new Intl.NumberFormat('fr-FR').format(context.parsed) + ' BIF';
                        }
                    }
                }
            },
            scales: {
                r: {
                    beginAtZero: true,
                    grid: {
                        color: '#F3F4F6'
                    },
                    ticks: {
                        color: '#6B7280',
                        font: {
                            size: 11
                        }
                    }
                }
            }
        }
    });

    // Animation d'entrée pour les cartes
    const cards = document.querySelectorAll('.group');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease-out';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
@endpush
