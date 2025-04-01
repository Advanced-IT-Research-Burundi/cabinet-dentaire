@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Stock</h1>
    <form action="{{ route('stocks.update', $stock->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-md-6">
            <label for="product_name" class="form-label">Nom du Produit</label>
            <input type="text" class="form-control" id="product_name" name="product_name" value="{{ old('product_name', $stock->product_name) }}" required>
            </div>
            <div class="col-md-6">
            <label for="category" class="form-label">Catégorie</label>
            <input type="text" class="form-control" id="category" name="category" value="{{ old('category', $stock->category) }}">
            </div>
            <div class="col-md-6">
            <label for="available_quantity" class="form-label">Quantité Disponible</label>
            <input type="number" class="form-control" id="available_quantity" name="available_quantity" value="{{ old('available_quantity', $stock->available_quantity) }}" required>
            </div>
            <div class="col-md-6">
            <label for="unit_measure" class="form-label">Unité de Mesure</label>
            <input type="text" class="form-control" id="unit_measure" name="unit_measure" value="{{ old('unit_measure', $stock->unit_measure) }}">
            </div>
            <div class="col-md-6">
            <label for="minimum_quantity" class="form-label">Quantité Minimum</label>
            <input type="number" class="form-control" id="minimum_quantity" name="minimum_quantity" value="{{ old('minimum_quantity', $stock->minimum_quantity) }}">
            </div>
            <div class="col-md-6">
            <label for="purchase_price" class="form-label">Prix d'Achat</label>
            <input type="number" step="0.01" class="form-control" id="purchase_price" name="purchase_price" value="{{ old('purchase_price', $stock->purchase_price) }}">
            </div>
            <div class="col-md-6">
            <label for="supplier" class="form-label">Fournisseur</label>
            <input type="text" class="form-control" id="supplier" name="supplier" value="{{ old('supplier', $stock->supplier) }}">
            </div>
            <div class="col-md-6">
            <label for="location" class="form-label">Emplacement</label>
            <input type="text" class="form-control" id="location" name="location" value="{{ old('location', $stock->location) }}">
            </div>
            <div class="col-md-6">
            <label for="expiration_date" class="form-label">Date d'Expiration</label>
            <input type="date" class="form-control" id="expiration_date" name="expiration_date" value="{{ old('expiration_date', $stock->expiration_date) }}">
            </div>
            <div class="col-md-6">
            <label for="status" class="form-label">Status</label>
            <select id="status" name="status" class="form-select">
                <option value="0" {{ old('status', $stock->status) == 0 ? 'selected' : '' }}>Disponible</option>
                <option value="1" {{ old('status', $stock->status) == 1 ? 'selected' : '' }}>Faible stock</option>
                <option value="2" {{ old('status', $stock->status) == 2 ? 'selected' : '' }}>En rupture</option>
                <option value="3" {{ old('status', $stock->status) == 3 ? 'selected' : '' }}>Expiré</option>
                <option value="4" {{ old('status', $stock->status) == 4 ? 'selected' : '' }}>Autre</option>
            </select>
            </div>
            <div class="col-12">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $stock->description) }}</textarea>
            </div>
        </div>
        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Mettre à Jour</button>
            <a href="{{ route('stocks.index') }}" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>
@endsection