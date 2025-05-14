@extends('layouts.app')

@section('title', 'Liste des Assurances')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header   d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Assurances</h4>
            <a href="{{ route('assurances.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus-circle"></i> Ajouter une Assurance
            </a>
        </div>

        <div class="card-body">
            <!-- Filtres -->
            <div class="mb-4">
                <form action="{{ route('assurances.index') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text">Nom</span>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Rechercher...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text">Couverture min</span>
                            <input type="number" class="form-control" name="min_coverage" value="{{ request('min_coverage') }}" placeholder="Min %">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text">Couverture max</span>
                            <input type="number" class="form-control" name="max_coverage" value="{{ request('max_coverage') }}" placeholder="Max %">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-filter"></i> Filtrer
                        </button>
                        <a href="{{ route('assurances.index') }}" class="btn btn-secondary">
                            <i class="fas fa-sync"></i> Réinitialiser
                        </a>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>
                                <a href="{{ route('assurances.index', array_merge(request()->except('sort', 'direction'), [
                                    'sort' => 'name',
                                    'direction' => request('sort') == 'name' && request('direction') == 'asc' ? 'desc' : 'asc'
                                ])) }}" class="text-decoration-none text-dark">
                                    Nom
                                    @if(request('sort') == 'name')
                                        <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ route('assurances.index', array_merge(request()->except('sort', 'direction'), [
                                    'sort' => 'coverage_percentage',
                                    'direction' => request('sort') == 'coverage_percentage' && request('direction') == 'asc' ? 'desc' : 'asc'
                                ])) }}" class="text-decoration-none text-dark">
                                    Pourcentage de Couverture
                                    @if(request('sort') == 'coverage_percentage')
                                        <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </a>
                            </th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($assurances as $assurance)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $assurance->name }}</td>
                                <td>{{ $assurance->coverage_percentage }}%</td>
                                <td>{{ $assurance->description ?: '-' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('assurances.edit', $assurance) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('assurances.destroy', $assurance) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette assurance?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">

                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-3">
                                    <div class="alert alert-info mb-0">
                                        <i class="fas fa-info-circle me-2"></i> Aucune assurance trouvée.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $assurances->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
