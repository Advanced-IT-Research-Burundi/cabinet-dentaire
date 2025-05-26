@extends('layouts.app')

@section('title', 'Détails de la Caisse')

@section('content')
<div class="container-fluid px-4">

    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="bi bi-cash-register me-2 text-primary"></i>
                Détails de la Caisse
            </h1>
            <p class="text-muted mb-0">{{ $caisse->name }}</p>
        </div>
        <div class="btn-group" role="group">
            <a href="{{ route('caisses.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Retour
            </a>
            @if($caisse->montant > 0)
                <!-- Button to trigger modal -->
                <div class="btn btn-outline d-flex">
                    Retrait
                </div>
                <!-- Include modal -->
                @include('caisse.withdraw-modal', ['caisse' => $caisse])
            @endif

        </div>
    </div>

    <div class="row">
        <!-- Informations Générales -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-info-circle me-2"></i>Informations Générales
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item mb-4">
                                <label class="fw-bold text-muted small text-uppercase">Type de Caisse</label>
                                <div class="mt-1">
                                    @if($caisse->type == 'income')
                                        <span class="badge bg-success-soft text-success px-3 py-2 rounded-pill fs-6">
                                            <i class="bi bi-arrow-up me-1"></i>Revenu
                                        </span>
                                    @elseif($caisse->type == 'expense')
                                        <span class="badge bg-danger-soft text-danger px-3 py-2 rounded-pill fs-6">
                                            <i class="bi bi-arrow-down me-1"></i>Dépense
                                        </span>
                                    @else
                                        <span class="badge bg-warning-soft text-warning px-3 py-2 rounded-pill fs-6">
                                            <i class="bi bi-arrow-right me-1"></i>Transfert
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="info-item mb-4">
                                <label class="fw-bold text-muted small text-uppercase">Nom de la Caisse</label>
                                <div class="mt-1 fs-5 fw-semibold text-dark">{{ $caisse->name }}</div>
                            </div>

                            <div class="info-item mb-4">
                                <label class="fw-bold text-muted small text-uppercase">Date de Création</label>
                                <div class="mt-1">
                                    <i class="bi bi-calendar text-muted me-2"></i>
                                    <span class="fw-semibold">{{ $caisse->date->format('d/m/Y à H:i') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-item mb-4">
                                <label class="fw-bold text-muted small text-uppercase">Montant</label>
                                <div class="mt-1">
                                    <span class="fs-4 fw-bold text-primary">
                                        {{ number_format($caisse->montant, 0, ',', ' ') }}
                                        <small class="text-muted fs-6">FBU</small>
                                    </span>
                                </div>
                            </div>

                            <div class="info-item mb-4">
                                <label class="fw-bold text-muted small text-uppercase">Statut</label>
                                <div class="mt-1">
                                    @switch($caisse->status)
                                        @case('completed')
                                            <span class="badge bg-success-soft text-success px-3 py-2 rounded-pill fs-6">
                                                <i class="bi bi-check-circle me-1"></i>Terminé
                                            </span>
                                            @break
                                        @case('pending')
                                            <span class="badge bg-warning-soft text-warning px-3 py-2 rounded-pill fs-6">
                                                <i class="bi bi-clock me-1"></i>En attente
                                            </span>
                                            @break
                                        @case('active')
                                            <span class="badge bg-info-soft text-info px-3 py-2 rounded-pill fs-6">
                                                <i class="bi bi-play-circle me-1"></i>Actif
                                            </span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary-soft text-secondary px-3 py-2 rounded-pill fs-6">
                                                {{ $caisse->status }}
                                            </span>
                                    @endswitch
                                </div>
                            </div>

                            <div class="info-item mb-4">
                                <label class="fw-bold text-muted small text-uppercase">Créé par</label>
                                <div class="mt-1 d-flex align-items-center">
                                    <div class="avatar avatar-sm rounded-circle bg-primary text-white me-2">
                                        {{ substr($caisse->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $caisse->user->name }}</div>
                                        <small class="text-muted">{{ $caisse->user->email }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($caisse->description)
                    <div class="mt-4 pt-4 border-top">
                        <label class="fw-bold text-muted small text-uppercase">Description</label>
                        <div class="mt-2 p-3 bg-light rounded">
                            <p class="mb-0 text-dark">{{ $caisse->description }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Détails des Transactions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-list-alt me-2"></i>Détails des Transactions
                    </h6>

                </div>
                <div class="card-body">
                    @if($caisse->caisseDetails && $caisse->caisseDetails->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Type</th>
                                        <th>Description</th>
                                        <th>Facture N°</th>
                                        {{-- <th>Prix Unit.</th>
                                        <th>Quantité</th> --}}
                                        <th>Total</th>
                                        <th>Statut</th>
                                        <th>Utilisateur</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($caisse->caisseDetails as $detail)
                                    <tr>
                                        @php
                                                $pos = strpos($detail->type, 'No ');
                                                $invoiceId = $pos !== false ? substr($detail->type, $pos + 3) : '0';
                                            @endphp
                                        <td>
                                            <div class="mt-1">
                                                @if($invoiceId == 0)
                                                     <span class="badge bg-warning-soft text-warning px-3 py-2 rounded-pill fs-6">
                                                        <i class="bi bi-arrow-right me-1"></i>Transfert
                                                    </span>
                                                @else
                                                    @if($caisse->type == 'income')
                                                        <span class="badge bg-success-soft text-success px-3 py-2 rounded-pill fs-6">
                                                        <i class="bi bi-arrow-up me-1"></i>Revenu
                                                    </span>
                                                    @elseif($caisse->type == 'expense')
                                                        <span class="badge bg-danger-soft text-danger px-3 py-2 rounded-pill fs-6">
                                                            <i class="bi bi-arrow-down me-1"></i>Dépense
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning-soft text-warning px-3 py-2 rounded-pill fs-6">
                                                            <i class="bi bi-arrow-right me-1"></i>Transfert
                                                        </span>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $detail->description ?? '--' }}</div>
                                            {{-- @if($detail->remarque)
                                                <small class="text-muted">{{ Str::limit($detail->remarque, 50) }}</small>
                                            @endif --}}
                                        </td>
                                        {{-- <td>
                                            <span class="fw-semibold">{{ number_format($detail->price, 0, ',', ' ') }} FBU</span>
                                        </td> --}}
                                        {{-- <td>
                                            <span class="badge bg-light text-dark">{{ $detail->quantite ?? 1 }}</span>
                                        </td> --}}
                                        <td>
                                            <span class="fw-semibold">{{ $detail->type ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-primary">{{ number_format($detail->total, 0, ',', ' ') }} FBU</span>
                                        </td>
                                        <td>
                                            @switch($detail->status)
                                                @case('completed')
                                                    <span class="badge bg-success-soft text-success">
                                                        <i class="bi bi-check-circle me-1"></i>Terminé
                                                    </span>
                                                    @break
                                                @case('pending')
                                                    <span class="badge bg-warning-soft text-warning">
                                                        <i class="bi bi-clock me-1"></i>En attente
                                                    </span>
                                                    @break
                                                @case('active')
                                                    <span class="badge bg-info-soft text-info">
                                                        <i class="bi bi-play-circle me-1"></i>Actif
                                                    </span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary-soft text-secondary">
                                                        {{ $detail->status }}
                                                    </span>
                                            @endswitch
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-xs rounded-circle bg-secondary text-white me-2">
                                                    {{ substr($detail->user->name ?? 'U', 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="fw-semibold small">{{ $detail->user->name ?? 'Utilisateur' }}</div>
                                                    <small class="text-muted">{{ $detail->created_at->format('d/m/Y') }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">

                                            <div class="btn-group" role="group">
                                                @if($invoiceId && $invoiceId > 0)
                                                    <a href="{{ route('invoices.show', $invoiceId) }}" class="btn btn-sm btn-outline-primary"
                                                            title="Voir détails">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="4" class="text-end">Total :</th>
                                        <th class="text-primary">
                                            {{ number_format($caisse->caisseDetails->sum('total'), 0, ',', ' ') }} FBU
                                        </th>
                                        <th colspan="3"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-clipboard-x fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun détail de transaction</h5>
                            <p class="text-muted">Commencez par ajouter des détails à cette caisse</p>

                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">

            <!-- Statistiques -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-graph-up me-2"></i>Statistiques
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border-bottom pb-2">
                                <div class="h4 mb-0 text-primary">{{ $caisse->caisseDetails->count() }}</div>
                                <small class="text-muted">Transactions</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border-bottom pb-2">
                                <div class="h4 mb-0 text-success">{{ $caisse->caisseDetails->where('type', 'recette')->count() }}</div>
                                <small class="text-muted">Recettes</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border-bottom pb-2">
                                <div class="h4 mb-0 text-danger">{{ $caisse->caisseDetails->where('type', 'depense')->count() }}</div>
                                <small class="text-muted">Dépenses</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border-bottom pb-2">
                                <div class="h4 mb-0 text-info">{{ $caisse->caisseDetails->where('status', 'completed')->count() }}</div>
                                <small class="text-muted">Terminées</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historique -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-clock-history me-2"></i>Historique
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Caisse créée</h6>
                                <small class="text-muted">{{ $caisse->created_at->format('d/m/Y à H:i') }}</small>
                            </div>
                        </div>
                        @if($caisse->updated_at != $caisse->created_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Dernière modification</h6>
                                <small class="text-muted">{{ $caisse->updated_at->format('d/m/Y à H:i') }}</small>
                            </div>
                        </div>
                        @endif
                        @foreach($caisse->caisseDetails->take(3) as $detail)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-{{ $detail->type == 'recette' ? 'success' : 'danger' }}"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">{{ $detail->type == 'recette' ? 'Recette' : 'Dépense' }} ajoutée</h6>
                                <p class="mb-1 small">{{ Str::limit($detail->description, 30) }}</p>
                                <small class="text-muted">{{ $detail->created_at->format('d/m/Y à H:i') }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('styles')
<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.bg-success-soft {
    background-color: rgba(25, 135, 84, 0.1) !important;
}
.bg-danger-soft {
    background-color: rgba(220, 53, 69, 0.1) !important;
}
.bg-warning-soft {
    background-color: rgba(255, 193, 7, 0.1) !important;
}
.bg-info-soft {
    background-color: rgba(13, 202, 240, 0.1) !important;
}
.bg-secondary-soft {
    background-color: rgba(108, 117, 125, 0.1) !important;
}

.avatar {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 14px;
}

.avatar-xs {
    width: 24px;
    height: 24px;
    font-size: 12px;
}

.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 14px;
}

.timeline {
    position: relative;
    padding-left: 20px;
}

.timeline-item {
    position: relative;
    padding-bottom: 20px;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -16px;
    top: 20px;
    width: 2px;
    height: calc(100% - 12px);
    background-color: #e9ecef;
}

.timeline-marker {
    position: absolute;
    left: -20px;
    top: 4px;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #e9ecef;
}

.timeline-content {
    margin-left: 8px;
}

.info-item {
    position: relative;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.02);
}

.btn-group .btn {
    border-radius: 0.375rem !important;
    margin: 0 2px;
}

@media print {
    .btn, .dropdown, .modal {
        display: none !important;
    }

    .card {
        border: 1px solid #dee2e6 !important;
        box-shadow: none !important;
    }
}
</style>
@endpush
