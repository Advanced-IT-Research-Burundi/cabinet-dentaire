@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <!-- Informations du produit -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Informations du produit</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nom:</strong> {{ $stock->product_name }}</p>
                            <p><strong>Code:</strong> {{ $stock->code_product }}</p>
                            <p><strong>Marque:</strong> {{ $stock->marque }}</p>
                            <p><strong>Quantité disponible:</strong> {{ $stock->available_quantity }} {{ $stock->unit_measure }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Catégorie:</strong> {{ $stock->category }}</p>
                            <p><strong>Prix:</strong> {{ $stock->price }} FBU</p>
                            <p><strong>Statut:</strong> <span class="badge bg-{{ $stock->status === 'Faible_stock' ? 'warning' : 'success' }}">{{ $stock->status }}</span></p>
                            <p><strong>Emplacement:</strong> {{ $stock->location }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulaire de mouvement -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Nouveau mouvement de stock</h5>
                </div>
                <div>
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <form action="{{ route('stock_movements.store') }}" method="POST">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="stock_id" value="{{ $stock->id }}">
                        <div class="mb-3">
                            <label for="type" class="form-label">Type de mouvement</label>
                            <select name="type" id="type" class="form-select">
                                @foreach(MOUVEMENT_STOCK as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" name="date" id="date" value="{{ now()->format('Y-m-d') }}" class="form-control" required>
                            @error('date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantité</label>
                            <input type="number" step="0.01" name="quantity" id="quantity" class="form-control" value="{{ old('quantity') }}"  required>
                            @error('quantity')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Prix unitaire</label>
                            <input type="number" step="0.01" name="price" id="price" class="form-control" value="{{ old('price') }}" required>
                            @error('price')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Enregistrer le mouvement</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Historique des mouvements -->
    <div class="mt-4 row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Historique des mouvements</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Quantité</th>
                                    <th>Prix unitaire</th>
                                    <th>Total</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($movements as $movement)
                                    <tr>
                                        <td>{{ $movement->date->format('d/m/Y') }}</td>
                                        <td>{{ config('constants.MOUVEMENT_STOCK.' . $movement->type) }}</td>
                                        <td>{{ $movement->quantity }} {{ $stock->unit_measure }}</td>
                                        <td>{{ number_format($movement->price, 2) }} FBU</td>
                                        <td>{{ number_format($movement->quantity * $movement->price, 2) }} FBU</td>
                                        <td>{{ $movement->description }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection