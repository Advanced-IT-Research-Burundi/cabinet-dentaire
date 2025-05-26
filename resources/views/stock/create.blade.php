@extends('layouts.app')

@section('title', 'Créer un Nouveau Stock')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="bi bi-plus-circle me-2"></i>Créer un Nouveau Produit
        </h1>
        <a href="{{ route('stocks.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left-circle me-1"></i> Retour à la liste
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li><i class="bi bi-exclamation-circle-fill text-danger me-1"></i>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('stocks.store') }}" method="POST">
                @csrf
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                <div class="row row-cols-1 row-cols-md-3 g-3">
                    <div class="col">
                        <label for="code_product" class="form-label">
                            <i class="bi bi-upc-scan me-1"></i>Code Produit
                        </label>
                        <input type="text" class="form-control @error('code_product') is-invalid @enderror" id="code_product" name="code_product" value="{{ old('code_product') }}">
                        @error('code_product')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col">
                        <label for="product_name" class="form-label">
                            <i class="bi bi-box-seam me-1"></i>Nom du Produit
                        </label>
                        <input type="text" class="form-control @error('product_name') is-invalid @enderror" id="product_name" name="product_name" value="{{ old('product_name') }}">
                        @error('product_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col">
                        <label for="marque" class="form-label">
                            <i class="bi bi-tags me-1"></i>Marque
                        </label>
                        <input type="text" class="form-control @error('marque') is-invalid @enderror" id="marque" name="marque" value="{{ old('marque') }}">
                        @error('marque')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col">
                        <label for="unite_mesure" class="form-label">
                            <i class="bi bi-rulers me-1"></i>Unité de Mesure
                        </label>
                        <input type="text" class="form-control @error('unite_mesure') is-invalid @enderror" id="unite_mesure" name="unite_mesure" value="{{ old('unite_mesure') }}">
                        @error('unite_mesure')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <input type="hidden" step="0.01" class="form-control @error('quantite') is-invalid @enderror" id="quantite" name="quantite" value="0" required>

                    <div class="col">
                        <label for="quantite_alert" class="form-label">
                            <i class="bi bi-exclamation-triangle me-1"></i>Quantité d'Alerte
                        </label>
                        <input type="number" step="0.01" class="form-control @error('quantite_alert') is-invalid @enderror" id="quantite_alert" name="quantite_alert" value="{{ old('quantite_alert') }}">
                        @error('quantite_alert')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col">
                        <label for="price" class="form-label">
                            <i class="bi bi-currency-dollar me-1"></i>Prix
                        </label>
                        <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}">
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col">
                        <label for="price_ttc" class="form-label">
                            <i class="bi bi-currency-dollar me-1"></i>Prix TTC
                        </label>
                        <input type="number" step="0.01" class="form-control @error('price_ttc') is-invalid @enderror" id="price_ttc" name="price_ttc" value="{{ old('price_ttc', 0) }}">
                        @error('price_ttc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col">
                        <label for="price_max" class="form-label">
                            <i class="bi bi-currency-dollar me-1"></i>Prix Maximum
                        </label>
                        <input type="number" step="0.01" class="form-control @error('price_max') is-invalid @enderror" id="price_max" name="price_max" value="{{ old('price_max', 0) }}">
                        @error('price_max')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col">
                        <label for="price_tvac" class="form-label">
                            <i class="bi bi-currency-dollar me-1"></i>Prix TVAC
                        </label>
                        <input type="number" step="0.01" class="form-control @error('price_tvac') is-invalid @enderror" id="price_tvac" name="price_tvac" value="{{ old('price_tvac', 0) }}">
                        @error('price_tvac')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col">
                        <label for="taux_tva" class="form-label">
                            <i class="bi bi-percent me-1"></i>Taux TVA
                        </label>
                        <input type="number" step="0.01" class="form-control @error('taux_tva') is-invalid @enderror" id="taux_tva" name="taux_tva" value="{{ old('taux_tva', 0) }}">
                        @error('taux_tva')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col">
                        <label for="item_ott_tax" class="form-label">
                            <i class="bi bi-receipt me-1"></i>Taxe OTT
                        </label>
                        <input type="number" step="0.01" class="form-control @error('item_ott_tax') is-invalid @enderror" id="item_ott_tax" name="item_ott_tax" value="{{ old('item_ott_tax', 0) }}">
                        @error('item_ott_tax')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col">
                        <label for="item_tsce_tax" class="form-label">
                            <i class="bi bi-receipt me-1"></i>Taxe TSCE
                        </label>
                        <input type="number" step="0.01" class="form-control @error('item_tsce_tax') is-invalid @enderror" id="item_tsce_tax" name="item_tsce_tax" value="{{ old('item_tsce_tax', 0) }}">
                        @error('item_tsce_tax')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col">
                        <label for="price_min" class="form-label">
                            <i class="bi bi-currency-dollar me-1"></i>Prix Minimum
                        </label>
                        <input type="number" step="0.01" class="form-control @error('price_min') is-invalid @enderror" id="price_min" name="price_min" value="{{ old('price_min', 0) }}">
                        @error('price_min')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col">
                        <label for="date_expiration" class="form-label">
                            <i class="bi bi-calendar-event me-1"></i>Date d'Expiration
                        </label>
                        <input type="date" class="form-control @error('date_expiration') is-invalid @enderror" id="date_expiration" name="date_expiration" value="{{ old('date_expiration') }}">
                        @error('date_expiration')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col">
                        <label for="status" class="form-label">
                            <i class="bi bi-info-circle me-1"></i>Statut
                        </label>
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

                    <div class="col">
                        <label for="category_id" class="form-label">
                            <i class="bi bi-folder me-1"></i>Catégorie
                        </label>
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

                    <div class="col">
                        <label for="supplier_id" class="form-label">
                            <i class="bi bi-truck me-1"></i>Fournisseur
                        </label>
                        <select id="supplier_id" name="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror">
                            <option value="" disabled selected>Choisir un fournisseur</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label">
                            <i class="bi bi-card-text me-1"></i>Description
                        </label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="reset" class="btn btn-secondary me-2">
                        <i class="bi bi-arrow-clockwise me-1"></i> Réinitialiser
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save2 me-1"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
