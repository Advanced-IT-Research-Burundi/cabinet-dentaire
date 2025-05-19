@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Utilisateurs</h1>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nouvel Utilisateur
        </a>
    </div>

    <!-- Search and Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('users.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control" name="search"
                               placeholder="Rechercher..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="role" class="form-select">
                        <option value="">Tous les rôles</option>
                        <option value="Admin" {{ request('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                        <option value="Dentiste" {{ request('role') == 'Dentiste' ? 'selected' : '' }}>Dentiste</option>
                        <option value="Secretaire" {{ request('role') == 'Secretaire' ? 'selected' : '' }}>Secrétaire</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="statut" class="form-select">
                        <option value="">Tous les statuts</option>
                        <option value="Actif" {{ request('statut') == 'Actif' ? 'selected' : '' }}>Actif</option>
                        <option value="Inactif" {{ request('statut') == 'Inactif' ? 'selected' : '' }}>Inactif</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel"></i> Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="card-body">
            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Photo</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Rôle</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>
                                        @if($user->photo_url)
                                            <img src="{{ Storage::url($user->photo_url) }}" alt="Photo" class="rounded-circle" width="40" height="40">
                                        @else
                                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white"
                                                 style="width: 40px; height: 40px;">
                                                {{ strtoupper(substr($user->first_name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $user->name }}
                                        {{ $user->first_name }}
                                         {{ $user->last_name }}
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone ?: '-' }}</td>
                                    <td>
                                        @switch($user->role)
                                            @case('Admin')
                                                <span class="badge bg-danger">Admin</span>
                                                @break
                                            @case('Dentiste')
                                                <span class="badge bg-primary">Dentiste</span>
                                                @break
                                            @case('Secretaire')
                                                <span class="badge bg-info">Secrétaire</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        @if($user->statut === 'Actif')
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-warning">Inactif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            @if($user->id !== Auth::id())
                                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            @else
                <p class="text-center text-muted my-5">Aucun utilisateur trouvé</p>
            @endif
        </div>
    </div>
</div>
@endsection
