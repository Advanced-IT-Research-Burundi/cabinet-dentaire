@extends('layouts.app')

@section('title', "Rendez-vous d'aujourd'hui")

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Rendez-vous d'aujourd'hui</h1>
                <div>
                    <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-calendar-week me-1"></i> Tous les rendez-vous
                    </a>
                    <a href="{{ route('appointments.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Nouveau Rendez-vous
                    </a>
                </div>
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
                            <p class="text-muted mb-0">Aucun rendez-vous prévu pour aujourd'hui</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
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
                                                    <button type="button" class="btn btn-sm btn-outline-success" 
                                                        onclick="markAsComplete({{ $appointment->id }})">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        onclick="markAsCancelled({{ $appointment->id }})">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                </div>
                                                <form id="complete-form-{{ $appointment->id }}" 
                                                    action="{{ route('appointments.update', $appointment) }}" 
                                                    method="POST" class="d-none">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="Termine">
                                                </form>
                                                <form id="cancel-form-{{ $appointment->id }}" 
                                                    action="{{ route('appointments.update', $appointment) }}" 
                                                    method="POST" class="d-none">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="Annule">
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function markAsComplete(id) {
    if (confirm('Marquer ce rendez-vous comme terminé ?')) {
        document.getElementById('complete-form-' + id).submit();
    }
}

function markAsCancelled(id) {
    if (confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous ?')) {
        document.getElementById('cancel-form-' + id).submit();
    }
}
</script>
@endpush
@endsection
