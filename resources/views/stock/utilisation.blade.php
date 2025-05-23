@extends('layouts.app')

@section('title', 'Tableau de Bord Stock')

@section('content')
<div class="min-vh-100">
    {{-- <!-- Header -->
    <div class="bg-white shadow-sm border-bottom">
        <div class="container-fluid py-4">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0 text-primary fw-bold">
                    <i class="fas fa-chart-line me-2"></i>
                    Tableau de Bord Stock
                </h1>
                <div class="text-end">
                    <p class="text-muted mb-1 small">Dernière mise à jour</p>
                    <p class="fw-semibold text-dark mb-0">{{ now()->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="container-fluid ">
        <!-- Statistiques principales -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card h-100 shadow-sm border-start border-primary border-4 hover-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                                    <i class="fas fa-boxes text-primary fs-4"></i>
                                </div>
                            </div>
                            <div class="ms-3">
                                <p class="text-muted mb-1 small fw-medium">Total Produits</p>
                                <h4 class="mb-0 fw-bold text-dark">{{ number_format($totalStock) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card h-100 shadow-sm border-start border-warning border-4 hover-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                                    <i class="fas fa-exclamation-triangle text-warning fs-4"></i>
                                </div>
                            </div>
                            <div class="ms-3">
                                <p class="text-muted mb-1 small fw-medium">Stock Faible</p>
                                <h4 class="mb-0 fw-bold text-warning">{{ number_format($stockFaible) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card h-100 shadow-sm border-start border-danger border-4 hover-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-danger bg-opacity-10 rounded-3 p-3">
                                    <i class="fas fa-clock text-danger fs-4"></i>
                                </div>
                            </div>
                            <div class="ms-3">
                                <p class="text-muted mb-1 small fw-medium">Expire Bientôt</p>
                                <h4 class="mb-0 fw-bold text-danger">{{ number_format($stockExpire) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="card h-100 shadow-sm border-start border-success border-4 hover-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-success bg-opacity-10 rounded-3 p-3">
                                    <i class="fas fa-dollar-sign text-success fs-4"></i>
                                </div>
                            </div>
                            <div class="ms-3">
                                <p class="text-muted mb-1 small fw-medium">Valeur Totale</p>
                                <h4 class="mb-0 fw-bold text-success">{{ number_format($valeurTotale, 0, ',', ' ') }} BIF</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphiques -->
        <div class="row g-4 mb-4">
            <!-- Évolution du Stock -->
            <div class="col-lg-6">
                <div class="card shadow-sm hover-card h-100">
                    <div class="card-header bg-transparent border-0 pb-0">
                        <h5 class="card-title d-flex align-items-center mb-0">
                            <div class="bg-primary bg-opacity-10 rounded-2 p-2 me-3">
                                <i class="fas fa-chart-bar text-primary"></i>
                            </div>
                            Évolution Stock (6 derniers mois)
                        </h5>
                    </div>
                    <div class="card-body" style="position: relative; height:420px;">
                        <canvas id="evolutionChart" ></canvas>
                    </div>
                </div>
            </div>

            <!-- Types de Mouvements -->
            <div class="col-lg-6">
                <div class="card shadow-sm hover-card h-100">
                    <div class="card-header bg-transparent border-0 pb-0">
                        <h5 class="card-title d-flex align-items-center mb-0">
                            <div class="bg-info bg-opacity-10 rounded-2 p-2 me-3">
                                <i class="fas fa-chart-pie text-info"></i>
                            </div>
                            Types de Mouvements (30 derniers jours)
                        </h5>
                    </div>
                    <div class="card-body" style="position: relative; height:420px;">
                        <canvas id="mouvementsChart" ></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <!-- Produits Populaires -->
            <div class="col-lg-6">
                <div class="card shadow-sm hover-card h-100">
                    <div class="card-header bg-transparent border-0 pb-0">
                        <h5 class="card-title d-flex align-items-center mb-0">
                            <div class="bg-success bg-opacity-10 rounded-2 p-2 me-3">
                                <i class="fas fa-trending-up text-success"></i>
                            </div>
                            Top 10 Produits les Plus Utilisés
                        </h5>
                    </div>
                    <div class="card-body" style="position: relative; height:420px;">
                        <canvas id="produitsChart" ></canvas>
                    </div>
                </div>
            </div>

            <!-- Stock par Catégorie -->
            <div class="col-lg-6">
                <div class="card shadow-sm hover-card h-100">
                    <div class="card-header bg-transparent border-0 pb-0">
                        <h5 class="card-title d-flex align-items-center mb-0">
                            <div class="bg-warning bg-opacity-10 rounded-2 p-2 me-3">
                                <i class="fas fa-layer-group text-warning"></i>
                            </div>
                            Valeur Stock par Catégorie
                        </h5>
                    </div>
                    <div class="card-body" style="position: relative; height:420px;">
                        <canvas id="categoriesChart" ></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alertes et Mouvements Récents -->
        <div class="row g-4">
            <!-- Alertes -->
            <div class="col-lg-8">
                <div class="card shadow-sm hover-card h-100">
                    <div class="card-header bg-transparent border-0 pb-0">
                        <h5 class="card-title d-flex align-items-center mb-0">
                            <div class="bg-danger bg-opacity-10 rounded-2 p-2 me-3">
                                <i class="fas fa-bell text-danger"></i>
                            </div>
                            Alertes Stock
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(count($alertes['stock_faible']) > 0)
                            <div class="alert alert-warning border-start border-warning border-4 mb-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-triangle text-warning"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="alert-heading">Stock Faible ({{ count($alertes['stock_faible']) }} produits)</h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-borderless mb-0">
                                                @foreach($alertes['stock_faible']->take(5) as $stock)
                                                    <tr>
                                                        <td class="ps-0">
                                                            <strong>{{ $stock->product_name }}</strong>
                                                        </td>
                                                        <td class="text-end pe-0">
                                                            <span class="badge bg-warning">
                                                                {{ $stock->quantite }}/{{ $stock->quantite_alert }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                        @if(count($alertes['stock_faible']) > 5)
                                            <small class="text-muted">... et {{ count($alertes['stock_faible']) - 5 }} autres</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(count($alertes['expiration_proche']) > 0)
                            <div class="alert alert-danger border-start border-danger border-4 mb-0">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-clock text-danger"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h6 class="alert-heading">Expiration Proche ({{ count($alertes['expiration_proche']) }} produits)</h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-borderless mb-0">
                                                @foreach($alertes['expiration_proche']->take(5) as $stock)
                                                    <tr>
                                                        <td class="ps-0">
                                                            <strong>{{ $stock->product_name }}</strong>
                                                        </td>
                                                        <td class="text-end pe-0">
                                                            <span class="badge bg-danger">
                                                                {{ $stock->date_expiration->format('d/m/Y') }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                        @if(count($alertes['expiration_proche']) > 5)
                                            <small class="text-muted">... et {{ count($alertes['expiration_proche']) - 5 }} autres</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Mouvements Récents -->
            <div class="col-lg-4">
                <div class="card shadow-sm hover-card h-100">
                    <div class="card-header bg-transparent border-0 pb-0">
                        <h5 class="card-title d-flex align-items-center mb-0">
                            <div class="bg-info bg-opacity-10 rounded-2 p-2 me-3">
                                <i class="fas fa-history text-info"></i>
                            </div>
                            Mouvements Récents
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-hover mb-0">
                                <tbody>
                                    @foreach($mouvementsRecents as $mouvement)
                                        <tr>
                                            <td class="py-3">
                                                <div>
                                                    <strong class="text-dark">{{ Str::limit($mouvement->item_designation, 25) }}</strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="fas fa-clock me-1"></i>
                                                        {{ Carbon\Carbon::parse($mouvement->item_movement_date)->format('d/m/Y H:i') }}
                                                    </small>
                                                </div>
                                            </td>
                                            <td class="text-end py-3">
                                                <span class="badge fs-6 {{ str_starts_with($mouvement->item_movement_type, 'E') ? 'bg-success' : 'bg-danger' }}">
                                                    {{ str_starts_with($mouvement->item_movement_type, 'E') ? '+' : '-' }}{{ $mouvement->item_quantity }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hover-card {
    transition: all 0.3s ease;
}

.hover-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.table-responsive {
    border-radius: 0.375rem;
}

.alert {
    border-radius: 0.5rem;
}

.card {
    border-radius: 0.75rem;
    border: none;
}

.badge {
    font-weight: 500;
}
</style>
@endsection

@push('scripts')
<!-- Chart.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuration globale des graphiques
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#6B7280';

    // Chart 1: Évolution du Stock
    const evolutionCtx = document.getElementById('evolutionChart').getContext('2d');
    new Chart(evolutionCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(collect($evolutionStock)->pluck('mois')) !!},
            datasets: [{
                label: 'Entrées',
                data: {!! json_encode(collect($evolutionStock)->pluck('entrees')) !!},
                borderColor: '#198754',
                backgroundColor: 'rgba(25, 135, 84, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Sorties',
                data: {!! json_encode(collect($evolutionStock)->pluck('sorties')) !!},
                borderColor: '#DC3545',
                backgroundColor: 'rgba(220, 53, 69, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                x: {
                    grid: {
                        display: false
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
                    '#0D6EFD', '#DC3545', '#198754', '#FFC107',
                    '#6F42C1', '#0DCAF0', '#20C997', '#FD7E14',
                    '#D63384', '#6C757D'
                ],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 15
                    }
                }
            }
        }
    });

    // Chart 3: Produits Populaires
    const produitsCtx = document.getElementById('produitsChart').getContext('2d');
    new Chart(produitsCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(collect($produitsPopulaires)->pluck('item_designation')->map(function($item) { return substr($item, 0, 20) . (strlen($item) > 20 ? '...' : ''); })) !!},
            datasets: [{
                label: 'Quantité Utilisée',
                data: {!! json_encode(collect($produitsPopulaires)->pluck('total_quantity')) !!},
                backgroundColor: 'rgba(25, 135, 84, 0.8)',
                borderColor: '#198754',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                x: {
                    ticks: {
                        maxRotation: 45
                    },
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
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
                    'rgba(13, 110, 253, 0.6)',
                    'rgba(220, 53, 69, 0.6)',
                    'rgba(25, 135, 84, 0.6)',
                    'rgba(255, 193, 7, 0.6)',
                    'rgba(111, 66, 193, 0.6)',
                    'rgba(13, 202, 240, 0.6)'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 15
                    }
                }
            }
        }
    });
});
</script>
@endpush
