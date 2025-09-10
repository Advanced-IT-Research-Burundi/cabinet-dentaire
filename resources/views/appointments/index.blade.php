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
            <div class="mb-4 card">
                <div class="card-body">
                    <form action="{{ route('appointments.index') }}" method="GET" class="row row-cols-1 row-cols-md-4 g-3">
                        <div class="col">
                            <div class="form-group">
                                <label for="patient" class="form-label">Patient</label>
                                <input type="text" class="form-control" id="patient" name="patient"
                                    value="{{ request('patient') }}" placeholder="Nom du patient">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="dentist" class="form-label">Dentiste</label>
                                <input type="text" class="form-control" id="dentist" name="dentist"
                                    value="{{ request('dentist') }}" placeholder="Nom du dentiste">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="treatment" class="form-label">Type de traitement</label>
                                <input type="text" class="form-control" id="treatment" name="treatment"
                                    value="{{ request('treatment') }}" placeholder="Type de traitement">
                            </div>
                        </div>
                        <div class="col d-flex align-items-end">
                            <div class="gap-2 d-flex w-100">
                                <button type="submit" class="btn btn-primary flex-grow-1">
                                    <i class="bi bi-search me-1"></i> Rechercher
                                </button>
                                @if(request()->hasAny(['patient', 'dentist', 'treatment']))
                                    <a href="{{ route('appointments.index') }}" class="btn btn-light">
                                        <i class="bi bi-x-circle me-1"></i> Réinitialiser
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>

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
                                        <th>Date de Rendez-vous</th>
                                        <th>Heure</th>
                                        <th>Patient</th>
                                        <th>Dentiste</th>
                                        <th>Traitement</th>
                                        <th>Statut</th>
                                        <th>Date d'enregistrement</th>
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
                                                <span class="badge bg-primary">
                                                {{ $appointment?->patient?->id }}
                                                </span>
                                                {{ $appointment?->patient?->first_name }}
                                                {{ $appointment?->patient?->last_name }}
                                            </td>
                                            <td>
                                                Dr. {{ $appointment?->dentist?->user?->first_name }}
                                                {{ $appointment?->dentist?->user?->last_name}}
                                            </td>
                                            <td>{{ $appointment?->plannedTreatment?->name }}</td>
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
                                            <span class="badge {{ $statusClasses[$appointment?->status] }}">
                                                {{ $statusLabels[$appointment?->status] }}
                                            </span>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($appointment->created_at)->format('d/m/Y H:i') }}</td>

                                        <td>
                                            <div class="btn-group">
                                                @if(in_array($appointment->status, ['Confirme', 'En_attente', 'Reporte']))
                                                    <a href="{{ route('appointments.edit', $appointment) }}"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                @endif

                                                @if($appointment->status === 'Confirme')
                                                    <a href="{{ route('appointments.finish', $appointment) }}"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir terminer ce rendez-vous ?')"
                                                        class="btn btn-sm btn-outline-success">
                                                        <i class="bi bi-check2-circle"></i>
                                                    </a>
                                                    <a href="{{ route('appointments.reschedule', $appointment)}}"
                                                        class="btn btn-sm btn-outline-warning">
                                                        <i class="bi bi-arrow-clockwise"></i>
                                                    </a>
                                                    <a href="{{ route('appointments.cancel', $appointment) }}"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous ?')"
                                                        class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-x-lg"></i>
                                                    </a>
                                                @elseif(in_array($appointment->status, ['En_attente', 'Reporte']))
                                                    <a href="{{ route('appointments.confirm', $appointment) }}"
                                                        onclick="return confirm('Confirmer ce rendez-vous ?')"
                                                        class="btn btn-sm btn-outline-success">
                                                        <i class="bi bi-check-lg"></i>
                                                    </a>
                                                    <a href="{{ route('appointments.cancel', $appointment) }}"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous ?')"
                                                        class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-x-lg"></i>
                                                    </a>
                                                @endif
                                                @if(in_array($appointment->status, ['Termine', 'Annule']))
                                                    <a href="{{ route('appointments.show', $appointment) }}"
                                                        class="btn btn-sm btn-outline-secondary">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                @endif
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
