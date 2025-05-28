@extends('layouts.app')

@section('title', 'Rapports Stock')

@push('styles')
<style>
    /* Style personnalisé pour DataTables */
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #dee2e6;
        border-radius: 4px;
        padding: 0.375rem 0.75rem;
    }

    .dataTables_wrapper .dataTables_length select {
        border: 1px solid #dee2e6;
        border-radius: 4px;
        padding: 0.25rem 2rem 0.25rem 0.5rem;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.25rem 0.75rem;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        margin: 0 2px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #0d6efd;
        color: white !important;
        border-color: #0d6efd;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #e9ecef;
        color: #0d6efd !important;
    }

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
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Statistiques rapides -->
    <div class="row m-0">
        <div class="col-md-3">
            <div class="card stats-card stock-disponible shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title text-muted mb-0">Stock Disponible</h6>
                            <h3 class="mb-0 text-success">{{ $produits->where('status', 'Disponible')->count() }}</h3>
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
                            <h3 class="mb-0 text-warning">{{ $produits->where('status', 'Faible_stock')->count() }}</h3>
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
                            <h3 class="mb-0 text-danger">{{ $produits->where('status', 'En_rupture')->count() }}</h3>
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
                            <h6 class="card-title text-muted mb-0">Expirés</h6>
                            <h3 class="mb-0 text-secondary">{{ $produits->where('status', 'Expire')->count() }}</h3>
                        </div>
                        <i class="bi bi-clock-fill text-secondary fs-1"></i>
                    </div>
                </div>
            </div>
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
                <table id="stockTable" class="table table-striped table-hover" style="width:100%">
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
                            <th colspan="6" class="text-end">Total:</th>
                            <th class="text-center">{{ number_format($produits->sum('quantite'), 0) }}</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th class="text-end">{{ number_format($produits->sum(function($produit) {
                                return $produit->quantite * $produit->price;
                            }), 0, ',', ' ') }} FBU</th>
                            <th colspan="3"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Résumé des valeurs -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Résumé Financier</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <p class="mb-1"><strong>Valeur totale HTVA:</strong></p>
                            <h4 class="text-info">{{ number_format($produits->sum(function($p) { return $p->quantite * $p->price; }), 0, ',', ' ') }} FBU</h4>
                        </div>
                        <div class="col-6">
                            <p class="mb-1"><strong>Valeur totale TTC:</strong></p>
                            <h4 class="text-success">{{ number_format($produits->sum(function($p) { return $p->quantite * $p->price_ttc; }), 0, ',', ' ') }} FBU</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Statistiques Générales</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <p class="mb-1"><strong>Total produits:</strong></p>
                            <h4 class="text-primary">{{ $produits->count() }}</h4>
                        </div>
                        <div class="col-6">
                            <p class="mb-1"><strong>Total unités:</strong></p>
                            <h4 class="text-dark">{{ number_format($produits->sum('quantite'), 0) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialisation de DataTable avec des options personnalisées
        var table = $('#stockTable').DataTable({
            responsive: true,
            dom: "<'row'<'col-md-6'l><'col-md-6'f>>" +
                 "<'row'<'col-md-12'tr>>" +
                 "<'row'<'col-md-5'i><'col-md-7'p>>",
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json',
                search: "Rechercher:",
                lengthMenu: "Afficher _MENU_ éléments par page",
                info: "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
                infoEmpty: "Aucun élément à afficher",
                infoFiltered: "(filtré de _MAX_ éléments au total)",
                paginate: {
                    first: "Premier",
                    last: "Dernier",
                    next: "Suivant",
                    previous: "Précédent"
                }
            },
            order: [[0, 'desc']],
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tout"]],
            columnDefs: [
                { targets: [6, 7], className: 'text-center' },
                { targets: [8, 9, 10], className: 'text-end' },
                { targets: [11, 12, 13], className: 'text-center' }
            ]
        });

        // Personnalisation de la recherche
        $('.dataTables_filter input')
            .attr('placeholder', 'Rechercher produit, catégorie, marque...')
            .addClass('form-control form-control-sm');

        // Personnalisation du sélecteur de nombre d'entrées
        $('.dataTables_length select').addClass('form-select form-select-sm');

        // Filtres par statut
        $('#stockTable tbody').on('click', '.badge', function() {
            var status = $(this).text();
            table.search(status).draw();
        });

        // Reset du filtre au double-clic
        $('#stockTable tbody').on('dblclick', function() {
            table.search('').draw();
        });
    });
</script>
@endpush
