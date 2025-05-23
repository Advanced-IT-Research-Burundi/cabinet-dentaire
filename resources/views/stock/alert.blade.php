@extends('layouts.app')

@section('title', 'Liste des Stocks')

@section('content')
<div class="px-4 container-fluid">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h1 class="mb-0 text-gray-800 h3">Liste des Produits en Alert</h1>
        <a href="{{ route('stocks.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle-fill"></i> Nouveau Produit
        </a>
    </div>
    <!-- Search and Filter Card -->
    <div class="mb-4 card">
        <div class="card-body">
            <form action="{{ route('invoice.alert') }}" method="GET" class="row g-3">
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
                <table class="table align-middle table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom du Produit</th>
                            <th>Numéro du produit</th>
                            <th>Catégorie</th>
                            <th>Code Produit</th>
                            <th>Quantité</th>
                            <th>Prix</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stocks as $stock)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $stock->product_name }}</td>
                                <td> {{ $stock->id }}</td>
                                <td>{{ $stock->category->name }}</td>
                                <td>{{ $stock->code_product ?: '-' }}</td>
                                <td>
                                    {{ $stock->quantite }}
                                    @if($stock->quantite < $stock->quantite_alert)
                                        <span class="badge bg-danger">Faible stock</span>
                                    @endif
                                </td>
                                <td>{{ $stock->price ? number_format($stock->price, 2) . ' Fbu' : '-' }}</td>
                                <td>{{ $stock->status }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('stocks.show', $stock) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('stocks.edit', $stock) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="{{ route('stocks.movement', $stock) }}" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-clipboard"></i>
                                            Mouvement
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-4 text-center">
                                    <div class="text-muted">
                                        <i class="mb-2 bi bi-inbox-fill fs-2 d-block"></i>
                                        Aucun produits du stock en alerte trouvé
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 d-flex justify-content-between align-items-center">
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
