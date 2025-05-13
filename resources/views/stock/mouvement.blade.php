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
                            <p><strong>Catégorie:</strong> {{ $stock->category->name }}</p>
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
                    <form action="{{ route('mouvements_stocks.store') }}" method="POST">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="item_code" value="{{ $stock->id }}">
                        <input type="hidden" name="stock_id" value="{{ $stock->id }}">
                        <input type="hidden" name="item_measurement_unit" value="{{ $stock->unit_measure ?: 'unit' }}">
                        <input type="hidden" name="item_designation" value="{{ $stock->product_name }}">
                        <div class="mb-3">
                            <label for="item_movement_type" class="form-label">Type de mouvement</label>
                            <select name="item_movement_type" id="item_movement_type" class="form-select">
                                @foreach(MOUVEMENT_STOCK as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('item_movement_type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="item_movement_date" class="form-label">Date</label>
                            <input type="date" name="item_movement_date" id="item_movement_date" value="{{ now()->format('Y-m-d') }}" class="form-control" required>
                            @error('item_movement_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="item_quantity" class="form-label">Quantité</label>
                            <input type="number" step="0.01" name="item_quantity" id="item_quantity" class="form-control" value="{{ old('item_quantity') }}"  required>
                            @error('item_quantity')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="item_purchase_or_sale_price" class="form-label">Prix unitaire

                            </label>
                            <input type="number" step="0.01" name="item_purchase_or_sale_price" id="item_purchase_or_sale_price" class="form-control" value="{{ old('item_purchase_or_sale_price') ?? $stock->price }}"  required>
                            @error('item_purchase_or_sale_price')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="item_movement_description" class="form-label">Description</label>
                            <textarea name="item_movement_description" id="item_movement_description" class="form-control" rows="3">{{ old('item_movement_description') }}</textarea>
                            @error('item_movement_description')
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
                                    <th>Id</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Quantité</th>
                                    <th>Prix unitaire</th>
                                    <th>Total</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mouvements as $mouvement)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $mouvement->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                                        <td>{{ $mouvement->item_movement_type }}</td>
                                        <td>{{ $mouvement->item_quantity }} {{ $stock->unit_measure }}</td>
                                        <td>{{ number_format($mouvement->item_purchase_or_sale_price, 2) }} FBU</td>
                                        <td>{{ number_format($mouvement->item_quantity * $mouvement->item_purchase_or_sale_price, 2) }} FBU</td>
                                        <td>{{ $mouvement->item_movement_description }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $mouvements->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
