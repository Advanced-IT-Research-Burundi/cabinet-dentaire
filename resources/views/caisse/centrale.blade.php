@extends('layouts.app')

@section('title', 'Caisse Centrale')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="bi bi-bank me-2 text-primary"></i>Caisse Centrale
            </h1>
            <p class="text-muted mb-0">Opérations bancaires et collecte des caisses utilisateurs</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('caisses.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Caisses
            </a>
            <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#operationModal">
                <i class="bi bi-plus-circle me-2"></i>Nouvelle opération
            </button>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Stats -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Solde actuel</div>
                    <div class="h4 mb-0 font-weight-bold">{{ number_format($centrale->montant, 0, ',', ' ') }} FBU</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Entrées du mois</div>
                    <div class="h5 mb-0 font-weight-bold">{{ number_format($stats['entrees_mois'], 0, ',', ' ') }} FBU</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Sorties du mois</div>
                    <div class="h5 mb-0 font-weight-bold">{{ number_format($stats['sorties_mois'], 0, ',', ' ') }} FBU</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Collectes du mois</div>
                    <div class="h5 mb-0 font-weight-bold">{{ number_format($stats['collectes_mois'], 0, ',', ' ') }} FBU</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Opérations -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary mb-3">
                        <i class="bi bi-list-ul me-2"></i>Historique des opérations
                    </h6>
                    <form method="GET" action="{{ route('caisse-centrale.index') }}" class="row g-2">
                        <div class="col-md-3">
                            <select name="operation_type" class="form-select form-select-sm">
                                <option value="">Toutes les opérations</option>
                                <option value="{{ COLLECTE_CAISSE }}" @selected(request('operation_type') == COLLECTE_CAISSE)>Collecte caisse utilisateur</option>
                                @foreach(OPERATIONS_CAISSE_CENTRALE as $code => $op)
                                    <option value="{{ $code }}" @selected(request('operation_type') == $code)>{{ $op['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="sens" class="form-select form-select-sm">
                                <option value="">Entrées + sorties</option>
                                <option value="entree" @selected(request('sens') == 'entree')>Entrées</option>
                                <option value="sortie" @selected(request('sens') == 'sortie')>Sorties</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}" title="Du">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}" title="Au">
                        </div>
                        <div class="col-md-3 d-flex gap-1">
                            <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-search"></i> Filtrer</button>
                            <a href="{{ route('caisse-centrale.index') }}" class="btn btn-sm btn-outline-secondary">Réinitialiser</a>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Opération</th>
                                    <th>Détails</th>
                                    <th class="text-end">Montant</th>
                                    <th>Par</th>
                                    <th class="text-center">Justificatif</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($operations as $op)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ ($op->date_operation ?? $op->created_at)->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $op->created_at->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        @if($op->total >= 0)
                                            <span class="badge bg-success-soft text-success px-3 py-2 rounded-pill">
                                                <i class="bi bi-arrow-down-left me-1"></i>{{ $op->operation_label }}
                                            </span>
                                        @else
                                            <span class="badge bg-danger-soft text-danger px-3 py-2 rounded-pill">
                                                <i class="bi bi-arrow-up-right me-1"></i>{{ $op->operation_label }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($op->sourceCaisse)
                                            <div class="small">Caisse de <strong>{{ $op->sourceCaisse->user->name ?? '--' }}</strong></div>
                                        @endif
                                        @if($op->banque)
                                            <div class="small"><i class="bi bi-bank me-1"></i>{{ $op->banque }}</div>
                                        @endif
                                        @if($op->reference)
                                            <div class="small text-muted">Réf : {{ $op->reference }}</div>
                                        @endif
                                        @if($op->description)
                                            <div class="small text-muted">{{ Str::limit($op->description, 60) }}</div>
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold {{ $op->total >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $op->total >= 0 ? '+' : '' }}{{ number_format($op->total, 0, ',', ' ') }} FBU
                                    </td>
                                    <td class="small">{{ $op->user->name ?? '--' }}</td>
                                    <td class="text-center">
                                        @if($op->justificatif)
                                            <a href="{{ route('caisse-centrale.justificatif.show', $op->id) }}" target="_blank"
                                               class="btn btn-sm btn-outline-secondary" title="Voir la pièce justificative">
                                                <i class="bi bi-paperclip"></i>
                                            </a>
                                        @endif
                                        <button type="button" class="btn btn-sm btn-outline-primary btn-attach"
                                                data-action="{{ route('caisse-centrale.justificatif.store', $op->id) }}"
                                                data-bs-toggle="modal" data-bs-target="#attachModal"
                                                title="{{ $op->justificatif ? 'Remplacer' : 'Ajouter' }} la pièce justificative">
                                            <i class="bi bi-upload"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        Aucune opération enregistrée
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($operations->hasPages())
                        <div class="d-flex justify-content-center mt-3">{{ $operations->links() }}</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Caisses utilisateurs à collecter -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-people me-2"></i>Caisses utilisateurs
                    </h6>
                    <small class="text-muted">Diminuer le montant d'une caisse et le transférer ici</small>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse($caissesUtilisateurs as $caisse)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ route('caisses.show', $caisse->id) }}" class="fw-semibold text-decoration-none">
                                    {{ $caisse->user->name ?? '--' }}
                                </a>
                                <div class="small text-muted">{{ number_format($caisse->montant, 0, ',', ' ') }} FBU</div>
                            </div>
                            @include('caisse.withdraw-modal', ['caisse' => $caisse])
                        </li>
                    @empty
                        <li class="list-group-item text-center text-muted py-4">Aucune caisse avec un solde positif</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal : nouvelle opération bancaire -->
<div class="modal fade" id="operationModal" tabindex="-1" aria-labelledby="operationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('caisse-centrale.operations.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="operationModalLabel">
                        <i class="bi bi-bank me-2"></i>Nouvelle opération bancaire
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-light border small">
                        Solde disponible : <strong>{{ number_format($centrale->montant, 0, ',', ' ') }} FBU</strong>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Type d'opération <span class="text-danger">*</span></label>
                            <select name="operation_type" class="form-select" required>
                                <option value="">Sélectionner...</option>
                                <optgroup label="Entrées (augmentent la caisse)">
                                    @foreach(OPERATIONS_CAISSE_CENTRALE as $code => $op)
                                        @if($op['sens'] === 'entree')
                                            <option value="{{ $code }}" @selected(old('operation_type') == $code)>{{ $op['label'] }}</option>
                                        @endif
                                    @endforeach
                                </optgroup>
                                <optgroup label="Sorties (diminuent la caisse)">
                                    @foreach(OPERATIONS_CAISSE_CENTRALE as $code => $op)
                                        @if($op['sens'] === 'sortie')
                                            <option value="{{ $code }}" @selected(old('operation_type') == $code)>{{ $op['label'] }}</option>
                                        @endif
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Montant (FBU) <span class="text-danger">*</span></label>
                            <input type="number" name="montant" class="form-control" step="0.01" min="0.01" value="{{ old('montant') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date de l'opération <span class="text-danger">*</span></label>
                            <input type="date" name="date_operation" class="form-control" value="{{ old('date_operation', now()->toDateString()) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Banque</label>
                            <input type="text" name="banque" class="form-control" value="{{ old('banque') }}" placeholder="Ex : BANCOBU, KCB, BCB...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Référence (bordereau, chèque...)</label>
                            <input type="text" name="reference" class="form-control" value="{{ old('reference') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pièce justificative</label>
                            <input type="file" name="justificatif" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                            <div class="form-text">PDF, JPG ou PNG — 5 Mo maximum</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle me-2"></i>Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal : ajouter une pièce justificative -->
<div class="modal fade" id="attachModal" tabindex="-1" aria-labelledby="attachModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="" id="attachForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="attachModalLabel"><i class="bi bi-paperclip me-2"></i>Pièce justificative</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <input type="file" name="justificatif" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                    <div class="form-text">PDF, JPG ou PNG — 5 Mo maximum. Remplace le fichier existant s'il y en a un.</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-upload me-2"></i>Envoyer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.border-left-primary { border-left: 0.25rem solid #4e73df !important; }
.border-left-success { border-left: 0.25rem solid #1cc88a !important; }
.border-left-danger { border-left: 0.25rem solid #e74a3b !important; }
.border-left-warning { border-left: 0.25rem solid #f6c23e !important; }
.bg-success-soft { background-color: rgba(25, 135, 84, 0.1) !important; }
.bg-danger-soft { background-color: rgba(220, 53, 69, 0.1) !important; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-attach').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('attachForm').action = this.dataset.action;
        });
    });

    @if($errors->hasAny(['operation_type', 'montant', 'date_operation', 'banque', 'reference', 'description']))
        new bootstrap.Modal(document.getElementById('operationModal')).show();
    @endif
});
</script>
@endsection
