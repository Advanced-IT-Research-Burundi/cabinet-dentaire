@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('breadcrumbs')
<nav class="container" aria-label="breadcrumb">
    <h1>Tableau de bord</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door"></i> Accueil</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tableau de bord</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container">

    {{-- Dashboard Admin --}}
    @can('is-admin')
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card h-100 bg-primary bg-opacity-10 border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="text-primary fw-bold">RDV Aujourd'hui</div>
                        <div class="bg-primary text-white p-2 rounded-circle">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                    </div>
                    <div class="stat-value text-primary">{{ $rdvToday ?? 0 }}</div>
                    <div class="stat-title">Rendez-vous</div>
                </div>
                <div class="card-footer bg-transparent border-0 py-2">
                    <a href="{{ route('appointments.today') }}" class="text-decoration-none text-primary small">
                        <i class="bi bi-arrow-right"></i> Voir détails
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card h-100 bg-success bg-opacity-10 border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="text-success fw-bold">Nouveaux Patients</div>
                        <div class="bg-success text-white p-2 rounded-circle">
                            <i class="bi bi-person-plus"></i>
                        </div>
                    </div>
                    <div class="stat-value text-success">{{ $newPatients ?? 0 }}</div>
                    <div class="stat-title">Ce mois-ci</div>
                </div>
                <div class="card-footer bg-transparent border-0 py-2">
                    <a href="{{ route('patients.index') }}" class="text-decoration-none text-success small">
                        <i class="bi bi-arrow-right"></i> Gérer patients
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card h-100 bg-info bg-opacity-10 border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="text-info fw-bold">Revenus</div>
                        <div class="bg-info text-white p-2 rounded-circle">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                    </div>
                    <div class="stat-value text-info">{{ number_format($revenue ?? 0, 0) }} FBU</div>
                    <div class="stat-title">Ce mois-ci</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card h-100 bg-warning bg-opacity-10 border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="text-warning fw-bold">Utilisateurs</div>
                        <div class="bg-warning text-white p-2 rounded-circle">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                    <div class="stat-value text-warning">{{ $totalUsers ?? 0 }}</div>
                    <div class="stat-title">Total utilisateurs</div>
                </div>
                <div class="card-footer bg-transparent border-0 py-2">
                    <a href="{{ route('users.index') }}" class="text-decoration-none text-warning small">
                        <i class="bi bi-arrow-right"></i> Gérer utilisateurs
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Liens rapides Admin --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Accès rapide</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 mb-2">
                            <a href="{{ route('appointments.create') }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-plus-circle me-1"></i> Nouveau RDV
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('patients.create') }}" class="btn btn-outline-success w-100">
                                <i class="bi bi-person-plus me-1"></i> Nouveau Patient
                            </a>
                        </div>
                        <div class="col-md-2 mb-2">
                            <a href="{{ route('stocks.index') }}" class="btn btn-outline-info w-100">
                                <i class="bi bi-box me-1"></i> Gestion Stock
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('stock.utilisateur') }}" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-graph-up me-1"></i> Rapports
                            </a>
                        </div>
                        <div class="col-md-2 mb-2">
                            <a href="{{ route('parametres') }}" class="btn btn-outline-warning w-100">
                                <i class="bi bi-gear me-1"></i>Paramètres
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan

    {{-- Dashboard Dentiste --}}
    @can('is-dentiste')
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card h-100 bg-primary bg-opacity-10 border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="text-primary fw-bold">Mes RDV Aujourd'hui</div>
                        <div class="bg-primary text-white p-2 rounded-circle">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                    </div>
                    <div class="stat-value text-primary">{{ $myAppointmentsToday ?? 0 }}</div>
                    <div class="stat-title">Consultations</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card h-100 bg-success bg-opacity-10 border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="text-success fw-bold">Mes Patients</div>
                        <div class="bg-success text-white p-2 rounded-circle">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                    <div class="stat-value text-success">{{ $myPatientsTotal ?? 0 }}</div>
                    <div class="stat-title">Total patients</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card h-100 bg-info bg-opacity-10 border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="text-info fw-bold">Traitements</div>
                        <div class="bg-info text-white p-2 rounded-circle">
                            <i class="bi bi-tools"></i>
                        </div>
                    </div>
                    <div class="stat-value text-info">{{ $myTreatmentsMonth ?? 0 }}</div>
                    <div class="stat-title">Ce mois-ci</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card h-100 bg-warning bg-opacity-10 border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="text-warning fw-bold">Revenus</div>
                        <div class="bg-warning text-white p-2 rounded-circle">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                    </div>
                    <div class="stat-value text-warning">{{ number_format($myRevenueMonth ?? 0, 0) }} FBU</div>
                    <div class="stat-title">Ce mois-ci</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Liens rapides Dentiste --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Accès rapide</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <a href="{{ route('appointments.index') }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-calendar me-1"></i> Mon Planning
                            </a>
                        </div>
                        <div class="col-md-4 mb-2">
                            <a href="{{ route('patients.index') }}" class="btn btn-outline-success w-100">
                                <i class="bi bi-people me-1"></i> Mes Patients
                            </a>
                        </div>
                        <div class="col-md-4 mb-2">
                            <a href="{{ route('treatments.index') }}" class="btn btn-outline-info w-100">
                                <i class="bi bi-tools me-1"></i> Traitements
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan

    {{-- Dashboard Pharmacien --}}
    @can('is-pharmacist')
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card h-100 bg-success bg-opacity-10 border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="text-success fw-bold">Produits Stock</div>
                        <div class="bg-success text-white p-2 rounded-circle">
                            <i class="bi bi-box"></i>
                        </div>
                    </div>
                    <div class="stat-value text-success">{{ $totalStocks ?? 0 }}</div>
                    <div class="stat-title">Total produits</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card h-100 bg-warning bg-opacity-10 border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="text-warning fw-bold">Stock Faible</div>
                        <div class="bg-warning text-white p-2 rounded-circle">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                    </div>
                    <div class="stat-value text-warning">{{ $lowStocks ?? 0 }}</div>
                    <div class="stat-title">Alerte stock</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card h-100 bg-danger bg-opacity-10 border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="text-danger fw-bold">Produits Expirés</div>
                        <div class="bg-danger text-white p-2 rounded-circle">
                            <i class="bi bi-x-circle"></i>
                        </div>
                    </div>
                    <div class="stat-value text-danger">{{ $expiredStocks ?? 0 }}</div>
                    <div class="stat-title">À traiter</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card h-100 bg-info bg-opacity-10 border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="text-info fw-bold">Ventes</div>
                        <div class="bg-info text-white p-2 rounded-circle">
                            <i class="bi bi-cart-check"></i>
                        </div>
                    </div>
                    <div class="stat-value text-info">{{ number_format($salesMonth ?? 0, 0) }} FBU</div>
                    <div class="stat-title">Ce mois-ci</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Liens rapides Pharmacien --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Accès rapide</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('stocks.index') }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-box me-1"></i> Gestion Stock
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('stocks.create') }}" class="btn btn-outline-success w-100">
                                <i class="bi bi-plus-circle me-1"></i> Nouveau Produit
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('invoices.create') }}" class="btn btn-outline-info w-100">
                                <i class="bi bi-cart-plus me-1"></i> Nouvelle Facture
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('stocks.index') }}" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-arrow-left-right me-1"></i> Mouvements
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan

    {{-- Dashboard Secrétaire --}}
    @can('is-secretaire')
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card h-100 bg-primary bg-opacity-10 border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="text-primary fw-bold">RDV Aujourd'hui</div>
                        <div class="bg-primary text-white p-2 rounded-circle">
                            <i class="bi bi-calendar-day"></i>
                        </div>
                    </div>
                    <div class="stat-value text-primary">{{ $appointmentsToday ?? 0 }}</div>
                    <div class="stat-title">Rendez-vous</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card h-100 bg-warning bg-opacity-10 border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="text-warning fw-bold">En Attente</div>
                        <div class="bg-warning text-white p-2 rounded-circle">
                            <i class="bi bi-clock"></i>
                        </div>
                    </div>
                    <div class="stat-value text-warning">{{ $pendingAppointments ?? 0 }}</div>
                    <div class="stat-title">À confirmer</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card h-100 bg-success bg-opacity-10 border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="text-success fw-bold">Patients</div>
                        <div class="bg-success text-white p-2 rounded-circle">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                    <div class="stat-value text-success">{{ $patientsTotal ?? 0 }}</div>
                    <div class="stat-title">Total patients</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card h-100 bg-danger bg-opacity-10 border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="text-danger fw-bold">Impayées</div>
                        <div class="bg-danger text-white p-2 rounded-circle">
                            <i class="bi bi-exclamation-circle"></i>
                        </div>
                    </div>
                    <div class="stat-value text-danger">{{ $invoicesUnpaid ?? 0 }}</div>
                    <div class="stat-title">Factures</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Liens rapides Secrétaire --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Accès rapide</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('appointments.create') }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-calendar-plus me-1"></i> Nouveau RDV
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('patients.create') }}" class="btn btn-outline-success w-100">
                                <i class="bi bi-person-plus me-1"></i> Nouveau Patient
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('appointments.today') }}" class="btn btn-outline-info w-100">
                                <i class="bi bi-calendar-day me-1"></i> Planning Jour
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="{{ route('invoices.index') }}" class="btn btn-outline-warning w-100">
                                <i class="bi bi-receipt me-1"></i> Facturation
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan

    {{-- Rendez-vous d'aujourd'hui (pour tous les rôles) --}}
    @if($todayAppointments && $todayAppointments->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 card-title">
                            <i class="bi bi-calendar-day me-2 text-primary"></i>
                            @can('is-dentiste')
                                Mes rendez-vous d'aujourd'hui
                            @else
                                Rendez-vous d'aujourd'hui
                            @endcan
                        </h5>
                        <a href="{{ route('appointments.today') }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-eye me-1"></i>Tout voir
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>Heure</th>
                                    <th>Patient</th>
                                    @cannot('is-dentiste')
                                    <th>Dentiste</th>
                                    @endcannot
                                    <th>Traitement</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($todayAppointments as $appointment)
                                <tr>
                                    <td>
                                        {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} -
                                        {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}
                                    </td>
                                    <td>
                                        <span class="badge bg-primary me-1">{{ $appointment->patient->id }}</span>
                                        {{ $appointment->patient->full_name }}
                                    </td>
                                    @cannot('is-dentiste')
                                    <td>
                                        Dr. {{ $appointment->dentist->user->first_name }}
                                        {{ $appointment->dentist->user->last_name }}
                                    </td>
                                    @endcannot
                                    <td>
                                        @forelse ( $appointment?->plannedTreatments as $traitement)
                                            <li>{{ $traitement?->name ?? 'N/A' }}</li>
                                        @empty
                                            {{ $appointment?->plannedTreatment?->name ?? 'N/A' }}</td>
                                        @endforelse

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
                                        <span class="badge {{ $statusClasses[$appointment->status] ?? 'bg-secondary' }}">
                                            {{ $statusLabels[$appointment->status] ?? $appointment->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            @if(in_array($appointment->status, ['Confirme', 'En_attente', 'Reporte']))
                                                <a href="{{ route('appointments.show', $appointment) }}"
                                                   class="btn btn-outline-primary" title="Voir détails">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                @can('is-admin')
                                                <a href="{{ route('appointments.edit', $appointment) }}"
                                                   class="btn btn-outline-warning" title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                @endcan
                                                @can('is-dentiste')
                                                <form action="{{ route('appointments.finish', $appointment) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-outline-success"
                                                            title="Marquer comme terminé"
                                                            onclick="return confirm('Marquer ce rendez-vous comme terminé ?')">
                                                        <i class="bi bi-check-circle"></i>
                                                    </button>
                                                </form>
                                                @endcan
                                            @else
                                                <a href="{{ route('appointments.show', $appointment) }}"
                                                   class="btn btn-outline-primary" title="Voir détails">
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
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Section des alertes et notifications --}}
    <div class="row mt-4">
        {{-- Alertes pour les pharmaciens --}}
        @can('is-pharmacist')
        @if($lowStocks > 0 || $expiredStocks > 0)
        <div class="col-md-6 mb-4">
            <div class="card border-warning">
                <div class="card-header bg-warning bg-opacity-10">
                    <h6 class="mb-0 text-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>Alertes Stock
                    </h6>
                </div>
                <div class="card-body">
                    @if($lowStocks > 0)
                    <div class="alert alert-warning alert-sm mb-2">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        {{ $lowStocks }} produit(s) en stock faible
                        <a href="{{ route('stocks.index', ['search' => '', 'status' => 'Faible_stock']) }}" class="alert-link">Voir</a>
                    </div>
                    @endif
                    @if($expiredStocks > 0)
                    <div class="alert alert-danger alert-sm mb-0">
                        <i class="bi bi-x-circle me-2"></i>
                        {{ $expiredStocks }} produit(s) expiré(s)
                        <a href="{{ route('stocks.index', ['search' => '', 'status' => 'Expire']) }}" class="alert-link">Voir</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
        @endcan

        {{-- Alertes pour les secrétaires --}}
        @can('is-secretaire')
        @if($pendingAppointments > 0 || $invoicesUnpaid > 0)
        <div class="col-md-6 mb-4">
            <div class="card border-info">
                <div class="card-header bg-info bg-opacity-10">
                    <h6 class="mb-0 text-info">
                        <i class="bi bi-bell me-2"></i>Notifications
                    </h6>
                </div>
                <div class="card-body">
                    @if($pendingAppointments > 0)
                    <div class="alert alert-warning alert-sm mb-2">
                        <i class="bi bi-clock me-2"></i>
                        {{ $pendingAppointments }} rendez-vous en attente de confirmation
                        <a href="{{ route('appointments.index', ['status' => 'En_attente']) }}" class="alert-link">Voir</a>
                    </div>
                    @endif
                    @if($invoicesUnpaid > 0)
                    <div class="alert alert-danger alert-sm mb-0">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        {{ $invoicesUnpaid }} facture(s) impayée(s)
                        <a href="{{ route('invoices.index', ['status' => 'unpaid']) }}" class="alert-link">Voir</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
        @endcan



        {{-- Planning rapide pour les dentistes --}}
        @can('is-dentiste')
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-calendar-week me-2"></i>Mon Planning Cette Semaine
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $weekAppointments = \App\Models\Appointment::with(['patient'])
                            ->whereHas('dentist', function ($q) {
                                $q->where('dentist_id', auth()->user()->id);
                            })
                            ->whereBetween('date', [
                                 \Carbon\Carbon::now()->startOfWeek(),
                                 \Carbon\Carbon::now()->endOfWeek()
                            ])
                            ->orderBy('date')
                            ->orderBy('start_time')
                            ->limit(5)
                            ->get();
                    @endphp

                    @if($weekAppointments->count() > 0)
                        @foreach($weekAppointments as $appointment)
                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                            <div>
                                <strong>{{ \Carbon\Carbon::parse($appointment->date)->format('d/m') }}</strong>
                                <span class="text-muted">{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}</span>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold">{{ $appointment->patient->full_name }}</div>
                                <small class="text-muted">
                                    <td>
                                        @forelse ( $appointment?->plannedTreatments as $traitement)
                                            <li>{{ $traitement?->name ?? 'N/A' }}</li>
                                        @empty
                                            {{ $appointment?->plannedTreatment?->name ?? 'N/A' }}</td>
                                        @endforelse

                                    <td>
                                                                            </small>
                            </div>
                        </div>
                        @endforeach
                        <div class="text-center mt-3">
                            <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-outline-primary">
                                Voir tout mon planning
                            </a>
                        </div>
                    @else
                        <div class="text-center text-muted py-3">
                            <i class="bi bi-calendar-x fs-1"></i>
                            <p class="mb-0">Aucun rendez-vous cette semaine</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endcan
    </div>

</div>
@endsection

@push('styles')
<style>
.stat-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    line-height: 1;
}

.stat-title {
    font-size: 0.875rem;
    color: #6c757d;
    font-weight: 500;
}

.hover-shadow:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transform: translateY(-1px);
    transition: all 0.2s ease;
}

.alert-sm {
    padding: 0.5rem 0.75rem;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.card-title {
    color: #495057;
    font-weight: 600;
}

.progress {
    background-color: #e9ecef;
    border-radius: 4px;
}

.btn-group-sm > .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.775rem;
}

@media (max-width: 768px) {
    .stat-value {
        font-size: 1.5rem;
    }

    .col-xl-3 {
        margin-bottom: 1rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation des cartes statistiques
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';

        setTimeout(() => {
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Actualisation automatique des données toutes les 5 minutes
    setInterval(function() {
        if (document.visibilityState === 'visible') {
            location.reload();
        }
    }, 300000); // 5 minutes

    // Tooltip pour les boutons d'action
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush
