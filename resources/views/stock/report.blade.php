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
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Rapport du stock</h4>
            <div>
                <button class="btn btn-light btn-sm" onclick="window.print()">
                    <i class="bi bi-printer"></i> Imprimer
                </button>
                <button class="btn btn-light btn-sm ms-2" id="exportExcel">
                    <i class="bi bi-file-earmark-excel"></i> Excel
                </button>
                <button class="btn btn-light btn-sm ms-2" id="exportPdf">
                    <i class="bi bi-file-earmark-pdf"></i> PDF
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="stockTable" class="table table-striped table-hover" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nom du produit</th>
                            <th>Catégorie</th>
                            <th>Quantité en stock</th>
                            <th>Prix unitaire</th>
                            <th>Valeur totale</th>
                            <th>Dernière mise à jour</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($produits as $produit)
                        <tr>
                            <td>{{ $produit->id }}</td>
                            <td>{{ $produit->nom }}</td>
                            <td>{{ $produit->categorie->nom ?? 'Non spécifiée' }}</td>
                            <td class="text-center">
                                <span class="badge bg-{{ $produit->quantite > 10 ? 'success' : ($produit->quantite > 0 ? 'warning' : 'danger') }}">
                                    {{ $produit->quantite }}
                                </span>
                            </td>
                            <td>{{ number_format($produit->prix_unitaire, 0, ',', ' ') }} FBU</td>
                            <td>{{ number_format($produit->quantite * $produit->prix_unitaire, 0, ',', ' ') }} FBU</td>
                            <td>{{ $produit->updated_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($produit->quantite > 10)
                                    <span class="badge bg-success">En stock</span>
                                @elseif($produit->quantite > 0)
                                    <span class="badge bg-warning">Stock faible</span>
                                @else
                                    <span class="badge bg-danger">Rupture</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">Total:</th>
                            <th class="text-center">{{ $produits->sum('quantite') }}</th>
                            <th></th>
                            <th>{{ number_format($produits->sum(function($produit) {
                                return $produit->quantite * $produit->prix_unitaire;
                            }), 0, ',', ' ') }} FBU</th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
                </table>
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
            pageLength: 10,
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="bi bi-file-earmark-excel"></i> Excel',
                    className: 'btn btn-success btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7]
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="bi bi-file-earmark-pdf"></i> PDF',
                    className: 'btn btn-danger btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7]
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="bi bi-printer"></i> Imprimer',
                    className: 'btn btn-primary btn-sm',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7]
                    }
                }
            ],
            initComplete: function() {
                // Ajout des boutons d'exportation
                var buttons = new $.fn.dataTable.Buttons(table, {
                    buttons: ['excel', 'pdf', 'print']
                }).container().appendTo($('#exportButtons'));
            }
        });

        // Gestion des boutons d'exportation
        $('#exportExcel').on('click', function() {
            table.button('.buttons-excel').trigger();
        });

        $('#exportPdf').on('click', function() {
            table.button('.buttons-pdf').trigger();
        });

        // Personnalisation de la recherche
        $('.dataTables_filter input')
            .attr('placeholder', 'Rechercher...')
            .addClass('form-control form-control-sm');

        // Personnalisation du sélecteur de nombre d'entrées
        $('.dataTables_length select').addClass('form-select form-select-sm');
    });
</script>
@endpush
