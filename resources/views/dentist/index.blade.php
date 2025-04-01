@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Gestion des Dentistes</h1>
        <a href="{{ route('dentists.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus-fill me-1"></i> Nouveau Dentiste
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Spécialité</th>
                            <th>N° Licence</th>
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
                                            <span class="avatar-initials">{{ substr($dentist->user->prenom, 0, 1) }}{{ substr($dentist->user->nom, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $dentist->user->prenom }} {{ $dentist->user->nom }}</div>
                                            <div class="text-muted small">{{ $dentist->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $dentist->specialty }}</td>
                                <td>{{ $dentist->license_number }}</td>
                                <td>
                                    @if($dentist->is_active)
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-danger">Inactif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group float-end">
                                        <a href="{{ route('dentists.show', $dentist) }}" class="btn btn-sm btn-outline-primary" title="Voir">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('dentists.edit', $dentist) }}" class="btn btn-sm btn-outline-secondary" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('dentists.destroy', $dentist) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce dentiste ?')" title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
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
