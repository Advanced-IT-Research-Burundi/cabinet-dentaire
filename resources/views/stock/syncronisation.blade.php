@extends('layouts.app')

@section('title', 'Synchronisation des stocks avec OBR')

@section('page-title', 'Synchronisation avec OBR')

@push('styles')
<style>
    .stats-card {
        border-left: 4px solid;
        transition: transform 0.2s;
    }
    .stats-card:hover {
        transform: translateY(-2px);
    }
    .stats-entrees { border-left-color: #198754; }
    .stats-sorties { border-left-color: #dc3545; }
    .stats-sync { border-left-color: #0d6efd; }
    .stats-nonsync { border-left-color: #ffc107; }
    .filter-card {
        background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);
        border: none;
        border-radius: 10px;
    }
    .filter-card label {
        color: white;
        font-weight: 500;
        font-size: 0.85rem;
    }
    .filter-card .form-control,
    .filter-card .form-select {
        border: none;
        border-radius: 8px;
    }
</style>
@endpush

@section('breadcrumbs')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
            <li class="breadcrumb-item active" aria-current="page">Synchronisation OBR</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Statistiques -->
            <div class="row mb-3">
                <div class="col-md-3 col-6 mb-2">
                    <div class="card stats-card stats-entrees shadow-sm h-100">
                        <div class="card-body py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">Entrees</small>
                                    <h4 class="mb-0 text-success">{{ $stats['entrees'] ?? 0 }}</h4>
                                </div>
                                <i class="bi bi-arrow-down-circle text-success fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-2">
                    <div class="card stats-card stats-sorties shadow-sm h-100">
                        <div class="card-body py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">Sorties</small>
                                    <h4 class="mb-0 text-danger">{{ $stats['sorties'] ?? 0 }}</h4>
                                </div>
                                <i class="bi bi-arrow-up-circle text-danger fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-2">
                    <div class="card stats-card stats-sync shadow-sm h-100">
                        <div class="card-body py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">Synchronises</small>
                                    <h4 class="mb-0 text-primary">{{ $stats['synchronises'] ?? 0 }}</h4>
                                </div>
                                <i class="bi bi-check-circle text-primary fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-2">
                    <div class="card stats-card stats-nonsync shadow-sm h-100">
                        <div class="card-body py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">Non Sync.</small>
                                    <h4 class="mb-0 text-warning">{{ $stats['non_synchronises'] ?? 0 }}</h4>
                                </div>
                                <i class="bi bi-x-circle text-warning fs-3"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtres -->
            <div class="card filter-card shadow-sm mb-3">
                <div class="card-body py-3">
                    <form action="{{ route('stock.syncronisation') }}" method="GET" id="filterForm">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-2">
                                <label for="search" class="form-label mb-1">Recherche</label>
                                <input type="text" name="search" id="search" class="form-control form-control-sm"
                                    placeholder="Code, Designation..." value="{{ $search ?? '' }}">
                            </div>
                            <div class="col-md-2">
                                <label for="type_mouvement" class="form-label mb-1">Type de mouvement</label>
                                <select name="type_mouvement" id="type_mouvement" class="form-select form-select-sm">
                                    <option value="">Tous les types</option>
                                    <option value="entrees" {{ ($typeMouvement ?? '') == 'entrees' ? 'selected' : '' }}>Toutes entrees</option>
                                    <option value="sorties" {{ ($typeMouvement ?? '') == 'sorties' ? 'selected' : '' }}>Toutes sorties</option>
                                    <optgroup label="Types d'entrees">
                                        @foreach($mouvementTypes as $code => $label)
                                            @if(str_starts_with($code, 'E'))
                                                <option value="{{ $code }}" {{ ($typeMouvement ?? '') == $code ? 'selected' : '' }}>{{ $code }} - {{ $label }}</option>
                                            @endif
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="Types de sorties">
                                        @foreach($mouvementTypes as $code => $label)
                                            @if(str_starts_with($code, 'S'))
                                                <option value="{{ $code }}" {{ ($typeMouvement ?? '') == $code ? 'selected' : '' }}>{{ $code }} - {{ $label }}</option>
                                            @endif
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="date_debut" class="form-label mb-1">Date debut</label>
                                <input type="date" name="date_debut" id="date_debut" class="form-control form-control-sm"
                                    value="{{ $dateDebut ?? '' }}">
                            </div>
                            <div class="col-md-2">
                                <label for="date_fin" class="form-label mb-1">Date fin</label>
                                <input type="date" name="date_fin" id="date_fin" class="form-control form-control-sm"
                                    value="{{ $dateFin ?? '' }}">
                            </div>
                            <div class="col-md-1">
                                <label for="statut_obr" class="form-label mb-1">Statut OBR</label>
                                <select name="statut_obr" id="statut_obr" class="form-select form-select-sm">
                                    <option value="">Tous</option>
                                    <option value="1" {{ ($statutObr ?? '') === '1' ? 'selected' : '' }}>Sync.</option>
                                    <option value="0" {{ ($statutObr ?? '') === '0' ? 'selected' : '' }}>Non sync.</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label for="per_page" class="form-label mb-1">Par page</label>
                                <select name="per_page" id="per_page" class="form-select form-select-sm">
                                    <option value="15" {{ ($perPage ?? 15) == 15 ? 'selected' : '' }}>15</option>
                                    <option value="25" {{ ($perPage ?? 15) == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ ($perPage ?? 15) == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ ($perPage ?? 15) == 100 ? 'selected' : '' }}>100</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <div class="d-flex gap-1">
                                    <button type="submit" class="btn btn-light btn-sm flex-fill">
                                        <i class="bi bi-funnel"></i> Filtrer
                                    </button>
                                    <a href="{{ route('stock.syncronisation') }}" class="btn btn-outline-light btn-sm">
                                        <i class="bi bi-x"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tableau -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-arrow-repeat me-2"></i>Mouvements de stock
                        <span class="badge bg-secondary ms-2">{{ $stats['total'] ?? $mouvements->total() }}</span>
                    </h5>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-success btn-sm" onclick="exportData('excel')">
                            <i class="bi bi-file-earmark-excel"></i> Excel
                        </button>
                        <button type="button" class="btn btn-danger btn-sm" onclick="exportData('pdf')">
                            <i class="bi bi-file-earmark-pdf"></i> PDF
                        </button>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 table-sm">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Code</th>
                                    <th>Designation</th>
                                    <th>Qte</th>
                                    <th>Unite</th>
                                    <th>Prix</th>
                                    <th>Type</th>
                                    <th>Ref. Facture</th>
                                    <th>Date</th>
                                    <th>Statut OBR</th>
                                    <th>Envoye le</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($mouvements as $mouvement)
                                    <tr>
                                        <td class="ps-4 fw-semibold text-primary">{{ $mouvement->item_code }}</td>
                                        <td>
                                            <div class="fw-medium">{{ $mouvement->item_designation }}</div>
                                            @if($mouvement->item_movement_description)
                                                <small class="text-muted">{{ Str::limit($mouvement->item_movement_description, 30) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ str_starts_with($mouvement->item_movement_type, 'E') ? 'success' : 'danger' }}">
                                                {{ str_starts_with($mouvement->item_movement_type, 'E') ? '+' : '-' }}{{ $mouvement->item_quantity }}
                                            </span>
                                        </td>
                                        <td class="small text-muted">{{ $mouvement->item_measurement_unit }}</td>
                                        <td>
                                            <div class="fw-medium">{{ number_format($mouvement->item_purchase_or_sale_price, 0, ',', ' ') }}</div>
                                            <small class="text-muted">{{ $mouvement->item_purchase_or_sale_currency }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ str_starts_with($mouvement->item_movement_type, 'E') ? 'success' : 'danger' }} bg-opacity-10 text-{{ str_starts_with($mouvement->item_movement_type, 'E') ? 'success' : 'danger' }}" title="{{ $mouvementTypes[$mouvement->item_movement_type] ?? '' }}">
                                                {{ $mouvement->item_movement_type }}
                                            </span>
                                        </td>
                                        <td class="font-monospace small">{{ $mouvement->item_movement_invoice_ref ?? '-' }}</td>
                                        <td class="small">{{ \Carbon\Carbon::parse($mouvement->item_movement_date)->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @if ($mouvement->is_send_to_obr == 1)
                                                <span class="badge bg-success rounded-pill"><i class="bi bi-check-circle me-1"></i>Sync.</span>
                                            @else
                                                <span class="badge bg-danger rounded-pill"><i class="bi bi-x-circle me-1"></i>Non Sync.</span>
                                            @endif
                                        </td>
                                        <td class="small text-muted">
                                            {{ $mouvement->is_sent_at ? \Carbon\Carbon::parse($mouvement->is_sent_at)->format('d/m/Y H:i') : '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-5">
                                            <div class="text-muted opacity-75">
                                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                                <p class="mb-0">Aucun mouvement trouve pour cette recherche.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Affichage de {{ $mouvements->firstItem() ?? 0 }} a {{ $mouvements->lastItem() ?? 0 }} sur {{ $mouvements->total() }} mouvements
                        </div>
                        <div>
                            {{ $mouvements->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function exportData(format) {
        var params = new URLSearchParams(window.location.search);

        var dateDebut = document.getElementById('date_debut').value || '{{ now()->subMonth()->format('Y-m-d') }}';
        var dateFin = document.getElementById('date_fin').value || '{{ now()->format('Y-m-d') }}';
        var typeMouvement = document.getElementById('type_mouvement').value || 'all';

        var baseUrl = format === 'excel'
            ? '{{ route("stock.syncronisation.export-excel") }}'
            : '{{ route("stock.syncronisation.export-pdf") }}';

        var url = baseUrl + '?date_debut=' + dateDebut + '&date_fin=' + dateFin + '&type_mouvement=' + typeMouvement;

        window.location.href = url;
    }
</script>
@endpush
