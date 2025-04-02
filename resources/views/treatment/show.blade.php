@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mb-4 row">
        <div class="col">
            <h1>Détails du traitement</h1>
        </div>
        <div class="col text-end">
            <a href="{{ route('treatments.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Retour
            </a>
            <a href="{{ route('treatments.edit', $treatment) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-1"></i> Modifier
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="mb-4 row">
                <div class="col-md-6">
                    <h5>Informations générales</h5>
                    <dl class="row">
                        <dt class="col-sm-4">Patient</dt>
                        <dd class="col-sm-8">{{ $treatment->patient->full_name }}</dd>

                        <dt class="col-sm-4">Dentiste</dt>
                        <dd class="col-sm-8">{{ $treatment->dentist->full_name }}</dd>

                        <dt class="col-sm-4">Type de traitement</dt>
                        <dd class="col-sm-8">{{ $treatment->treatmentType->name }}</dd>

                        <dt class="col-sm-4">Rendez-vous</dt>
                        <dd class="col-sm-8">{{ $treatment->appointment->date->format('d/m/Y H:i') }}</dd>
                    </dl>
                </div>

                <div class="col-md-6">
                    <h5>Détails du traitement</h5>
                    <dl class="row">
                        <dt class="col-sm-4">Date</dt>
                        <dd class="col-sm-8">{{ $treatment->date->format('d/m/Y') }}</dd>

                        <dt class="col-sm-4">Prix appliqué</dt>
                        <dd class="col-sm-8">{{ number_format($treatment->applied_price, 2) }} FBU</dd>

                        <dt class="col-sm-4">Statut</dt>
                        <dd class="col-sm-8">
                            @switch($treatment->status)
                                @case('Planifie')
                                    <span class="badge bg-info">Planifié</span>
                                    @break
                                @case('En_cours')
                                    <span class="badge bg-warning">En cours</span>
                                    @break
                                @case('Termine')
                                    <span class="badge bg-success">Terminé</span>
                                    @break
                                @case('Annule')
                                    <span class="badge bg-danger">Annulé</span>
                                    @break
                            @endswitch
                        </dd>
                    </dl>
                </div>
            </div>

            <div class="mb-4 row">
                <div class="col-md-12">
                    <h5>Description</h5>
                    <p class="mb-0">{{ $treatment->description ?: 'Aucune description disponible' }}</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h5>Notes médicales</h5>
                    <p class="mb-0">{{ $treatment->medical_notes ?: 'Aucune note médicale disponible' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
