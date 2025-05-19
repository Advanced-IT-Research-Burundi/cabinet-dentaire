@extends('layouts.app')

@section('title', 'Nouveau Rendez-vous')

@section('content')
<div class="container-fluid">
    <div class="mb-4 row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="mb-0 h3">Nouveau Rendez-vous</h1>
                <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Retour
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            @include('appointments.form-appointment', ['appointment' => $appointment])
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Instructions</h5>
                    <p class="card-text">Veuillez remplir tous les champs obligatoires (*) pour créer un nouveau rendez-vous.</p>
                    <ul class="mb-0 list-unstyled">
                        <li><i class="bi bi-dot"></i> Sélectionnez un patient et un dentiste</li>
                        <li><i class="bi bi-dot"></i> Choisissez une date et une plage horaire</li>
                        <li><i class="bi bi-dot"></i> Indiquez le type de traitement prévu</li>
                        <li><i class="bi bi-dot"></i> Ajoutez un motif de consultation</li>
                        <li><i class="bi bi-dot"></i> Définissez le statut initial du rendez-vous</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
