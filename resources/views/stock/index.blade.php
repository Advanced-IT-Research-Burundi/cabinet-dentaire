@extends('layouts.app')

@section('title', 'Liste des Stocks')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Liste des Stocks</h1>
        <a href="{{ route('stocks.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle-fill"></i> Nouveau Stock
        </a>
    </div>

    <!-- Search and Filter Card -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('stocks.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}" 
                               placeholder="Rechercher par nom de produit, catégorie...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Tous les Status</option>
                        <option value="Disponible" {{ request('status') == "Disponible" ? 'selected' : '' }}>Disponible</option>
                        <option value="Faible_stock" {{ request('status') == "Faible_stock" ? 'selected' : '' }}>Faible stock</option>
                        <option value="En_rupture" {{ request('status') == "En_rupture" ? 'selected' : '' }}>En rupture</option>
                        <option value="Expire" {{ request('status') == "Expire" ? 'selected' : '' }}>Expiré</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Stocks Table Card -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Nom du Produit</th>
                            <th>Catégorie</th>
                            <th>Quantité Disponible</th>
                            <th>Prix d'Achat</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stocks as $stock)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary-subtle text-primary rounded-circle me-2 d-flex align-items-center justify-content-center">
                                            {{ strtoupper(substr($stock->product_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-medium">{{ $stock->product_name }}</div>
                                            <small class="text-muted">ID: {{ $stock->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $stock->category ?: '-' }}</td>
                                <td>{{ $stock->available_quantity }}</td>
                                <td>{{ $stock->purchase_price ? number_format($stock->purchase_price) . ' Fbu' : '-' }}</td>
                                <td>
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
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('stocks.show', $stock) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('stocks.edit', $stock) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                onclick="confirmDelete('{{ $stock->id }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    <form id="delete-form-{{ $stock->id }}" 
                                          action="{{ route('stocks.destroy', $stock) }}" 
                                          method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox-fill fs-2 d-block mb-2"></i>
                                        Aucun stock trouvé
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted small">
                    Affichage de {{ $stocks->firstItem() ?? 0 }} à {{ $stocks->lastItem() ?? 0 }} sur {{ $stocks->total() }} stocks
                </div>
                {{ $stocks->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(stockId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce stock ?')) {
        document.getElementById('delete-form-' + stockId).submit();
    }
}
</script>
@endpush
@endsection
