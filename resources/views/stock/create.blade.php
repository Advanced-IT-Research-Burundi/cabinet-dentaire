@extends('layouts.app')

@section('title', 'Créer un Nouveau Stock')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Créer un Nouveau Stock</h1>
        <a href="{{ route('stocks.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('stocks.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="product_name" class="form-label">Nom du Produit</label>
                        <input type="text" class="form-control" id="product_name" name="product_name" value="{{ old('product_name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="category" class="form-label">Catégorie</label>
                        <input type="text" class="form-control" id="category" name="category" value="{{ old('category') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="available_quantity" class="form-label">Quantité Disponible</label>
                        <input type="number" class="form-control" id="available_quantity" name="available_quantity" value="{{ old('available_quantity') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="unit_measure" class="form-label">Unité de Mesure</label>
                        <input type="text" class="form-control" id="unit_measure" name="unit_measure" value="{{ old('unit_measure') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="minimum_quantity" class="form-label">Quantité Minimum</label>
                        <input type="number" class="form-control" id="minimum_quantity" name="minimum_quantity" value="{{ old('minimum_quantity') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="purchase_price" class="form-label">Prix d'Achat</label>
                        <input type="number" step="0.01" class="form-control" id="purchase_price" name="purchase_price" value="{{ old('purchase_price') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="supplier" class="form-label">Fournisseur</label>
                        <input type="text" class="form-control" id="supplier" name="supplier" value="{{ old('supplier') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="location" class="form-label">Emplacement</label>
                        <input type="text" class="form-control" id="location" name="location" value="{{ old('location') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="expiration_date" class="form-label">Date d'Expiration</label>
                        <input type="date" class="form-control" id="expiration_date" name="expiration_date" value="{{ old('expiration_date') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-select">
                            <option value="Disponible" {{ old('status') == "Disponible" ? 'selected' : '' }}>Disponible</option>
                            <option value="Faible_stock" {{ old('status') == "Faible_stock" ? 'selected' : '' }}>Faible stock</option>
                            <option value="En_rupture" {{ old('status') == "En_rupture" ? 'selected' : '' }}>En rupture</option>
                            <option value="Expire" {{ old('status') == "Expire" ? 'selected' : '' }}>Expiré</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
