@extends('layouts.app')

@section('title', 'Rapports Stock')

@push('styles')
<style>
    .stats-card {
        border-left: 4px solid;
        transition: transform 0.2s;
    }

    .stats-card:hover {
        transform: translateY(-2px);
    }

    .stock-disponible { border-left-color: #28a745; }
    .stock-faible { border-left-color: #ffc107; }
    .stock-rupture { border-left-color: #dc3545; }
    .stock-expire { border-left-color: #6c757d; }

    .nav-tabs .nav-link {
        color: #495057;
        font-weight: 500;
    }
    .nav-tabs .nav-link.active {
        color: #0d6efd;
        font-weight: 600;
    }
    .nav-tabs .nav-link i {
        margin-right: 5px;
    }
    .filter-card {
        background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        border: none;
        border-radius: 10px;
    }
    .filter-card .card-body {
        padding: 1.5rem;
    }
    .filter-card label {
        color: white;
        font-weight: 500;
    }
    .filter-card .form-control,
    .filter-card .form-select {
        border: none;
        border-radius: 8px;
    }

    .table th {
        white-space: nowrap;
    }

    .pagination {
        margin-bottom: 0;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Navigation par onglets -->
    <ul class="nav nav-tabs mb-4" id="rapportTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="stock-tab" data-bs-toggle="tab" data-bs-target="#stock-content" type="button" role="tab">
                <i class="bi bi-box-seam"></i> Rapport du Stock
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="mouvements-tab" data-bs-toggle="tab" data-bs-target="#mouvements-content" type="button" role="tab">
                <i class="bi bi-arrow-left-right"></i> Rapport des Mouvements
            </button>
        </li>
    </ul>

    <div class="tab-content" id="rapportTabsContent">
        <!-- Onglet Rapport du Stock -->
        <div class="tab-pane fade show active" id="stock-content" role="tabpanel">
            <!-- Statistiques rapides -->
            <div class="row m-0">
        <div class="col-md-3">
            <div class="card stats-card stock-disponible shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted mb-0">Stock Disponible</h6>
                            <h3 class="mb-0 text-success">{{ $stats['disponible'] }}</h3>
                        </div>
                        <i class="bi bi-check-circle-fill text-success fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card stock-faible shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted mb-0">Stock Faible</h6>
                            <h3 class="mb-0 text-warning">{{ $stats['faible'] }}</h3>
                        </div>
                        <i class="bi bi-exclamation-triangle-fill text-warning fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card stock-rupture shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted mb-0">En Rupture</h6>
                            <h3 class="mb-0 text-danger">{{ $stats['rupture'] }}</h3>
                        </div>
                        <i class="bi bi-x-circle-fill text-danger fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card stock-expire shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted mb-0">Expires</h6>
                            <h3 class="mb-0 text-secondary">{{ $stats['expire'] }}</h3>
                        </div>
                        <i class="bi bi-clock-fill text-secondary fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres de recherche -->
    <div class="card shadow-sm mt-3 mb-3">
        <div class="card-body py-2">
            <form method="GET" action="{{ route('stock.rapport') }}" class="row g-2 align-items-center">
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" name="search" placeholder="Rechercher produit, marque, code..." value="{{ $search ?? '' }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Tous les statuts</option>
                        <option value="Disponible" {{ ($status ?? '') == 'Disponible' ? 'selected' : '' }}>Disponible</option>
                        <option value="Faible_stock" {{ ($status ?? '') == 'Faible_stock' ? 'selected' : '' }}>Stock faible</option>
                        <option value="En_rupture" {{ ($status ?? '') == 'En_rupture' ? 'selected' : '' }}>En rupture</option>
                        <option value="Expire" {{ ($status ?? '') == 'Expire' ? 'selected' : '' }}>Expire</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="per_page" class="form-select form-select-sm">
                        <option value="10" {{ ($perPage ?? 25) == 10 ? 'selected' : '' }}>10 par page</option>
                        <option value="25" {{ ($perPage ?? 25) == 25 ? 'selected' : '' }}>25 par page</option>
                        <option value="50" {{ ($perPage ?? 25) == 50 ? 'selected' : '' }}>50 par page</option>
                        <option value="100" {{ ($perPage ?? 25) == 100 ? 'selected' : '' }}>100 par page</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-funnel"></i> Filtrer
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('stock.rapport') }}" class="btn btn-outline-secondary btn-sm w-100">
                        <i class="bi bi-x-circle"></i> Reinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm mt-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Rapport du stock</h4>
            <div>
                {{-- <a href="{{ route('stock.import-form') }}" class="btn btn-light btn-sm me-2">
                    <i class="bi bi-upload"></i> Importer
                </a>
                <a href="{{ route('stock.download-template') }}" class="btn btn-light btn-sm me-2">
                    <i class="bi bi-file-earmark-arrow-down"></i> Modèle
                </a> --}}
                {{-- <button class="btn btn-light btn-sm" onclick="window.print()">
                    <i class="bi bi-printer"></i> Imprimer
                </button> --}}
                <a href="{{ route('stock.export-excel') }}" class="btn btn-light btn-sm ms-2">
                    <i class="bi bi-file-earmark-excel"></i> Excel
                </a>
                <a href="{{ route('stock.export-pdf') }}" class="btn btn-light btn-sm ms-2">
                    <i class="bi bi-file-earmark-pdf"></i> PDF
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-sm" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nom du produit</th>
                            <th>Marque</th>
                            <th>Code</th>
                            <th>Catégorie</th>
                            <th>Fournisseur</th>
                            <th>Quantité</th>
                            <th>Qté Alerte</th>
                            <th>Prix HTVA</th>
                            <th>Prix TTC</th>
                            <th>Valeur totale</th>
                            <th>Expiration</th>
                            <th>Statut</th>
                            <th>Dernière MAJ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($produits as $produit)
                        <tr>
                            <td>{{ $produit->id }}</td>
                            <td>
                                <strong>{{ $produit->product_name }}</strong>
                                @if($produit->description)
                                    <br><small class="text-muted">{{ Str::limit($produit->description, 50) }}</small>
                                @endif
                            </td>
                            <td>{{ $produit->marque ?? '-' }}</td>
                            <td>
                                @if($produit->code_product)
                                    <code>{{ $produit->code_product }}</code>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $produit->category->name ?? 'Non spécifiée' }}</td>
                            <td>{{ $produit->supplier->name ?? 'Non spécifié' }}</td>
                            <td class="text-center">
                                <span class="badge bg-{{ $produit->quantite > $produit->quantite_alert ? 'success' : ($produit->quantite > 0 ? 'warning' : 'danger') }}">
                                    {{ number_format($produit->quantite, 0) }}
                                </span>
                                @if($produit->unite_mesure)
                                    <br><small class="text-muted">{{ $produit->unite_mesure }}</small>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info">{{ number_format($produit->quantite_alert, 0) }}</span>
                            </td>
                            <td class="text-end">{{ number_format($produit->price, 0, ',', ' ') }} FBU</td>
                            <td class="text-end">{{ number_format($produit->price_ttc, 0, ',', ' ') }} FBU</td>
                            <td class="text-end">
                                <strong>{{ number_format($produit->quantite * $produit->price, 0, ',', ' ') }} FBU</strong>
                            </td>
                            <td class="text-center">
                                @if($produit->date_expiration)
                                    {{ $produit->date_expiration->format('d/m/Y') }}
                                    @if($produit->date_expiration->isPast())
                                        <br><small class="text-danger">Expiré</small>
                                    @elseif($produit->date_expiration->diffInDays(now()) <= 30)
                                        <br><small class="text-warning">Expire bientôt</small>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center">
                                @switch($produit->status)
                                    @case('Disponible')
                                        <span class="badge bg-success">En stock</span>
                                        @break
                                    @case('Faible_stock')
                                        <span class="badge bg-warning">Stock faible</span>
                                        @break
                                    @case('En_rupture')
                                        <span class="badge bg-danger">Rupture</span>
                                        @break
                                    @case('Expire')
                                        <span class="badge bg-secondary">Expiré</span>
                                        @break
                                    @default
                                        <span class="badge bg-light text-dark">{{ $produit->status }}</span>
                                @endswitch
                            </td>
                            <td>{{ $produit->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-secondary">
                            <th colspan="6" class="text-end">Total (global):</th>
                            <th class="text-center">{{ number_format($stats['total_quantite'], 0) }}</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th class="text-end">{{ number_format($stats['total_valeur_htva'], 0, ',', ' ') }} FBU</th>
                            <th colspan="3"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Affichage de {{ $produits->firstItem() ?? 0 }} a {{ $produits->lastItem() ?? 0 }} sur {{ $produits->total() }} produits
                </div>
                <div>
                    {{ $produits->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Resume des valeurs -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Resume Financier</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <p class="mb-1"><strong>Valeur totale HTVA:</strong></p>
                            <h4 class="text-info">{{ number_format($stats['total_valeur_htva'], 0, ',', ' ') }} FBU</h4>
                        </div>
                        <div class="col-6">
                            <p class="mb-1"><strong>Valeur totale TTC:</strong></p>
                            <h4 class="text-success">{{ number_format($stats['total_valeur_ttc'], 0, ',', ' ') }} FBU</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Statistiques Generales</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <p class="mb-1"><strong>Total produits:</strong></p>
                            <h4 class="text-primary">{{ $stats['total_produits'] }}</h4>
                        </div>
                        <div class="col-6">
                            <p class="mb-1"><strong>Total unites:</strong></p>
                            <h4 class="text-dark">{{ number_format($stats['total_quantite'], 0) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        </div>
        <!-- Fin onglet Stock -->

        <!-- Onglet Rapport des Mouvements -->
        <div class="tab-pane fade" id="mouvements-content" role="tabpanel">
            <!-- Formulaire de filtres -->
            <div class="card filter-card shadow-sm mb-4">
                <div class="card-body">
                    <form id="mouvementsFilterForm" class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label for="date_debut" class="form-label">Date de debut</label>
                            <input type="date" class="form-control" id="date_debut" name="date_debut"
                                   value="{{ now()->subMonth()->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="date_fin" class="form-label">Date de fin</label>
                            <input type="date" class="form-control" id="date_fin" name="date_fin"
                                   value="{{ now()->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="type_mouvement" class="form-label">Type de mouvement</label>
                            <select class="form-select" id="type_mouvement" name="type_mouvement">
                                <option value="all">Tous les mouvements</option>
                                <option value="entrees">Entrees uniquement</option>
                                <option value="sorties">Sorties uniquement</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Exporter</label>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-light flex-fill" onclick="exportMouvements('excel')">
                                    <i class="bi bi-file-earmark-excel"></i> Excel
                                </button>
                                <button type="button" class="btn btn-light flex-fill" onclick="exportMouvements('pdf')">
                                    <i class="bi bi-file-earmark-pdf"></i> PDF
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Informations sur les types de mouvements -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0"><i class="bi bi-arrow-down-circle"></i> Types d'Entrees</h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li><span class="badge bg-success">EN</span> Entree Normales</li>
                                <li><span class="badge bg-success">ER</span> Entree Retour</li>
                                <li><span class="badge bg-success">EI</span> Entree Inventaire</li>
                                <li><span class="badge bg-success">EAJ</span> Entrees Ajustement</li>
                                <li><span class="badge bg-success">ET</span> Entrees Transfert</li>
                                <li><span class="badge bg-success">EAU</span> Entrees Autres</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-danger text-white">
                            <h6 class="mb-0"><i class="bi bi-arrow-up-circle"></i> Types de Sorties</h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li><span class="badge bg-danger">SN</span> Sorties Normales</li>
                                <li><span class="badge bg-danger">SP</span> Sorties Perte</li>
                                <li><span class="badge bg-danger">SV</span> Sorties Vol</li>
                                <li><span class="badge bg-danger">SD</span> Sorties Desuetude</li>
                                <li><span class="badge bg-danger">SC</span> Sorties Casse</li>
                                <li><span class="badge bg-danger">SAJ</span> Sorties Ajustement</li>
                                <li><span class="badge bg-danger">ST</span> Sorties Transfert</li>
                                <li><span class="badge bg-danger">SAU</span> Sorties Autres</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Message d'instructions -->
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i>
                <strong>Instructions:</strong> Selectionnez la periode et le type de mouvement souhaites, puis cliquez sur le bouton Excel ou PDF pour exporter le rapport des mouvements de stock.
            </div>
        </div>
        <!-- Fin onglet Mouvements -->
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Fonction d'export des mouvements
    function exportMouvements(format) {
        var dateDebut = document.getElementById('date_debut').value;
        var dateFin = document.getElementById('date_fin').value;
        var typeMouvement = document.getElementById('type_mouvement').value;

        if (!dateDebut || !dateFin) {
            alert('Veuillez selectionner les dates de debut et de fin.');
            return;
        }

        if (new Date(dateDebut) > new Date(dateFin)) {
            alert('La date de debut doit etre anterieure a la date de fin.');
            return;
        }

        var baseUrl = format === 'excel'
            ? '{{ route("stock.mouvements.export-excel") }}'
            : '{{ route("stock.mouvements.export-pdf") }}';

        var url = baseUrl + '?date_debut=' + dateDebut + '&date_fin=' + dateFin + '&type_mouvement=' + typeMouvement;

        window.location.href = url;
    }
</script>
@endpush
