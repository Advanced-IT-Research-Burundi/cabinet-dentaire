@extends('layouts.app')

@section('title', 'Liste des Fournisseurs')

@section('content')
<div class="container-fluid px-4">
    <!-- En-tête avec statistiques -->
    <div class="row ">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="bi bi-building me-2 text-primary"></i>
                        Fournisseurs
                    </h1>
                    <p class="text-muted mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        Gestion de vos partenaires commerciaux
                    </p>
                </div>
                <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>
                    Ajouter un Fournisseur
                </a>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="card ">
        <div class="card-body">
            <form method="GET" action="{{ route('suppliers.index') }}" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control"
                               placeholder="Rechercher un fournisseur..."
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-funnel me-1"></i>
                        Filtrer
                    </button>
                </div>
                <div class="col-md-2 text-">
                    <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise me-1"></i>
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Table des fournisseurs -->
    <div class="card shadow">
        <div class="card-header bg-light">
            <h6 class="mb-0">
                <i class="bi bi-table me-2"></i>
                Liste des Fournisseurs
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">
                                <i class="bi bi-hash me-1"></i>
                                #
                            </th>
                            <th>
                                <i class="bi bi-building me-1"></i>
                                Nom
                            </th>
                            <th>
                                <i class="bi bi-envelope me-1"></i>
                                Email
                            </th>
                            <th>
                                <i class="bi bi-telephone me-1"></i>
                                Téléphone
                            </th>
                            <th>
                                <i class="bi bi-geo-alt me-1"></i>
                                Adresse
                            </th>
                            <th class="text-center">
                                <i class="bi bi-gear me-1"></i>
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($suppliers as $supplier)
                            <tr>
                                <td class="text-center">
                                    <span class="badge bg-secondary">{{ $loop->iteration }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <i class="bi bi-building"></i>
                                        </div>
                                        <div>
                                            <strong>{{ $supplier->name }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                <i class="bi bi-calendar-plus me-1"></i>
                                                Ajouté le {{ $supplier->created_at?->format('d/m/Y') ?? 'N/A' }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($supplier->email)
                                        <a href="mailto:{{ $supplier->email }}" class="text-decoration-none">
                                            <i class="bi bi-envelope-fill text-primary me-1"></i>
                                            {{ $supplier->email }}
                                        </a>
                                    @else
                                        <span class="text-muted">
                                            <i class="bi bi-envelope-slash me-1"></i>
                                            Non renseigné
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($supplier->phone)
                                        <a href="tel:{{ $supplier->phone }}" class="text-decoration-none">
                                            <i class="bi bi-telephone-fill text-success me-1"></i>
                                            {{ $supplier->phone }}
                                        </a>
                                    @else
                                        <span class="text-muted">
                                            <i class="bi bi-telephone-slash me-1"></i>
                                            Non renseigné
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($supplier->address)
                                        <i class="bi bi-geo-alt-fill text-warning me-1"></i>
                                        {{ Str::limit($supplier->address, 30) }}
                                        @if(strlen($supplier->address) > 30)
                                            <span class="text-muted" title="{{ $supplier->address }}">...</span>
                                        @endif
                                    @else
                                        <span class="text-muted">
                                            <i class="bi bi-geo-alt-slash me-1"></i>
                                            Non renseignée
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <!-- Bouton Voir -->
                                        <a href="{{ route('suppliers.show', $supplier->id) }}"
                                           class="btn btn-sm btn-outline-info"
                                           title="Voir les détails">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <!-- Bouton Modifier -->
                                        <a href="{{ route('suppliers.edit', $supplier->id) }}"
                                           class="btn btn-sm btn-outline-warning"
                                           title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <!-- Bouton Supprimer -->
                                        <form action="{{ route('suppliers.destroy', $supplier->id) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce fournisseur ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bi bi-building-x display-1 text-muted mb-3"></i>
                                        <h5 class="text-muted mb-2">Aucun fournisseur trouvé</h5>
                                        <p class="text-muted mb-3">Commencez par ajouter votre premier fournisseur</p>
                                        <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
                                            <i class="bi bi-plus-circle me-2"></i>
                                            Ajouter un Fournisseur
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if(method_exists($suppliers, 'links') && $suppliers->hasPages())
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Affichage de {{ $suppliers->firstItem() }} à {{ $suppliers->lastItem() }}
                        sur {{ $suppliers->total() }} résultats
                    </div>
                    {{ $suppliers->appends(request()->query())->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
.avatar {
    font-size: 1.2rem;
}
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}
.table th {
    font-weight: 600;
    border-top: none;
}
.btn-group .btn {
    border-radius: 0.375rem;
    margin-right: 2px;
}
.btn-group .btn:last-child {
    margin-right: 0;
}
</style>
@endpush
@endsection
