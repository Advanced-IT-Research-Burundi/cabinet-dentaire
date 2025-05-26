@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- En-tête avec titre et actions -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">
                <i class="bi bi-clipboard2-pulse-fill me-2 text-primary"></i>
                Détails du traitement
            </h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('treatments.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Retour
            </a>
            <a href="{{ route('treatments.edit', $treatment) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-1"></i> Modifier
            </a>
        </div>
    </div>

    <!-- Carte principale avec informations du traitement -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-gradient-primary text-white py-3">
            <h5 class="card-title mb-0">
                <i class="bi bi-info-circle-fill me-2"></i>
                Informations du traitement
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="row g-4">
                <!-- Colonne gauche - Informations générales -->
                <div class="col-lg-6">
                    <div class="bg-light rounded-3 p-3 h-100">
                        <h6 class="text-uppercase fw-bold text-muted mb-3">
                            <i class="bi bi-person-fill me-2"></i>Informations générales
                        </h6>
                        <dl class="row mb-0">
                            <dt class="col-sm-5 text-muted">
                                <i class="bi bi-person me-1"></i>Patient
                            </dt>
                            <dd class="col-sm-7 fw-medium">{{ $treatment->patient->full_name }}</dd>

                            <dt class="col-sm-5 text-muted">
                                <i class="bi bi-person-badge me-1"></i>Dentiste
                            </dt>
                            <dd class="col-sm-7 fw-medium">{{ $treatment->dentist->full_name }}</dd>

                            <dt class="col-sm-5 text-muted">
                                <i class="bi bi-clipboard2-pulse me-1"></i>Type
                            </dt>
                            <dd class="col-sm-7 fw-medium">{{ $treatment->treatmentType->name }}</dd>

                            <dt class="col-sm-5 text-muted">
                                <i class="bi bi-calendar-check me-1"></i>Rendez-vous
                            </dt>
                            <dd class="col-sm-7 fw-medium">
                                @if($treatment->appointment)
                                    {{ $treatment->appointment->date->format('d/m/Y H:i') }}
                                @else
                                    <span class="text-muted">Non défini</span>
                                @endif
                            </dd>
                        </dl>
                    </div>
                </div>

                <!-- Colonne droite - Détails du traitement -->
                <div class="col-lg-6">
                    <div class="bg-light rounded-3 p-3 h-100">
                        <h6 class="text-uppercase fw-bold text-muted mb-3">
                            <i class="bi bi-clipboard-data me-2"></i>Détails du traitement
                        </h6>
                        <dl class="row mb-0">
                            <dt class="col-sm-5 text-muted">
                                <i class="bi bi-calendar-date me-1"></i>Date
                            </dt>
                            <dd class="col-sm-7 fw-medium">{{ $treatment->date->format('d/m/Y') }}</dd>

                            <dt class="col-sm-5 text-muted">
                                <i class="bi bi-currency-exchange me-1"></i>Prix appliqué
                            </dt>
                            <dd class="col-sm-7 fw-medium text-success">
                                <strong>{{ number_format($treatment->applied_price, 2) }} FBU</strong>
                            </dd>

                            <dt class="col-sm-5 text-muted">
                                <i class="bi bi-flag me-1"></i>Statut
                            </dt>
                            <dd class="col-sm-7">
                                @switch($treatment->status)
                                    @case('Planifie')
                                        <span class="badge bg-info fs-6 px-3 py-2">
                                            <i class="bi bi-clock me-1"></i>Planifié
                                        </span>
                                        @break
                                    @case('En_cours')
                                        <span class="badge bg-warning fs-6 px-3 py-2">
                                            <i class="bi bi-arrow-clockwise me-1"></i>En cours
                                        </span>
                                        @break
                                    @case('Termine')
                                        <span class="badge bg-success fs-6 px-3 py-2">
                                            <i class="bi bi-check-circle me-1"></i>Terminé
                                        </span>
                                        @break
                                    @case('Annule')
                                        <span class="badge bg-danger fs-6 px-3 py-2">
                                            <i class="bi bi-x-circle me-1"></i>Annulé
                                        </span>
                                        @break
                                @endswitch
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cartes pour Description et Notes médicales -->
    <div class="row g-4">
        <!-- Description -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-light py-3">
                    <h6 class="card-title mb-0 text-uppercase fw-bold text-muted">
                        <i class="bi bi-card-text me-2"></i>Description
                    </h6>
                </div>
                <div class="card-body">
                    @if($treatment->description)
                        <p class="mb-0 text-dark">{{ $treatment->description }}</p>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-file-text display-4 mb-3 opacity-50"></i>
                            <p class="mb-0">Aucune description disponible</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Notes médicales -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-light py-3">
                    <h6 class="card-title mb-0 text-uppercase fw-bold text-muted">
                        <i class="bi bi-journal-medical me-2"></i>Notes médicales
                    </h6>
                </div>
                <div class="card-body">
                    @if($treatment->medical_notes)
                        <p class="mb-0 text-dark">{{ $treatment->medical_notes }}</p>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-journal-medical display-4 mb-3 opacity-50"></i>
                            <p class="mb-0">Aucune note médicale disponible</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(45deg, #007bff, #0056b3);
    }

    .card {
        transition: transform 0.2s ease-in-out;
    }

    .card:hover {
        transform: translateY(-2px);
    }

    .badge {
        font-weight: 500;
        letter-spacing: 0.5px;
    }

    .text-gray-800 {
        color: #5a5c69 !important;
    }

    @media print {
        .btn, .breadcrumb {
            display: none !important;
        }

        .card {
            box-shadow: none !important;
            border: 1px solid #dee2e6 !important;
        }

        .bg-gradient-primary {
            background: #007bff !important;
            color: white !important;
        }
    }
</style>
@endsection
