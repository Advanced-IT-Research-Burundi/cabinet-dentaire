@extends('layouts.app')

@section('title', 'Importer Stock')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-upload"></i> Importer des données de stock
                    </h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Instructions -->
                    <div class="alert alert-info">
                        <h6><i class="bi bi-info-circle"></i> Instructions d'import</h6>
                        <ul class="mb-0">
                            <li>Utilisez le <strong>modèle Excel</strong> fourni pour structurer vos données</li>
                            <li>Les colonnes <strong>nom_du_produit</strong>, <strong>quantite_en_stock</strong> et <strong>prix_unitaire_htva</strong> sont obligatoires</li>
                            <li>Les catégories et fournisseurs inexistants seront créés automatiquement</li>
                            <li>Le format de date accepté est <strong>JJ/MM/AAAA</strong></li>
                            <li>Formats supportés: <strong>Excel (.xlsx, .xls) et CSV</strong></li>
                            <li>Taille maximale: <strong>2 MB</strong></li>
                        </ul>
                    </div>

                    <!-- Télécharger le modèle -->
                    <div class="mb-4">
                        <h6>1. Télécharger le modèle</h6>
                        <a href="{{ route('stock.download-template') }}" class="btn btn-outline-success">
                            <i class="bi bi-file-earmark-arrow-down"></i> Télécharger le modèle Excel
                        </a>
                        <small class="text-muted d-block mt-1">
                            Ce modèle contient toutes les colonnes nécessaires avec un exemple de données
                        </small>
                    </div>

                    <!-- Formulaire d'upload -->
                    <div class="mb-4">
                        <h6>2. Sélectionner et importer votre fichier</h6>
                        <form action="{{ route('stock.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                            @csrf
                            <div class="mb-3">
                                <label for="file" class="form-label">Fichier à importer</label>
                                <input type="file"
                                       class="form-control @error('file') is-invalid @enderror"
                                       id="file"
                                       name="file"
                                       accept=".xlsx,.xls,.csv"
                                       required>
                                @error('file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Formats acceptés: Excel (.xlsx, .xls) et CSV. Taille max: 2MB
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('stock.rapport') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Retour au rapport
                                </a>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="bi bi-upload"></i> Importer les données
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Colonnes du modèle -->
                    <div class="mt-4">
                        <h6>Structure du modèle Excel</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Colonne</th>
                                        <th>Obligatoire</th>
                                        <th>Type</th>
                                        <th>Exemple</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><code>nom_du_produit</code></td>
                                        <td><span class="badge bg-danger">Oui</span></td>
                                        <td>Texte</td>
                                        <td>Ordinateur portable</td>
                                        <td>Nom du produit</td>
                                    </tr>
                                    <tr>
                                        <td><code>marque</code></td>
                                        <td><span class="badge bg-secondary">Non</span></td>
                                        <td>Texte</td>
                                        <td>Dell</td>
                                        <td>Marque du produit</td>
                                    </tr>
                                    <tr>
                                        <td><code>code_produit</code></td>
                                        <td><span class="badge bg-secondary">Non</span></td>
                                        <td>Texte</td>
                                        <td>DELL-001</td>
                                        <td>Code unique du produit</td>
                                    </tr>
                                    <tr>
                                        <td><code>categorie</code></td>
                                        <td><span class="badge bg-secondary">Non</span></td>
                                        <td>Texte</td>
                                        <td>Informatique</td>
                                        <td>Nom de la catégorie</td>
                                    </tr>
                                    <tr>
                                        <td><code>fournisseur</code></td>
                                        <td><span class="badge bg-secondary">Non</span></td>
                                        <td>Texte</td>
                                        <td>Dell Inc.</td>
                                        <td>Nom du fournisseur</td>
                                    </tr>
                                    <tr>
                                        <td><code>unite_de_mesure</code></td>
                                        <td><span class="badge bg-secondary">Non</span></td>
                                        <td>Texte</td>
                                        <td>Pièce</td>
                                        <td>Unité de mesure</td>
                                    </tr>
                                    <tr>
                                        <td><code>quantite_en_stock</code></td>
                                        <td><span class="badge bg-danger">Oui</span></td>
                                        <td>Nombre</td>
                                        <td>10</td>
                                        <td>Quantité disponible</td>
                                    </tr>
                                    <tr>
                                        <td><code>quantite_dalerte</code></td>
                                        <td><span class="badge bg-secondary">Non</span></td>
                                        <td>Nombre</td>
                                        <td>2</td>
                                        <td>Seuil d'alerte</td>
                                    </tr>
                                    <tr>
                                        <td><code>prix_unitaire_htva</code></td>
                                        <td><span class="badge bg-danger">Oui</span></td>
                                        <td>Nombre</td>
                                        <td>800.00</td>
                                        <td>Prix hors TVA</td>
                                    </tr>
                                    <tr>
                                        <td><code>prix_ttc</code></td>
                                        <td><span class="badge bg-secondary">Non</span></td>
                                        <td>Nombre</td>
                                        <td>928.00</td>
                                        <td>Prix toutes taxes comprises</td>
                                    </tr>
                                    <tr>
                                        <td><code>taux_tva</code></td>
                                        <td><span class="badge bg-secondary">Non</span></td>
                                        <td>Nombre</td>
                                        <td>16.00</td>
                                        <td>Taux de TVA en %</td>
                                    </tr>
                                    <tr>
                                        <td><code>date_dexpiration</code></td>
                                        <td><span class="badge bg-secondary">Non</span></td>
                                        <td>Date</td>
                                        <td>31/12/2025</td>
                                        <td>Date d'expiration (JJ/MM/AAAA)</td>
                                    </tr>
                                    <tr>
                                        <td><code>description</code></td>
                                        <td><span class="badge bg-secondary">Non</span></td>
                                        <td>Texte</td>
                                        <td>Ordinateur portable Dell Inspiron</td>
                                        <td>Description du produit</td>
                                    </tr>
                                    <tr>
                                        <td><code>emplacement</code></td>
                                        <td><span class="badge bg-secondary">Non</span></td>
                                        <td>Texte</td>
                                        <td>Magasin A</td>
                                        <td>Localisation du stock</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('importForm').addEventListener('submit', function() {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Import en cours...';
        submitBtn.disabled = true;
    });

    // Validation de fichier côté client
    document.getElementById('file').addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const maxSize = 2 * 1024 * 1024; // 2MB
            const validTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                               'application/vnd.ms-excel',
                               'text/csv'];

            if (file.size > maxSize) {
                alert('Le fichier est trop volumineux. Taille maximale: 2MB');
                this.value = '';
                return;
            }

            if (!validTypes.includes(file.type)) {
                alert('Format de fichier non supporté. Utilisez Excel (.xlsx, .xls) ou CSV.');
                this.value = '';
                return;
            }
        }
    });
</script>
@endpush
@endsection
