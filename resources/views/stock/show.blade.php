@extends('layouts.app')

@section('title', 'Détails du Stock')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="bi bi-info-circle me-2"></i> Détails du Produit
        </h1>
        <a href="{{ route('stocks.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row row-cols-1 row-cols-md-3 g-3">
                <div class="col">
                    <h5><i class="bi bi-upc me-1"></i> Code Produit</h5>
                    <p>{{ $stock->code_product ?: '-' }}</p>
                </div>
                <div class="col">
                    <h5><i class="bi bi-box me-1"></i> Nom du Produit</h5>
                    <p>{{ $stock->product_name ?: '-' }}</p>
                </div>
                <div class="col">
                    <h5><i class="bi bi-tag me-1"></i> Marque</h5>
                    <p>{{ $stock->marque ?: '-' }}</p>
                </div>
                <div class="col">
                    <h5><i class="bi bi-rulers me-1"></i> Unité de Mesure</h5>
                    <p>{{ $stock->unite_mesure ?: '-' }}</p>
                </div>
                <div class="col">
                    <h5><i class="bi bi-stack me-1"></i> Quantité</h5>
                    <p>{{ $stock->quantite }}</p>
                </div>
                <div class="col">
                    <h5><i class="bi bi-exclamation-circle me-1"></i> Quantité d'Alerte</h5>
                    <p>{{ $stock->quantite_alert }}</p>
                </div>
                <div class="col">
                    <h5><i class="bi bi-currency-dollar me-1"></i> Prix</h5>
                    <p>{{ $stock->price ? number_format($stock->price, 2) . ' Fbu' : '-' }}</p>
                </div>
                <div class="col">
                    <h5><i class="bi bi-receipt me-1"></i> Prix TTC</h5>
                    <p>{{ $stock->price_ttc ? number_format($stock->price_ttc, 2) . ' Fbu' : '-' }}</p>
                </div>
                <div class="col">
                    <h5><i class="bi bi-arrow-up-circle me-1"></i> Prix Maximum</h5>
                    <p>{{ $stock->price_max ? number_format($stock->price_max, 2) . ' Fbu' : '-' }}</p>
                </div>
                <div class="col">
                    <h5><i class="bi bi-percent me-1"></i> Prix TVAC</h5>
                    <p>{{ $stock->price_tvac ? number_format($stock->price_tvac, 2) . ' Fbu' : '-' }}</p>
                </div>
                <div class="col">
                    <h5><i class="bi bi-calculator me-1"></i> Taux TVA</h5>
                    <p>{{ $stock->taux_tva }}</p>
                </div>
                <div class="col">
                    <h5><i class="bi bi-file-earmark-bar-graph me-1"></i> Taxe OTT</h5>
                    <p>{{ $stock->item_ott_tax }}</p>
                </div>
                <div class="col">
                    <h5><i class="bi bi-cash-stack me-1"></i> Taxe TSCE</h5>
                    <p>{{ $stock->item_tsce_tax }}</p>
                </div>
                <div class="col">
                    <h5><i class="bi bi-arrow-down-circle me-1"></i> Prix Minimum</h5>
                    <p>{{ $stock->price_min }}</p>
                </div>
                <div class="col">
                    <h5><i class="bi bi-calendar-event me-1"></i> Date d'Expiration</h5>
                    <p>{{ $stock->date_expiration ? $stock->date_expiration->format('d/m/Y') : '-' }}</p>
                </div>
                <div class="col">
                    <h5><i class="bi bi-tags me-1"></i> Catégorie</h5>
                    <p>{{ $stock->category->name ?? '-' }}</p>
                </div>
                <div class="col">
                    <h5><i class="bi bi-person-circle me-1"></i> Utilisateur</h5>
                    <p>{{ $user->name ?? '-' }}</p>
                </div>
                <div class="col">
                    <h5><i class="bi bi-truck me-1"></i> Fournisseur</h5>
                    <p>{{ $stock->supplier->name ?? '-' }}</p>
                </div>
                <div class="col-12">
                    <h5><i class="bi bi-card-text me-1"></i> Description</h5>
                    <p>{{ $stock->description ?: '-' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
