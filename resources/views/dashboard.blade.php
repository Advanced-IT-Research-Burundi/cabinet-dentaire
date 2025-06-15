<!-- resources/views/dashboard.blade.php -->
@extends('layouts.app')

@section('title', 'Tableau de bord')


@section('breadcrumbs')
<nav class="container" aria-label="breadcrumb">
    <h1>Tableau de bord</h1>
    <ol class="breadcrumb">
        {{-- <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i></a></li> --}}
        {{-- <li class="breadcrumb-item active" aria-current="page">Tableau de bord</li> --}}
    </ol>
</nav>
@endsection

@section('content')
<!-- Cartes de statistiques -->
@canany(['is-admin', 'is-reception'])
<div class="container">
    <div class="row">
        <div class="mb-4 col-xl-3 col-md-6">
            <div class="bg-opacity-10 border-0 card stat-card h-100 bg-primary">
                <div class="card-body">
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <div class="text-primary fw-bold">Rendez-vous aujourd'hui</div>
                        <div class="p-2 text-white bg-primary rounded-circle">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                    </div>
                    <div class="stat-value text-primary">{{ $rdvToday ?? 0 }}</div>
                    <div class="stat-title">Rendez-vous programmés</div>
                </div>
                <div class="py-2 bg-transparent border-0 card-footer">
                    <a href="{{ route('appointments.today') }}" class="text-decoration-none text-primary small">
                        <i class="bi bi-arrow-right"></i> Voir les détails
                    </a>
                </div>
                <i class="bi bi-calendar-check stat-icon text-primary"></i>
            </div>
        </div>

        <div class="mb-4 col-xl-3 col-md-6">
            <div class="bg-opacity-10 border-0 card stat-card h-100 bg-success">
                <div class="card-body">
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <div class="text-success fw-bold">Nouveaux patients</div>
                        <div class="p-2 text-white bg-success rounded-circle">
                            <i class="bi bi-person-plus"></i>
                        </div>
                    </div>
                    <div class="stat-value text-success">{{ $newPatients ?? 0 }}</div>
                    <div class="stat-title">Ce mois-ci</div>
                </div>
                <div class="py-2 bg-transparent border-0 card-footer">
                    <a href="{{ route('patients.index') }}" class="text-decoration-none text-success small">
                        <i class="bi bi-arrow-right"></i> Voir les détails
                    </a>
                </div>
                <i class="bi bi-person-plus stat-icon text-success"></i>
            </div>
        </div>

        <div class="mb-4 col-xl-3 col-md-6">
            <div class="bg-opacity-10 border-0 card stat-card h-100 bg-info">
                <div class="card-body">
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <div class="text-info fw-bold">Revenus</div>
                        <div class="p-2 text-white bg-info rounded-circle">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                    </div>
                    <div class="stat-value text-info">{{ number_format($revenue ?? 0, 2) }} FBU</div>
                    <div class="stat-title">Ce mois-ci</div>
                </div>
                <div class="py-2 bg-transparent border-0 card-footer">
                    {{-- <a href="{{ route('factures.monthly') }}" class="text-decoration-none text-info small">
                        <i class="bi bi-arrow-right"></i> Voir les détails
                    </a> --}}
                </div>
                <i class="bi bi-cash-stack stat-icon text-info"></i>
            </div>
        </div>

        <div class="mb-4 col-xl-3 col-md-6">
            <div class="bg-opacity-10 border-0 card stat-card h-100 bg-warning">
                <div class="card-body">
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <div class="text-warning fw-bold">Utilisateurs</div>
                        <div class="p-2 text-white bg-warning rounded-circle">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                    <div class="stat-value text-warning">{{ $totalusers ?? 0 }}</div>
                    <div class="stat-title">Total des utilisateurs</div>
                </div>
                <div class="py-2 bg-transparent border-0 card-footer">
                    {{-- <a href="{{ route('factures.unpaid') }}" class="text-decoration-none text-warning small">
                        <i class="bi bi-arrow-right"></i> Voir les détails
                    </a> --}}
                </div>
                <i class="bi bi-receipt stat-icon text-warning"></i>
            </div>
        </div>
    </div>

    <!-- Rendez-vous d'aujourd'hui et alertes -->
    <div class="row g-4 justify-content-center">
        <!-- Rendez-vous d'aujourd'hui -->
        <div class="mb-4">
            <div class="border-0 card h-100">
                <div class="bg-white card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 card-title">
                            <i class="bi bi-calendar-day me-2 text-primary"></i>Rendez-vous d'aujourd'hui
                        </h5>
                        <a href="{{ route('appointments.today') }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-eye me-1"></i>Tout voir
                        </a>
                    </div>
                </div>
                <div class="p-0 card-body">
                    <div class="table-responsive">
                        <table class="table mb-0 align-middle table-hover">
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
                                @forelse($todayAppointments as $appointment)
                                 <tr>
                                            <td>{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} -
                                                {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">
                                                {{ $appointment->patient->id }}
                                                </span>
                                                {{ $appointment->patient->full_name }}
                                                {{-- {{ $appointment->patient->last_name }} --}}
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
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Aucun rendez-vous</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endcanany
@endsection
