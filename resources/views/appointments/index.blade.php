@extends('layouts.app')

@section('title', 'Rendez-vous')

@section('content')
<div class="container-fluid">
    <div class="mb-4 row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="mb-0 h3">Gestion des Rendez-vous</h1>
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
                        <div class="py-5 text-center">
                            <i class="mb-3 bi bi-calendar-x fs-1 text-muted"></i>
                            <p class="mb-0 text-muted">Aucun rendez-vous trouvé</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table align-middle table-hover">
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
                                                {{ $appointment->patient->first_name }}
                                                {{ $appointment->patient->last_name }}
                                            </td>
                                            <td>
                                                Dr. {{ $appointment->dentist->user->first_name }}
                                                {{ $appointment->dentist->user->last_name}}
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

                        <div class="mt-4 d-flex justify-content-center">
                            {{ $appointments->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
