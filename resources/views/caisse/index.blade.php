@extends('layouts.app')

@section('title', 'Gestion des Caisses')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="bi bi-cash-register me-2 text-primary"></i>
                Gestion des Caisses
            </h1>
            <p class="text-muted mb-0">Gérez vos opérations de caisse et transactions</p>
        </div>
        <div>
            <a href="{{ route('caisses.create') }}" class="btn btn-primary btn-lg shadow-sm">
                <i class="bi bi-plus me-2"></i>Nouvelle Caisse
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row ">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Caisses
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $caisses->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-boxes fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Montant Total
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($caisses->sum('montant'), 0, ',', ' ') }} FBU
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Caisses Actives
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $caisses->where('status', 'active')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Caisse Principale
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $caissePrincipale ?? 'N/A' }} FBU
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card ">
        <div class="card-header  d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-filter me-2"></i>Filtres
            </h6>
            <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse">
                <i class="bi bi-chevron-down"></i>
            </button>
        </div>
        <div class="collapse" id="filtersCollapse">
            <div class="card-body">
                <form method="GET" action="{{ route('caisses.index') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Type</label>
                                <select name="type" class="form-select">
                                    <option value="">Tous les types</option>
                                    <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Revenu</option>
                                    <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Dépense</option>
                                    <option value="transfer" {{ request('type') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                </select>
                            </div>
                        </div>
                        {{-- <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Statut</label>
                                <select name="status" class="form-select">
                                    <option value="">Tous les statuts</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminé</option>
                                </select>
                            </div>
                        </div> --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Date de début</label>
                                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">Date de fin</label>
                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                            </div>
                        </div>
                        <div class="col-md-3 mt-4">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bi bi-search me-1"></i>Filtrer
                            </button>
                            <a href="{{ route('caisses.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-times me-1"></i>Réinitialiser
                            </a>
                        </div>

                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- Main Table -->
    <div class="card">
        <div class="card-header d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-list me-2"></i>Liste des Caisses
            </h6>
            {{-- <div class="dropdown">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-download me-1"></i>Exporter
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-file-excel me-2"></i>Excel</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-file-pdf me-2"></i>PDF</a></li>
                </ul>
            </div> --}}
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0">
                                <i class="bi bi-tag me-1"></i>Type
                            </th>
                            {{-- <th class="border-0">
                                <i class="bi bi-signature me-1"></i>Nom
                            </th> --}}
                            <th class="border-0">
                                <i class="bi bi-user me-1"></i>Utilisateur
                            </th>
                            <th class="border-0">
                                <i class="bi bi-calendar me-1"></i>Date
                            </th>
                            <th class="border-0">
                                <i class="bi bi-money-bill me-1"></i>Montant
                            </th>
                            <th class="border-0">
                                <i class="bi bi-info-circle me-1"></i>Statut
                            </th>
                            <th class="border-0 text-center">
                                <i class="bi bi-cogs me-1"></i>Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($caisses as $caisse)
                        <tr class="align-middle">
                            <td>
                                <div class="d-flex align-items-center">
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
                            </td>
                            {{-- <td>
                                <div class="fw-bold text-dark">{{ $caisse->name }}</div>
                                <small class="text-muted">{{ Str::limit($caisse->description, 30) }}</small>
                            </td> --}}
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm rounded-circle bg-primary text-white me-2">
                                        {{ substr($caisse->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $caisse->user->name }}</div>
                                        <small class="text-muted">{{ $caisse->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $caisse->date->format('d/m/Y') }}</div>
                                <small class="text-muted">{{ $caisse->created_at->format('H:i') }}</small>
                            </td>
                            <td>
                                <div class="fw-bold text-dark fs-6">
                                    {{ number_format($caisse->montant, 0, ',', ' ') }}
                                    <small class="text-muted">FBU</small>
                                </div>
                            </td>
                            <td>
                                @switch($caisse->status)
                                    @case('completed')
                                        <span class="badge bg-success-soft text-success px-3 py-2 rounded-pill">
                                            <i class="bi bi-check-circle me-1"></i>Terminé
                                        </span>
                                        @break
                                    @case('pending')
                                        <span class="badge bg-warning-soft text-warning px-3 py-2 rounded-pill">
                                            <i class="bi bi-clock me-1"></i>En attente
                                        </span>
                                        @break
                                    @case('active')
                                        <span class="badge bg-info-soft text-info px-3 py-2 rounded-pill">
                                            <i class="bi bi-play-circle me-1"></i>Actif
                                        </span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary-soft text-secondary px-3 py-2 rounded-pill">
                                            <i class="bi bi-question-circle me-1"></i>{{ $caisse->status }}
                                        </span>
                                @endswitch
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('caisses.show', $caisse->id) }}"
                                       class="btn btn-sm btn-outline-primary"
                                       data-bs-toggle="tooltip"
                                       title="Voir les détails">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    @if($caisse->montant > 0)
                                        @include('caisse.withdraw-modal', ['caisse' => $caisse])
                                    @endif
                                    {{-- <button type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal{{ $caisse->id }}"
                                            title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </button> --}}
                                </div>

                                <!-- Modal de suppression -->
                                <div class="modal fade" id="deleteModal{{ $caisse->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirmer la suppression</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                Êtes-vous sûr de vouloir supprimer la caisse "{{ $caisse->name }}" ?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <form method="POST" action="{{ route('caisses.destroy', $caisse->id) }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Supprimer</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-inbox fa-3x mb-3"></i>
                                    <h5>Aucune caisse trouvée</h5>
                                    <p>Commencez par créer votre première caisse</p>
                                    <a href="{{ route('caisses.create') }}" class="btn btn-primary">
                                        <i class="bi bi-plus me-2"></i>Créer une caisse
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($caisses->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $caisses->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

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

.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.02);
}

.btn-group .btn {
    border-radius: 0.375rem !important;
    margin: 0 2px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection
