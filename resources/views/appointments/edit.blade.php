@extends('layouts.app')

@section('title', 'Modifier Rendez-vous')

@section('content')
<div class="container-fluid">
    <div class="mb-4 row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="mb-0 h3">Modifier Rendez-vous</h1>
                <div>
                    <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-outline-primary me-2">
                        <i class="bi bi-eye me-1"></i> Voir le détail
                    </a>
                    <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            @include('appointments.form-appointment', ['appointment' => $appointment])
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Informations</h5>
                    <p class="card-text">Vous êtes en train de modifier un rendez-vous existant.</p>
                    <div class="mb-3">
                        <strong>Patient:</strong> {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                    </div>
                    <div class="mb-3">
                        <strong>Dentiste:</strong> Dr. {{ $appointment->dentist->user->first_name }} {{ $appointment->dentist->user->last_name }}
                    </div>
                    <div class="mb-3">
                        <strong>Date créée:</strong> {{ $appointment->created_at->format('d/m/Y H:i') }}
                    </div>
                    <div>
                        <strong>Dernière modification:</strong> {{ $appointment->updated_at->format('d/m/Y H:i') }}
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Actions additionnelles</h5>
                    <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce rendez-vous?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-trash me-1"></i> Supprimer ce rendez-vous
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
