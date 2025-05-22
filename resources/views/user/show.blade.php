@extends('layouts.app')

@section('title', 'Détails de l\'Utilisateur')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $user->name }}</h1>
        <div>
            <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Modifier
            </a>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Informations de base -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-person-vcard me-1"></i> Informations de base
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center mb-4">
                        <div class="col-auto">
                            @if($user->photo_url)
                                <img src="{{ Storage::url($user->photo_url) }}" alt="Photo" class="rounded-circle" width="100" height="100">
                            @else
                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white"
                                     style="width: 100px; height: 100px; font-size: 2.5rem;">
                                    {{ strtoupper(substr($user->first_name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="col">
                            <h4 class="mb-1">{{ $user->first_name }} {{ $user->last_name }}</h4>
                            <p class="mb-0">
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
                                @if($user->statut === 'Actif')
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-warning">Inactif</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="fw-bold">Email</label>
                            <p>{{ $user->email }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label class="fw-bold">Téléphone</label>
                            <p>{{ $user->phone ?: '-' }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label class="fw-bold">Email secondaire</label>
                            <p>{{ $user->secondary_email ?: '-' }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label class="fw-bold">Téléphone secondaire</label>
                            <p>{{ $user->secondary_phone ?: '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Adresse -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-geo-alt me-1"></i> Adresse
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="fw-bold">Adresse complète</label>
                            <p>
                                @if($user->adresse || $user->ville || $user->code_postal || $user->pays)
                                    {{ $user->adresse }}<br>
                                    {{ $user->code_postal }} {{ $user->ville }}<br>
                                    {{ $user->pays }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activité -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-activity me-1"></i> Activité
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="border rounded p-3">
                                <h6 class="text-muted mb-2">Dernière connexion</h6>
                                <p class="mb-0">
                                    @if($user->derniere_connexion)
                                        {{ $user->derniere_connexion->format('d/m/Y H:i') }}
                                    @else
                                        Jamais connecté
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3">
                                <h6 class="text-muted mb-2">Date de création</h6>
                                <p class="mb-0">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3">
                                <h6 class="text-muted mb-2">Dernière modification</h6>
                                <p class="mb-0">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
