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
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('stocks.store') }}" method="POST">
                @csrf
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="code_product" class="form-label">Code Produit</label>
                        <input type="text" class="form-control @error('code_product') is-invalid @enderror" id="code_product" name="code_product" value="{{ old('code_product') }}">
                        @error('code_product')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="product_name" class="form-label">Nom du Produit</label>
                        <input type="text" class="form-control @error('product_name') is-invalid @enderror" id="product_name" name="product_name" value="{{ old('product_name') }}">
                        @error('product_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="marque" class="form-label">Marque</label>
                        <input type="text" class="form-control @error('marque') is-invalid @enderror" id="marque" name="marque" value="{{ old('marque') }}">
                        @error('marque')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="unite_mesure" class="form-label">Unité de Mesure</label>
                        <input type="text" class="form-control @error('unite_mesure') is-invalid @enderror" id="unite_mesure" name="unite_mesure" value="{{ old('unite_mesure') }}">
                        @error('unite_mesure')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="quantite" class="form-label">Quantité</label>
                        <input type="number" step="0.01" class="form-control @error('quantite') is-invalid @enderror" id="quantite" name="quantite" value="{{ old('quantite') }}" required>
                        @error('quantite')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="quantite_alert" class="form-label">Quantité d'Alerte</label>
                        <input type="number" step="0.01" class="form-control @error('quantite_alert') is-invalid @enderror" id="quantite_alert" name="quantite_alert" value="{{ old('quantite_alert') }}">
                        @error('quantite_alert')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="price" class="form-label">Prix</label>
                        <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}">
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="price_ttc" class="form-label">Prix TTC</label>
                        <input type="number" step="0.01" class="form-control @error('price_ttc') is-invalid @enderror" id="price_ttc" name="price_ttc" value="{{ old('price_ttc') }}">
                        @error('price_ttc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="price_max" class="form-label">Prix Maximum</label>
                        <input type="number" step="0.01" class="form-control @error('price_max') is-invalid @enderror" id="price_max" name="price_max" value="{{ old('price_max') }}">
                        @error('price_max')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="price_tvac" class="form-label">Prix TVAC</label>
                        <input type="number" step="0.01" class="form-control @error('price_tvac') is-invalid @enderror" id="price_tvac" name="price_tvac" value="{{ old('price_tvac') }}">
                        @error('price_tvac')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="taux_tva" class="form-label">Taux TVA</label>
                        <input type="number" step="0.01" class="form-control @error('taux_tva') is-invalid @enderror" id="taux_tva" name="taux_tva" value="{{ old('taux_tva') }}">
                        @error('taux_tva')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="item_ott_tax" class="form-label">Taxe OTT</label>
                        <input type="number" step="0.01" class="form-control @error('item_ott_tax') is-invalid @enderror" id="item_ott_tax" name="item_ott_tax" value="{{ old('item_ott_tax') }}">
                        @error('item_ott_tax')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="item_tsce_tax" class="form-label">Taxe TSCE</label>
                        <input type="number" step="0.01" class="form-control @error('item_tsce_tax') is-invalid @enderror" id="item_tsce_tax" name="item_tsce_tax" value="{{ old('item_tsce_tax') }}">
                        @error('item_tsce_tax')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="price_min" class="form-label">Prix Minimum</label>
                        <input type="number" step="0.01" class="form-control @error('price_min') is-invalid @enderror" id="price_min" name="price_min" value="{{ old('price_min') }}">
                        @error('price_min')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="date_expiration" class="form-label">Date d'Expiration</label>
                        <input type="date" class="form-control @error('date_expiration') is-invalid @enderror" id="date_expiration" name="date_expiration" value="{{ old('date_expiration') }}">
                        @error('date_expiration')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="status" class="form-label">Statut</label>
                        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="" disabled selected>Choisir un statut</option>
                            <option value="Disponible" {{ old('status') == 'Disponible' ? 'selected' : '' }}>Disponible</option>
                            <option value="Faible_stock" {{ old('status') == 'Faible_stock' ? 'selected' : '' }}>Faible stock</option>
                            <option value="En_rupture" {{ old('status') == 'En_rupture' ? 'selected' : '' }}>En rupture</option>
                            <option value="Expire" {{ old('status') == 'Expire' ? 'selected' : '' }}>Expiré</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="category_id" class="form-label">Catégorie</label>
                        <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="" disabled selected>Choisir une catégorie</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
