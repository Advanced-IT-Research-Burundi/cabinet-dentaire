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
                    <h5>Nom du Produit</h5>
                    <p>{{ $stock->product_name }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Catégorie</h5>
                    <p>{{ $stock->category ?: '-' }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Quantité Disponible</h5>
                    <p>{{ $stock->available_quantity }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Unité de Mesure</h5>
                    <p>{{ $stock->unit_measure ?: '-' }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Quantité Minimum</h5>
                    <p>{{ $stock->minimum_quantity ?: '-' }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Prix d'Achat</h5>
                    <p>{{ $stock->purchase_price ? number_format($stock->purchase_price, 2) . ' €' : '-' }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Fournisseur</h5>
                    <p>{{ $stock->supplier ?: '-' }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Emplacement</h5>
                    <p>{{ $stock->location ?: '-' }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Date d'Expiration</h5>
                    <p>{{ $stock->expiration_date ? $stock->expiration_date->format('d/m/Y') : '-' }}</p>
                </div>
                <div class="col-md-6">
                    <h5>Status</h5>
                    <p>
                        @if($stock->status == "Disponible")
                            <span class="badge bg-success">Disponible</span>
                        @elseif($stock->status == "Faible_stock")
                            <span class="badge bg-warning">Faible stock</span>
                        @elseif($stock->status == "En_rupture")
                            <span class="badge bg-danger">En rupture</span>
                        @elseif($stock->status == "Expire")
                            <span class="badge bg-secondary">Expiré</span>
                        @else
                            <span>-</span>
                        @endif
                    </p>
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
