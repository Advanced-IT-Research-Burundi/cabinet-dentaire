@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- En-tête avec boutons d'action -->
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h1 class="display-6 fw-bold text-primary">
                <i class="bi bi-person-badge"></i> Profil du Dentiste
            </h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('dentists.edit', $dentist) }}" class="btn btn-primary me-2">
                <i class="bi bi-pencil-square"></i> Modifier
            </a>
            <a href="{{ route('dentists.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>

    <!-- Carte de contenu principal -->
    <div class="card shadow-sm">
        <!-- En-tête avec informations de base -->
        <div class="card-header bg-light p-3">
            <div class="row align-items-center">
                <div class="col-auto">
                    @if($dentist->user->photo_url)
                        <img src="{{ Storage::url($dentist->user->photo_url) }}" alt="Photo de Profil" class="rounded-circle" width="80" height="80">
                    @else
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2rem;">
                            <i class="bi bi-person"></i>
                        </div>
                    @endif
                </div>
                <div class="col">
                    <h2 class="h3 mb-1">{{ $dentist->user->first_name }} {{ $dentist->user->last_name }}</h2>
                    <p class="text-muted mb-0">
                        <i class="bi bi-award"></i> {{ $dentist->specialty ?? 'Dentisterie Générale' }}
                        <span class="ms-3 badge {{ $dentist->available ? 'bg-success' : 'bg-danger' }}">
                            <i class="bi {{ $dentist->available ? 'bi-check-circle' : 'bi-x-circle' }}"></i>
                            {{ $dentist->available ? 'Disponible' : 'Indisponible' }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Contenu des détails -->
        <div class="card-body">
            <div class="row">
                <!-- Colonne gauche - Informations personnelles -->
                <div class="col-md-6 mb-4 mb-md-0">
                    <div class="card h-100">
                        <div class="card-header bg-primary bg-opacity-10">
                            <h3 class="h5 mb-0">
                                <i class="bi bi-person-vcard"></i> Informations Personnelles
                            </h3>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold"><i class="bi bi-envelope"></i> Email</div>
                                        {{ $dentist->user->email }}
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold"><i class="bi bi-telephone"></i> Téléphone</div>
                                        {{ $dentist->user->phone ?? 'Non spécifié' }}
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold"><i class="bi bi-telephone-plus"></i> Téléphone secondaire</div>
                                        {{ $dentist->user->secondary_phone ?? 'Non spécifié' }}
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold"><i class="bi bi-at"></i> Email secondaire</div>
                                        {{ $dentist->user->secondary_email ?? 'Non spécifié' }}
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold"><i class="bi bi-credit-card"></i> Numéro de licence</div>
                                        {{ $dentist->license_number ?? 'Non spécifié' }}
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Colonne droite - Informations supplémentaires -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-primary bg-opacity-10">
                            <h3 class="h5 mb-0">
                                <i class="bi bi-info-circle"></i> Informations Supplémentaires
                            </h3>
                        </div>
                        <div class="card-body">
                            <!-- Adresse -->
                            <div class="mb-4">
                                <h4 class="h6 text-muted"><i class="bi bi-geo-alt"></i> Adresse</h4>
                                <p>
                                    {{ $dentist->user->adresse ?? 'Non spécifié' }}<br>
                                    {{ $dentist->user->ville ?? '' }} {{ $dentist->user->code_postal ?? '' }}<br>
                                    {{ $dentist->user->pays ?? '' }}
                                </p>
                            </div>

                            <!-- Couleur du calendrier -->
                            <div class="mb-4">
                                <h4 class="h6 text-muted"><i class="bi bi-calendar-event"></i> Couleur du calendrier</h4>
                                <div class="d-flex align-items-center">
                                    <div class="rounded border" style="width: 25px; height: 25px; background-color: {{ $dentist->calendar_color ?? '#3788d8' }}"></div>
                                    <span class="ms-2">{{ $dentist->calendar_color ?? 'Défaut' }}</span>
                                </div>
                            </div>

                            <!-- Biographie -->
                            <div>
                                <h4 class="h6 text-muted"><i class="bi bi-file-text"></i> Biographie</h4>
                                <p class="mb-0">{{ $dentist->biography ?? 'Aucune biographie disponible.' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques des rendez-vous -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary bg-opacity-10">
                            <h3 class="h5 mb-0">
                                <i class="bi bi-calendar-check"></i> Statistiques des Rendez-vous
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <div class="p-3 border rounded bg-light">
                                        <h4 class="h2 mb-1 text-primary">{{ $dentist->appointments->count() ?? 0 }}</h4>
                                        <p class="mb-0 text-muted">Rendez-vous totaux</p>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <div class="p-3 border rounded bg-light">
                                        <h4 class="h2 mb-1 text-success">{{ $dentist->appointments->where('status', 'Completed')->count() ?? 0 }}</h4>
                                        <p class="mb-0 text-muted">Terminés</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 border rounded bg-light">
                                        <h4 class="h2 mb-1 text-warning">{{ $dentist->appointments->where('status', 'Scheduled')->count() ?? 0 }}</h4>
                                        <p class="mb-0 text-muted">À venir</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between">
                        {{-- <button type="button" class="btn btn-outline-primary" onclick="window.print()">
                            <i class="bi bi-printer"></i> Imprimer le Profil
                        </button> --}}
                        <form action="{{ route('dentists.destroy', $dentist) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce dentiste ? Cette action est irréversible.')">
                                <i class="bi bi-trash"></i> Supprimer le Dentiste
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
