<!-- resources/views/dashboard.blade.php -->
@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('page-title', 'Tableau de bord')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        {{-- <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i></a></li> --}}
        <li class="breadcrumb-item active" aria-current="page">Tableau de bord</li>
    </ol>
</nav>
@endsection

@section('content')
<!-- Cartes de statistiques -->
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
                <a href="{{ route('patients.new') }}" class="text-decoration-none text-success small">
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
                <a href="{{ route('factures.monthly') }}" class="text-decoration-none text-info small">
                    <i class="bi bi-arrow-right"></i> Voir les détails
                </a>
            </div>
            <i class="bi bi-cash-stack stat-icon text-info"></i>
        </div>
    </div>

    <div class="mb-4 col-xl-3 col-md-6">
        <div class="bg-opacity-10 border-0 card stat-card h-100 bg-warning">
            <div class="card-body">
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <div class="text-warning fw-bold">Factures impayées</div>
                    <div class="p-2 text-white bg-warning rounded-circle">
                        <i class="bi bi-receipt"></i>
                    </div>
                </div>
                <div class="stat-value text-warning">{{ $unpaidInvoices ?? 0 }}</div>
                <div class="stat-title">Total en attente</div>
            </div>
            <div class="py-2 bg-transparent border-0 card-footer">
                <a href="{{ route('factures.unpaid') }}" class="text-decoration-none text-warning small">
                    <i class="bi bi-arrow-right"></i> Voir les détails
                </a>
            </div>
            <i class="bi bi-receipt stat-icon text-warning"></i>
        </div>
    </div>
</div>

<!-- Rendez-vous d'aujourd'hui et alertes -->
<div class="row">
    <!-- Rendez-vous d'aujourd'hui -->
    <div class="mb-4 col-xl-8">
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
                                <th>Heure</th>
                                <th>Patient</th>
                                <th>Dentiste</th>
                                <th>Motif</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($todayAppointments as $appointment)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($appointment->heure_debut)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->heure_fin)->format('H:i') }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-person-circle fs-5 me-2 text-secondary"></i>
                                        <div>
                                            <div class="fw-medium">{{ $appointment->patient->prenom }} {{ $appointment->patient->nom }}</div>
                                            <div class="small text-muted">{{ $appointment->patient->telephone }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $appointment->dentist->user->first_name }} {{ $appointment->dentist->user->last_name }}</td>
                                <td>{{ $appointment->motif }}</td>
                                <td>
                                    @if($appointment->statut == 'Confirmé')
                                        <span class="badge rounded-pill status-confirmed">Confirmé</span>
                                    @elseif($appointment->statut == 'Annulé')
                                        <span class="badge rounded-pill status-cancelled">Annulé</span>
                                    @else
                                        <span class="badge rounded-pill status-pending">En attente</span>
                                    @endif
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
@endsection
