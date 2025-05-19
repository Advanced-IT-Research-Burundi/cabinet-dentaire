@extends('layouts.app')

@section('title', 'Détails du Rendez-vous')

@section('content')
<div class="container-fluid">
    <div class="mb-4 row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="mb-0 h3">Détails du Rendez-vous</h1>
                <div>
                    <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-primary me-2">
                        <i class="bi bi-pencil me-1"></i> Modifier
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
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Informations du rendez-vous</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Statut</h6>
                            @php
                                $statusClass = [
                                    'En_attente' => 'bg-warning',
                                    'Confirme' => 'bg-success',
                                    'Annule' => 'bg-danger',
                                    'Reporte' => 'bg-info'
                                ][$appointment->status] ?? 'bg-secondary';

                                $statusLabel = [
                                    'En_attente' => 'En attente',
                                    'Confirme' => 'Confirmé',
                                    'Annule' => 'Annulé',
                                    'Reporte' => 'Reporté'
                                ][$appointment->status] ?? $appointment->status;
                            @endphp
                            <span class="badge {{ $statusClass }} text-white">{{ $statusLabel }}</span>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Référence</h6>
                            <p class="mb-0">#{{ $appointment->id }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Date</h6>
                            <p class="mb-0">{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Heure</h6>
                            <p class="mb-0">{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Patient</h6>
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-circle p-2 me-2">
                                    <i class="bi bi-person"></i>
                                </div>
                                <div>
                                    <p class="mb-0 fw-semibold">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</p>
                                    @if($appointment->patient->phone)
                                        <p class="mb-0 small text-muted">{{ $appointment->patient->phone }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Dentiste</h6>
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-circle p-2 me-2">
                                    <i class="bi bi-clipboard2-pulse"></i>
                                </div>
                                <div>
                                    <p class="mb-0 fw-semibold">Dr. {{ $appointment->dentist->user->first_name }} {{ $appointment->dentist->user->last_name }}</p>
                                    <p class="mb-0 small text-muted">{{ $appointment->dentist->speciality ?? 'Généraliste' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Traitement prévu</h6>
                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded-circle p-2 me-2">
                                <i class="bi bi-hospital"></i>
                            </div>
                            <div>
                                <p class="mb-0 fw-semibold">{{ $appointment->plannedTreatment->name }}</p>
                                <p class="mb-0 text-primary">{{ number_format($appointment->plannedTreatment->cost, 2) }} FBU</p>
                            </div>
                        </div>
                    </div>

                    @if($appointment->reason)
                        <div class="mb-4">
                            <h6 class="text-muted mb-2">Motif du rendez-vous</h6>
                            <p class="mb-0">{{ $appointment->reason }}</p>
                        </div>
                    @endif

                    @if($appointment->notes)
                        <div class="mb-0">
                            <h6 class="text-muted mb-2">Notes additionnelles</h6>
                            <p class="mb-0">{{ $appointment->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Section pour les historiques et commentaires si nécessaire --}}
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Historique</h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0 timeline-with-icons">
                        <li class="timeline-item mb-3">
                            <span class="timeline-icon">
                                <i class="bi bi-plus-circle text-primary"></i>
                            </span>
                            <p class="mt-2 mb-0"><strong>Création</strong> - {{ $appointment->created_at->format('d/m/Y à H:i') }}</p>
                        </li>

                        @if($appointment->created_at->ne($appointment->updated_at))
                            <li class="timeline-item">
                                <span class="timeline-icon">
                                    <i class="bi bi-pencil text-info"></i>
                                </span>
                                <p class="mt-2 mb-0"><strong>Dernière modification</strong> - {{ $appointment->updated_at->format('d/m/Y à H:i') }}</p>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($appointment->status !== 'Confirme')
                            <a href="#" onclick="event.preventDefault(); document.getElementById('confirm-form').submit();" class="btn btn-success mb-2">
                                <i class="bi bi-check-circle me-1"></i> Confirmer ce rendez-vous
                            </a>
                            <form id="confirm-form" action="{{ route('appointments.update', $appointment) }}" method="POST" class="d-none">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="patient_id" value="{{ $appointment->patient_id }}">
                                <input type="hidden" name="dentist_id" value="{{ $appointment->dentist_id }}">
                                <input type="hidden" name="date" value="{{ $appointment->date }}">
                                <input type="hidden" name="start_time" value="{{ $appointment->start_time }}">
                                <input type="hidden" name="end_time" value="{{ $appointment->end_time }}">
                                <input type="hidden" name="planned_treatment_id" value="{{ $appointment->planned_treatment_id }}">
                                <input type="hidden" name="reason" value="{{ $appointment->reason }}">
                                <input type="hidden" name="notes" value="{{ $appointment->notes }}">
                                <input type="hidden" name="status" value="Confirme">
                            </form>
                        @endif

                        @if($appointment->status !== 'Annule')
                            <a href="#" onclick="event.preventDefault(); document.getElementById('cancel-form').submit();" class="btn btn-outline-danger mb-2">
                                <i class="bi bi-x-circle me-1"></i> Annuler ce rendez-vous
                            </a>
                            <form id="cancel-form" action="{{ route('appointments.update', $appointment) }}" method="POST" class="d-none">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="patient_id" value="{{ $appointment->patient_id }}">
                                <input type="hidden" name="dentist_id" value="{{ $appointment->dentist_id }}">
                                <input type="hidden" name="date" value="{{ $appointment->date }}">
                                <input type="hidden" name="start_time" value="{{ $appointment->start_time }}">
                                <input type="hidden" name="end_time" value="{{ $appointment->end_time }}">
                                <input type="hidden" name="planned_treatment_id" value="{{ $appointment->planned_treatment_id }}">
                                <input type="hidden" name="reason" value="{{ $appointment->reason }}">
                                <input type="hidden" name="notes" value="{{ $appointment->notes }}">
                                <input type="hidden" name="status" value="Annule">
                            </form>
                        @endif

                        <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-outline-primary mb-2">
                            <i class="bi bi-pencil me-1"></i> Modifier ce rendez-vous
                        </a>

                        <button type="button" class="btn btn-outline-secondary mb-2" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash me-1"></i> Supprimer ce rendez-vous
                        </button>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Informations complémentaires</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted mb-1">Durée</h6>
                        @php
                            $startTime = \Carbon\Carbon::parse($appointment->start_time);
                            $endTime = \Carbon\Carbon::parse($appointment->end_time);
                            $durationMinutes = $startTime->diffInMinutes($endTime);
                        @endphp
                        <p class="mb-0">{{ $durationMinutes }} minutes</p>
                    </div>
                    <div class="mb-0">
                        <h6 class="text-muted mb-1">Créé par</h6>
                        <p class="mb-0">{{ $appointment->created_by_user->name ?? 'Système' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmation de suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer ce rendez-vous ? Cette action est irréversible.</p>
                <p class="mb-0">
                    <strong>Patient :</strong> {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}<br>
                    <strong>Date :</strong> {{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}<br>
                    <strong>Heure :</strong> {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form action="{{ route('appointments.destroy', $appointment) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .timeline-with-icons {
        position: relative;
        list-style: none;
        padding-left: 2.5rem;
    }

    .timeline-item {
        position: relative;
    }

    .timeline-item:not(:last-child):before {
        content: "";
        position: absolute;
        left: -24px;
        top: 20px;
        height: 100%;
        border-left: 2px dotted #e0e0e0;
    }

    .timeline-icon {
        position: absolute;
        left: -39px;
        background-color: #f8f9fa;
        border-radius: 50%;
        height: 31px;
        width: 31px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e0e0e0;
    }
</style>
@endpush
@endsection
