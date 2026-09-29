@extends('layouts.app')

@section('title', 'Types de Ventes & Catégories')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-tags-fill text-primary me-2"></i>Types de Ventes
            </h1>
            <p class="text-muted small mb-0">Gestion et classification des catégories de types de ventes</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('stocks.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Retour aux Stocks
            </a>
            <a href="{{ route('category-type-ventes.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle-fill me-1"></i> Nouveau Type de Vente
            </a>
        </div>
    </div>

    <!-- Search Card -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('category-type-ventes.index') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-md-9">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" class="form-control border-start-0 ps-0" name="search" value="{{ request('search') }}"
                               placeholder="Rechercher par nom de catégorie, description...">
                    </div>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="bi bi-funnel me-1"></i> Filtrer
                    </button>
                    @if(request('search'))
                        <a href="{{ route('category-type-ventes.index') }}" class="btn btn-outline-secondary" title="Réinitialiser">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width: 70px;">#</th>
                            <th>Nom du Type de Vente</th>
                            <th>Description</th>
                            <th>Date de création</th>
                            <th class="text-end pe-4" style="width: 160px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categoryTypeVentes as $typeVente)
                            <tr>
                                <td class="ps-4 fw-semibold text-muted">{{ $loop->iteration + ($categoryTypeVentes->currentPage() - 1) * $categoryTypeVentes->perPage() }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary-subtle text-primary rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;">
                                            <i class="bi bi-tag-fill"></i>
                                        </div>
                                        <div>
                                            <span class="fw-bold text-dark">{{ $typeVente->name }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($typeVente->description)
                                        <span class="text-secondary">{{ $typeVente->description }}</span>
                                    @else
                                        <span class="text-muted fst-italic">Aucune description</span>
                                    @endif
                                </td>
                                <td class="text-muted small">
                                    {{ $typeVente->created_at ? $typeVente->created_at->format('d/m/Y H:i') : '-' }}
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('category-type-ventes.show', $typeVente) }}" class="btn btn-outline-info" title="Détails">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('category-type-ventes.edit', $typeVente) }}" class="btn btn-outline-secondary" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger" title="Supprimer" onclick="confirmDelete({{ $typeVente->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    <form id="delete-form-{{ $typeVente->id }}" action="{{ route('category-type-ventes.destroy', $typeVente) }}" method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                        <p class="mb-1 fw-semibold">Aucun type de vente trouvé</p>
                                        <small class="text-muted">Créez votre première catégorie de vente pour commencer.</small>
                                        <div class="mt-3">
                                            <a href="{{ route('category-type-ventes.create') }}" class="btn btn-sm btn-primary">
                                                <i class="bi bi-plus-circle me-1"></i> Créer maintenant
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($categoryTypeVentes->hasPages() || $categoryTypeVentes->total() > 0)
                <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3 px-4">
                    <div class="text-muted small">
                        Affichage de {{ $categoryTypeVentes->firstItem() ?? 0 }} à {{ $categoryTypeVentes->lastItem() ?? 0 }} sur {{ $categoryTypeVentes->total() }} éléments
                    </div>
                    <div>
                        {{ $categoryTypeVentes->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce type de vente ?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush
@endsection
