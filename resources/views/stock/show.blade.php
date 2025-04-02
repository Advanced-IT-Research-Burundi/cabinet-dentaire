@extends('layouts.app')

@section('title', 'Détails du Stock')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Détails du Stock</h1>
        <a href="{{ route('stocks.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <h5>Code Produit</h5>
                    <p>{{ $stock->code_product ?: '-' }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Marque</h5>
                    <p>{{ $stock->marque ?: '-' }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Unité de Mesure</h5>
                    <p>{{ $stock->unite_mesure ?: '-' }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Quantité</h5>
                    <p>{{ $stock->quantite }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Quantité d'Alerte</h5>
                    <p>{{ $stock->quantite_alert }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Prix</h5>
                    <p>{{ $stock->price ? number_format($stock->price, 2) . ' Fbu' : '-' }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Prix TTC</h5>
                    <p>{{ $stock->price_ttc ? number_format($stock->price_ttc, 2) . ' Fbu' : '-' }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Prix Maximum</h5>
                    <p>{{ $stock->price_max ? number_format($stock->price_max, 2) . ' Fbu' : '-' }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Prix TVAC</h5>
                    <p>{{ $stock->price_tvac ? number_format($stock->price_tvac, 2) . ' Fbu' : '-' }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Taux TVA</h5>
                    <p>{{ $stock->taux_tva }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Taxe OTT</h5>
                    <p>{{ $stock->item_ott_tax }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Taxe TSCE</h5>
                    <p>{{ $stock->item_tsce_tax }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Prix Minimum</h5>
                    <p>{{ $stock->price_min }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Date d'Expiration</h5>
                    <p>{{ $stock->date_expiration ? $stock->date_expiration->format('d/m/Y') : '-' }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Catégorie</h5>
                    <p>{{ $stock->category->name ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Utilisateur</h5>
                    <p>{{ $user->name ?? '-' }}</p>
                </div>
                <div class="col-12">
                    <h5>Description</h5>
                    <p>{{ $stock->description ?: '-' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
