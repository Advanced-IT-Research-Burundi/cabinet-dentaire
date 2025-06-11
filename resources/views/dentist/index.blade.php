{{-- index.blade.php - Liste des dentistes avec recherche et filtrage --}}

@extends('layouts.app')

@section('content')
<div class="container-fluid py-0">
    <!-- En-tête avec titre et bouton d'ajout -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-primary">
                <i class="bi bi-people-fill me-2"></i>Gestion des Dentistes
            </h1>
            <p class="text-muted mb-0">{{ $dentists->total() }} dentiste(s) trouvé(s)</p>
        </div>
        <a href="{{ route('dentists.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus-fill me-1"></i> Nouveau Dentiste
        </a>
    </div>

    <!-- Messages de notification -->
    {{-- @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif --}}

    <!-- Filtres et recherche -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('dentists.index') }}" method="GET" id="search-form">
                <div class="row g-3">
                    <!-- Recherche par nom/email -->
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" placeholder="Rechercher par nom ou email"
                                aria-label="Recherche" name="search" value="{{ request('search') }}">
                        </div>
                    </div>

                    <!-- Filtre par spécialité -->
                    <div class="col-md-3">
                    <select class="form-select" name="specialty">
                        <option value="">Toutes les spécialités</option>
                        @foreach($specialties as $specialty)
                            @if(strlen($specialty) <= 50 && !str_contains($specialty, 'error'))
                                <option value="{{ $specialty }}" {{ request('specialty') == $specialty ? 'selected' : '' }}>
                                    {{ Str::limit($specialty, 40) }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                    <!-- Filtre par statut -->
                    <div class="col-md-2">
                        <select class="form-select" name="status"
                        {{-- onchange="this.form.submit()" --}}
                        >
                            <option value="">Tous les statuts</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Disponible</option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Non disponible</option>
                        </select>
                    </div>

                    <!-- Boutons de recherche et réinitialisation -->
                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="bi bi-filter me-1"></i> Filtrer
                            </button>
                            <a href="{{ route('dentists.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i> Réinitialiser
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau des dentistes -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            @if($dentists->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>
                                    <a href="{{ route('dentists.index', array_merge(request()->query(), ['sort' => 'name', 'direction' => request('direction') == 'asc' && request('sort') == 'name' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark">
                                        Nom
                                        @if(request('sort') == 'name')
                                            <i class="bi bi-caret-{{ request('direction') == 'asc' ? 'up' : 'down' }}-fill ms-1 small"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ route('dentists.index', array_merge(request()->query(), ['sort' => 'specialty', 'direction' => request('direction') == 'asc' && request('sort') == 'specialty' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark">
                                        Spécialité
                                        @if(request('sort') == 'specialty')
                                            <i class="bi bi-caret-{{ request('direction') == 'asc' ? 'up' : 'down' }}-fill ms-1 small"></i>
                                        @endif
                                    </a>
                                </th>
                                <th>N° Licence</th>
                                <th>Rendez-vous</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dentists as $dentist)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3" style="background-color: {{ $dentist->calendar_color }}">
                                                <span class="avatar-initials">
                                                    {{ substr($dentist->user->first_name ?? '', 0, 1) }}{{ substr($dentist->user->last_name ?? '', 0, 1) }}
                                                </span>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $dentist->user->first_name }} {{ $dentist->user->last_name }}</div>
                                                <div class="text-muted small">{{ $dentist->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill text-bg-light border border-primary border-opacity-25 text-primary">
                                            {{ $dentist->specialty }}
                                        </span>
                                    </td>
                                    <td>{{ $dentist->license_number }}</td>
                                    <td>
                                        <span class="badge rounded-pill text-bg-light">
                                            {{ $dentist->appointments_count ?? 0 }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($dentist->available)
                                            <span class="badge text-bg-success">
                                                <i class="bi bi-check-circle me-1"></i>Disponible
                                            </span>
                                        @else
                                            <span class="badge text-bg-danger">
                                                <i class="bi bi-x-circle me-1"></i>Non disponible
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group float-end">
                                            <a href="{{ route('dentists.show', $dentist) }}" class="btn btn-sm btn-outline-primary" title="Voir le profil">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('dentists.edit', $dentist) }}" class="btn btn-sm btn-outline-secondary" title="Modifier">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="modal" data-bs-target="#deleteModal{{ $dentist->id }}" title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>

                                        <!-- Modal de confirmation de suppression -->
                                        <div class="modal fade" id="deleteModal{{ $dentist->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $dentist->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel{{ $dentist->id }}">Confirmer la suppression</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Êtes-vous sûr de vouloir supprimer le dentiste <strong>{{ $dentist->user->first_name }} {{ $dentist->user->last_name }}</strong> ?</p>
                                                        <p class="text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i>Cette action est irréversible.</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                        <form action="{{ route('dentists.destroy', $dentist) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Supprimer</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center py-3">
                    {{ $dentists->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-search fs-1 text-muted"></i>
                    <p class="mt-3">Aucun dentiste trouvé pour ces critères de recherche.</p>
                    @if(request()->query())
                        <a href="{{ route('dentists.index') }}" class="btn btn-outline-primary mt-2">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>Réinitialiser les filtres
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.avatar-sm {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.avatar-initials {
    color: white;
    font-weight: bold;
}
</style>

@endsection
