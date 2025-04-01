@extends('layouts.app')

@section('title', 'Rendez-vous')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Gestion des Rendez-vous</h1>
                <a href="{{ route('appointments.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Nouveau Rendez-vous
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($appointments->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-calendar-x fs-1 text-muted mb-3"></i>
                            <p class="text-muted mb-0">Aucun rendez-vous trouvé</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Heure</th>
                                        <th>Patient</th>
                                        <th>Dentiste</th>
                                        <th>Traitement</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($appointments as $appointment)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - 
                                                {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}
                                            </td>
                                            <td>
                                                {{ $appointment->patient->user->prenom }} 
                                                {{ $appointment->patient->user->nom }}
                                            </td>
                                            <td>
                                                Dr. {{ $appointment->dentist->user->prenom }} 
                                                {{ $appointment->dentist->user->nom }}
                                            </td>
                                            <td>{{ $appointment->plannedTreatment->name }}</td>
                                            <td>
                                                @php
                                                    $statusClasses = [
                                                        'Confirme' => 'bg-success',
                                                        'Annule' => 'bg-danger',
                                                        'Termine' => 'bg-info',
                                                        'En_attente' => 'bg-warning',
                                                        'Reporte' => 'bg-secondary'
                                                    ];
                                                    $statusLabels = [
                                                        'Confirme' => 'Confirmé',
                                                        'Annule' => 'Annulé',
                                                        'Termine' => 'Terminé',
                                                        'En_attente' => 'En attente',
                                                        'Reporte' => 'Reporté'
                                                    ];
                                                @endphp
                                                <span class="badge {{ $statusClasses[$appointment->status] }}">
                                                    {{ $statusLabels[$appointment->status] }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('appointments.edit', $appointment) }}" 
                                                        class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('appointments.destroy', $appointment) }}" 
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce rendez-vous ?')">
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

                        <div class="d-flex justify-content-center mt-4">
                            {{ $appointments->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
