@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <!-- Informations du produit -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        Informations du produit
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><i class="bi bi-tag-fill me-2 text-primary"></i><strong>Nom:</strong> {{ $stock->product_name }}</p>
                            <p><i class="bi bi-upc-scan me-2 text-secondary"></i><strong>Code:</strong> {{ $stock->code_product }}</p>
                            <p><i class="bi bi-award-fill me-2 text-warning"></i><strong>Marque:</strong> {{ $stock->marque }}</p>
                            <p><i class="bi bi-boxes me-2 text-success"></i><strong>Quantité disponible:</strong> {{ $stock->available_quantity }} {{ $stock->unit_measure }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><i class="bi bi-grid-3x3-gap-fill me-2 text-info"></i><strong>Catégorie:</strong> {{ $stock->category->name }}</p>
                            <p><i class="bi bi-currency-dollar me-2 text-success"></i><strong>Prix:</strong> {{ $stock->price }} FBU</p>
                            <p><i class="bi bi-activity me-2"></i><strong>Statut:</strong>
                                <span class="badge bg-{{ $stock->status === 'Faible_stock' ? 'warning' : 'success' }}">
                                    <i class="bi bi-{{ $stock->status === 'Faible_stock' ? 'exclamation-triangle' : 'check-circle' }} me-1"></i>
                                    {{ $stock->status }}
                                </span>
                            </p>
                            <p><i class="bi bi-geo-alt-fill me-2 text-danger"></i><strong>Emplacement:</strong> {{ $stock->location }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulaire de mouvement -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="bi bi-plus-circle-fill me-2"></i>
                        Nouveau mouvement de stock
                    </h5>
                </div>
                <div>
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <ul class="mb-0">
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
                        <input type="hidden" name="item_measurement_unit" value="{{ $stock->unite_mesure ?: '-' }}">
                        <input type="hidden" name="item_designation" value="{{ $stock->product_name }}">

                        <div class="mb-3">
                            <label for="item_movement_type" class="form-label">
                                <i class="bi bi-arrow-left-right me-2"></i>Type de mouvement
                            </label>
                            <select name="item_movement_type" id="item_movement_type" class="form-select">
                                @foreach(MOUVEMENT_STOCK as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('item_movement_type')
                                <div class="text-danger">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="item_movement_date" class="form-label">
                                <i class="bi bi-calendar-date me-2"></i>Date
                            </label>
                            <input type="date" name="item_movement_date" id="item_movement_date" value="{{ now()->format('Y-m-d') }}" class="form-control" required>
                            @error('item_movement_date')
                                <div class="text-danger">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="item_quantity" class="form-label">
                                <i class="bi bi-123 me-2"></i>Quantité
                            </label>

                            <input type="number" step="0.01" name="item_quantity" id="item_quantity" class="form-control" value="{{ old('item_quantity') }}"
                            oninput="this.value = Math.max(0, this.value)"
                            required>
                            @error('item_quantity')
                                <div class="text-danger">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="item_purchase_or_sale_price" class="form-label">
                                <i class="bi bi-cash-coin me-2"></i>Prix unitaire
                            </label>
                            <input type="number" step="0.01" name="item_purchase_or_sale_price" id="item_purchase_or_sale_price" class="form-control" value="{{ old('item_purchase_or_sale_price') ?? $stock->price }}"
                            oninput="this.value = Math.max(0, this.value)"
                            required>
                            @error('item_purchase_or_sale_price')
                                <div class="text-danger">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="item_movement_description" class="form-label">
                                <i class="bi bi-chat-left-text me-2"></i>Description
                            </label>
                            <textarea name="item_movement_description" id="item_movement_description" class="form-control" rows="3">{{ old('item_movement_description') }}</textarea>
                            @error('item_movement_description')
                                <div class="text-danger">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Enregistrer le mouvement
                        </button>
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
                    <h5 class="card-title">
                        <i class="bi bi-clock-history me-2"></i>
                        Historique des mouvements
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th><i class="bi bi-hash me-1"></i>Id</th>
                                    <th><i class="bi bi-calendar3 me-1"></i>Date</th>
                                    <th><i class="bi bi-arrow-left-right me-1"></i>Type</th>
                                    <th><i class="bi bi-boxes me-1"></i>Quantité</th>
                                    <th><i class="bi bi-cash me-1"></i>Prix unitaire</th>
                                    <th><i class="bi bi-calculator me-1"></i>Total</th>
                                    <th><i class="bi bi-chat-left-text me-1"></i>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($mouvements as $mouvement)
                                    <tr>
                                        <td>
                                            <span class="badge bg-secondary">{{ $loop->iteration }}</span>
                                        </td>
                                        <td>
                                            <i class="bi bi-calendar-check me-1 text-muted"></i>
                                            {{ $mouvement->created_at?->format('d/m/Y H:i') ?? '-' }}
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                <i class="bi bi-tag me-1"></i>
                                                {{ $mouvement->item_movement_type }}
                                            </span>
                                        </td>
                                        <td>
                                            <i class="bi bi-box me-1 text-primary"></i>
                                            {{ $mouvement->item_quantity }} {{ $stock->unit_measure }}
                                        </td>
                                        <td>
                                            <i class="bi bi-currency-dollar me-1 text-success"></i>
                                            {{ number_format($mouvement->item_purchase_or_sale_price, 2) }} FBU
                                        </td>
                                        <td>
                                            <strong class="text-success">
                                                <i class="bi bi-cash-stack me-1"></i>
                                                {{ number_format($mouvement->item_quantity * $mouvement->item_purchase_or_sale_price, 2) }} FBU
                                            </strong>
                                        </td>
                                        <td>
                                            @if($mouvement->item_movement_description)
                                                <i class="bi bi-chat-square-text me-1 text-muted"></i>
                                                {{ $mouvement->item_movement_description }}
                                            @else
                                                <span class="text-muted">
                                                    <i class="bi bi-dash-circle me-1"></i>
                                                    Aucune description
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-4 text-center text-muted">
                                            <i class="mb-2 bi bi-inbox display-4 d-block"></i>
                                            Aucun mouvement de stock enregistré
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        @if(method_exists($mouvements, 'links'))
                            <div class="d-flex justify-content-center">
                                {{ $mouvements->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
