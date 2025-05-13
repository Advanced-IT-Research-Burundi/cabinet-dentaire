@extends('layouts.app')

@section('title', 'Types de traitements')

@section('content')
<div class="container-fluid">
    <div class="mb-4 row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="mb-0 h3">Types de traitements</h1>
                <a href="{{ route('settings.treatment-types.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Nouveau type
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Filtres de recherche -->
                    <div class="mb-4">
                        <form action="{{ route('settings.treatment-types.index') }}" method="GET" class="row g-3">
                            <div class="col-md-3">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                                           placeholder="Rechercher par code ou nom...">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select" name="category">
                                    <option value="">Toutes les catégories</option>
                                    @foreach($categories ?? [] as $category)
                                        <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select" name="status">
                                    <option value="">Tous les statuts</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <span class="input-group-text">Prix min</span>
                                    <input type="number" class="form-control" name="min_price" value="{{ request('min_price') }}"
                                           placeholder="Min">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <span class="input-group-text">Prix max</span>
                                    <input type="number" class="form-control" name="max_price" value="{{ request('max_price') }}"
                                           placeholder="Max">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-filter me-1"></i> Filtrer
                                </button>
                            </div>
                        </form>

                        <!-- Bouton de réinitialisation -->
                        <div class="mt-2 text-end">
                            <a href="{{ route('settings.treatment-types.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Réinitialiser les filtres
                            </a>
                            @if(request()->anyFilled(['search', 'category', 'status', 'min_price', 'max_price', 'sort', 'direction']))
                                <span class="ms-2 badge bg-info">
                                    <i class="bi bi-funnel-fill me-1"></i> Filtres actifs
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($treatmentTypes->isEmpty())
                        <div class="py-5 text-center">
                            <i class="mb-3 bi bi-clipboard2-x fs-1 text-muted"></i>
                            <p class="mb-0 text-muted">Aucun type de traitement trouvé</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table align-middle table-hover">
                                <thead>
                                    <tr>
                                        <th>
                                            <a href="{{ route('settings.treatment-types.index', array_merge(request()->except('sort', 'direction'), [
                                                'sort' => 'code',
                                                'direction' => request('sort') == 'code' && request('direction') == 'asc' ? 'desc' : 'asc'
                                            ])) }}" class="text-decoration-none text-dark">
                                                Code
                                                @if(request('sort') == 'code')
                                                    <i class="bi {{ request('direction') == 'asc' ? 'bi-sort-alpha-down' : 'bi-sort-alpha-up' }}"></i>
                                                @else
                                                    <i class="bi bi-arrow-down-up text-muted"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th>
                                            <a href="{{ route('settings.treatment-types.index', array_merge(request()->except('sort', 'direction'), [
                                                'sort' => 'name',
                                                'direction' => request('sort') == 'name' && request('direction') == 'asc' ? 'desc' : 'asc'
                                            ])) }}" class="text-decoration-none text-dark">
                                                Nom
                                                @if(request('sort') == 'name')
                                                    <i class="bi {{ request('direction') == 'asc' ? 'bi-sort-alpha-down' : 'bi-sort-alpha-up' }}"></i>
                                                @else
                                                    <i class="bi bi-arrow-down-up text-muted"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th>
                                            <a href="{{ route('settings.treatment-types.index', array_merge(request()->except('sort', 'direction'), [
                                                'sort' => 'category',
                                                'direction' => request('sort') == 'category' && request('direction') == 'asc' ? 'desc' : 'asc'
                                            ])) }}" class="text-decoration-none text-dark">
                                                Catégorie
                                                @if(request('sort') == 'category')
                                                    <i class="bi {{ request('direction') == 'asc' ? 'bi-sort-alpha-down' : 'bi-sort-alpha-up' }}"></i>
                                                @else
                                                    <i class="bi bi-arrow-down-up text-muted"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th>
                                            <a href="{{ route('settings.treatment-types.index', array_merge(request()->except('sort', 'direction'), [
                                                'sort' => 'base_price',
                                                'direction' => request('sort') == 'base_price' && request('direction') == 'asc' ? 'desc' : 'asc'
                                            ])) }}" class="text-decoration-none text-dark">
                                                Prix de base (BIF)
                                                @if(request('sort') == 'base_price')
                                                    <i class="bi {{ request('direction') == 'asc' ? 'bi-sort-numeric-down' : 'bi-sort-numeric-up' }}"></i>
                                                @else
                                                    <i class="bi bi-arrow-down-up text-muted"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th>
                                            <a href="{{ route('settings.treatment-types.index', array_merge(request()->except('sort', 'direction'), [
                                                'sort' => 'average_duration',
                                                'direction' => request('sort') == 'average_duration' && request('direction') == 'asc' ? 'desc' : 'asc'
                                            ])) }}" class="text-decoration-none text-dark">
                                                Durée (min)
                                                @if(request('sort') == 'average_duration')
                                                    <i class="bi {{ request('direction') == 'asc' ? 'bi-sort-numeric-down' : 'bi-sort-numeric-up' }}"></i>
                                                @else
                                                    <i class="bi bi-arrow-down-up text-muted"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th>
                                            <a href="{{ route('settings.treatment-types.index', array_merge(request()->except('sort', 'direction'), [
                                                'sort' => 'active',
                                                'direction' => request('sort') == 'active' && request('direction') == 'asc' ? 'desc' : 'asc'
                                            ])) }}" class="text-decoration-none text-dark">
                                                Statut
                                                @if(request('sort') == 'active')
                                                    <i class="bi {{ request('direction') == 'asc' ? 'bi-sort-down' : 'bi-sort-up' }}"></i>
                                                @else
                                                    <i class="bi bi-arrow-down-up text-muted"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($treatmentTypes as $type)
                                        <tr>
                                            <td><code>{{ $type->code ?? '-' }}</code></td>
                                            <td>{{ $type->name }}</td>
                                            <td>{{ $type->category ?? '-' }}</td>
                                            <td>{{ number_format($type->base_price ?? 0, 0, ',', ' ') }}</td>
                                            <td>{{ $type->average_duration ?? '-' }}</td>
                                            <td>
                                                @if($type->active)
                                                    <span class="badge bg-success">Actif</span>
                                                @else
                                                    <span class="badge bg-danger">Inactif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('settings.treatment-types.edit', $type) }}"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    {{-- <form action="{{ route('settings.treatment-types.destroy', $type) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce type de traitement ?')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form> --}}
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">
                                    Affichage de {{ $treatmentTypes->firstItem() ?? 0 }} à {{ $treatmentTypes->lastItem() ?? 0 }}
                                    sur {{ $treatmentTypes->total() }} entrées
                                </small>
                            </div>
                            <div>
                                {{ $treatmentTypes->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
