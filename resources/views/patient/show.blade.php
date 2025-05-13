@extends('layouts.app')

@section('title', 'Détails du Patient')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-gray-800 mb-0">
                <i class="bi bi-person-badge me-2"></i>
                @if($patient->patient_type == 'morale')
                    {{ $patient->societe }}
                @else
                    {{ $patient->first_name }} {{ $patient->middle_name }} {{ $patient->last_name }}
                @endif
            </h1>
            <p class="text-muted mb-0">
                @if($patient->patient_type == 'morale')
                    Personne Morale - NIF: {{ $patient->nif ?? 'Non spécifié' }}
                @else
                    Patient particulier depuis le {{ $patient->created_at->format('d/m/Y') }}
                @endif
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('patients.edit', $patient) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i> Modifier
            </a>
            <a href="{{ route('patients.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Retour
            </a>
        </div>
    </div>

    <!-- Barre de statistiques -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Rendez-vous</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $patient->appointments->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-calendar-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Traitements</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $patient->treatments->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-clipboard2-pulse fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Factures</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $patient->invoices->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-receipt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                À payer</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $patient->treatementsNotPaids()->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-cash-stack fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Onglets d'information -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <ul class="nav nav-tabs card-header-tabs" id="patientTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="info-tab" data-bs-toggle="tab" href="#info" role="tab" aria-controls="info" aria-selected="true">
                        <i class="bi bi-info-circle me-1"></i> Informations
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="appointments-tab" data-bs-toggle="tab" href="#appointments" role="tab" aria-controls="appointments" aria-selected="false">
                        <i class="bi bi-calendar-check me-1"></i> Rendez-vous
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="treatments-tab" data-bs-toggle="tab" href="#treatments" role="tab" aria-controls="treatments" aria-selected="false">
                        <i class="bi bi-clipboard2-pulse me-1"></i> Traitements
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="invoices-tab" data-bs-toggle="tab" href="#invoices" role="tab" aria-controls="invoices" aria-selected="false">
                        <i class="bi bi-receipt me-1"></i> Factures
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="patientTabContent">
                <!-- Onglet Informations -->
                <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
                    <div class="row">
                        <!-- Informations personnelles -->
                        <div class="col-md-6 mb-4">
                            <div class="card border-left-primary h-100">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0 card-title">
                                        <i class="bi bi-person-vcard me-1"></i> Informations personnelles
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        @if($patient->patient_type == 'morale')
                                            <div class="col-12">
                                                <label class="fw-bold text-primary">Type de patient</label>
                                                <p><span class="badge bg-info">Personne Morale</span></p>
                                            </div>
                                            <div class="col-12">
                                                <label class="fw-bold text-primary">Société</label>
                                                <p>{{ $patient->societe ?: '-' }}</p>
                                            </div>
                                            <div class="col-12">
                                                <label class="fw-bold text-primary">NIF</label>
                                                <p>{{ $patient->nif ?: '-' }}</p>
                                            </div>
                                        @else
                                            <div class="col-12">
                                                <label class="fw-bold text-primary">Type de patient</label>
                                                <p><span class="badge bg-primary">Particulier</span></p>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="fw-bold text-primary">Prénom</label>
                                                <p>{{ $patient->first_name }}</p>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="fw-bold text-primary">Deuxième prénom</label>
                                                <p>{{ $patient->middle_name ?: '-' }}</p>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="fw-bold text-primary">Nom</label>
                                                <p>{{ $patient->last_name }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="fw-bold text-primary">Date de naissance</label>
                                                <p>{{ $patient->birth_date ? $patient->birth_date->format('d/m/Y') : '-' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="fw-bold text-primary">Genre</label>
                                                <p>
                                                    @if($patient->gender == 'M')
                                                        <span class="badge bg-primary">Homme</span>
                                                    @elseif($patient->gender == 'F')
                                                        <span class="badge bg-info">Femme</span>
                                                    @else
                                                        <span class="badge bg-secondary">Autre</span>
                                                    @endif
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact -->
                        <div class="col-md-6 mb-4">
                            <div class="card border-left-info h-100">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0 card-title">
                                        <i class="bi bi-telephone me-1"></i> Contact
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <label class="fw-bold text-info">Téléphone principal</label>
                                            <p>
                                                @if($patient->phone)
                                                    <a href="tel:{{ $patient->phone }}" class="text-decoration-none">
                                                        <i class="bi bi-telephone-fill me-1"></i> {{ $patient->phone }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="fw-bold text-info">Téléphone secondaire</label>
                                            <p>
                                                @if($patient->secondary_phone)
                                                    <a href="tel:{{ $patient->secondary_phone }}" class="text-decoration-none">
                                                        <i class="bi bi-telephone-fill me-1"></i> {{ $patient->secondary_phone }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-12">
                                            <label class="fw-bold text-info">Email</label>
                                            <p>
                                                @if($patient->email)
                                                    <a href="mailto:{{ $patient->email }}" class="text-decoration-none">
                                                        <i class="bi bi-envelope-fill me-1"></i> {{ $patient->email }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-12">
                                            <label class="fw-bold text-info">Adresse complète</label>
                                            <div class="border-start border-info ps-3">
                                                <p class="mb-1">{{ $patient->address ?: 'Adresse non spécifiée' }}</p>
                                                @if($patient->postal_code || $patient->city)
                                                    <p class="mb-1">{{ $patient->postal_code }} {{ $patient->city }}</p>
                                                @endif
                                                <p class="mb-0">{{ $patient->country ?: '' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Assurance -->
                        <div class="col-md-6 mb-4">
                            <div class="card border-left-success h-100">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0 card-title">
                                        <i class="bi bi-shield-check me-1"></i> Assurance
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="fw-bold text-success">Compagnie d'assurance</label>
                                            <p>
                                                @if($patient->assurance)
                                                    {{ $patient->assurance->name }}
                                                @elseif($patient->insurance_company)
                                                    {{ $patient->insurance_company }}
                                                @else
                                                    <span class="text-muted">Non spécifiée</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-12">
                                            <label class="fw-bold text-success">Numéro d'assurance</label>
                                            <p>{{ $patient->insurance_number ?: '<span class="text-muted">Non spécifié</span>' }}</p>
                                        </div>
                                        @if($patient->assurance)
                                            <div class="col-12">
                                                <label class="fw-bold text-success">Taux de couverture</label>
                                                <div class="progress" style="height: 25px;">
                                                    @php
                                                        $coverageRate = $patient->assurance->coverage_rate ?? 0;
                                                    @endphp
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $coverageRate }}%"
                                                         aria-valuenow="{{ $coverageRate }}" aria-valuemin="0" aria-valuemax="100">
                                                        {{ $coverageRate }}%
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informations médicales -->
                        <div class="col-md-6 mb-4">
                            <div class="card border-left-warning h-100">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0 card-title">
                                        <i class="bi bi-clipboard2-pulse me-1"></i> Informations médicales
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="fw-bold text-warning">
                                                <i class="bi bi-journal-medical me-1"></i> Antécédents médicaux
                                            </label>
                                            <div class="border rounded p-3 bg-light mb-3">
                                                <p class="text-pre-wrap mb-0">{{ $patient->medical_history ?: 'Aucun antécédent médical enregistré' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label class="fw-bold text-warning">
                                                <i class="bi bi-exclamation-triangle me-1"></i> Allergies
                                            </label>
                                            <div class="border rounded p-3 bg-light">
                                                <p class="text-pre-wrap mb-0">{{ $patient->allergies ?: 'Aucune allergie enregistrée' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Onglet Rendez-vous -->
                <div class="tab-pane fade" id="appointments" role="tabpanel" aria-labelledby="appointments-tab">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">Liste des rendez-vous</h4>
                        <a href="{{ route('appointments.create', ['patient_id' => $patient->id]) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-circle me-1"></i> Nouveau rendez-vous
                        </a>
                    </div>

                    @if($patient->appointments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Heure</th>
                                        <th>Dentiste</th>
                                        <th>Motif</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($patient->appointments as $appointment)
                                        <tr>
                                            <td>{{ $appointment->date->format('d/m/Y') }}</td>
                                            <td>{{ $appointment->start_time }} - {{ $appointment->end_time }}</td>
                                            <td>{{ $appointment->dentist->user->first_name }} {{ $appointment->dentist->user->last_name }}</td>
                                            <td>{{ $appointment->reason }}</td>
                                            <td>
                                                @switch($appointment->status)
                                                    @case('Confirme')
                                                        <span class="badge bg-success">Confirmé</span>
                                                        @break
                                                    @case('Annule')
                                                        <span class="badge bg-danger">Annulé</span>
                                                        @break
                                                    @case('Termine')
                                                        <span class="badge bg-info">Terminé</span>
                                                        @break
                                                    @case('En_attente')
                                                        <span class="badge bg-warning">En attente</span>
                                                        @break
                                                    @case('Reporte')
                                                        <span class="badge bg-secondary">Reporté</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-sm btn-warning">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info" role="alert">
                            <i class="bi bi-info-circle me-2"></i> Aucun rendez-vous enregistré pour ce patient.
                        </div>
                    @endif
                </div>

                <!-- Onglet Traitements -->
                <div class="tab-pane fade" id="treatments" role="tabpanel" aria-labelledby="treatments-tab">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">Liste des traitements</h4>
                        <a href="{{ route('treatments.create', ['patient_id' => $patient->id]) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-circle me-1"></i> Nouveau traitement
                        </a>
                    </div>

                    @if($patient->treatments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Dentiste</th>
                                        <th>Description</th>
                                        <th>Statut</th>
                                        <th>Prix</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($patient->treatments as $treatment)
                                        <tr>
                                            <td>{{ $treatment->date->format('d/m/Y') }}</td>
                                            <td>{{ $treatment->treatment_type?->name ?? "Non spécifié" }}</td>
                                            <td>{{ $treatment->dentist?->user?->first_name ?? "" }} {{ $treatment->dentist?->user?->last_name ?? "" }}</td>
                                            <td>{{ Str::limit($treatment->description, 50) }}</td>
                                            <td>
                                                @switch($treatment->status)
                                                    @case('Planifie')
                                                        <span class="badge bg-warning">Planifié</span>
                                                        @break
                                                    @case('En_cours')
                                                        <span class="badge bg-info">En cours</span>
                                                        @break
                                                    @case('Termine')
                                                        <span class="badge bg-success">Terminé</span>
                                                        @break
                                                    @case('Annule')
                                                        <span class="badge bg-danger">Annulé</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>{{ number_format($treatment->applied_price, 2) }} €</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('treatments.show', $treatment) }}" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('treatments.edit', $treatment) }}" class="btn btn-sm btn-warning">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info" role="alert">
                            <i class="bi bi-info-circle me-2"></i> Aucun traitement enregistré pour ce patient.
                        </div>
                    @endif
                </div>

                <!-- Onglet Factures -->
                <div class="tab-pane fade" id="invoices" role="tabpanel" aria-labelledby="invoices-tab">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">Liste des factures</h4>
                        <a href="{{ route('invoices.create', ['patient_id' => $patient->id]) }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-circle me-1"></i> Nouvelle facture
                        </a>
                    </div>

                    @if($patient->invoices->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Numéro</th>
                                        <th>Date</th>
                                        <th>Échéance</th>
                                        <th>Montant total</th>
                                        <th>Montant assuré</th>
                                        <th>Montant patient</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($patient->invoices as $invoice)
                                        <tr class="{{ $invoice->status == 'En_retard' ? 'table-danger' : '' }}">
                                            <td>
                                                <strong>{{ $invoice->invoice_number }}</strong>
                                            </td>
                                            <td>{{ $invoice->issue_date->format('d/m/Y') }}</td>
                                            <td>{{ $invoice->due_date->format('d/m/Y') }}</td>
                                            <td>{{ number_format($invoice->total_amount, 2) }} {{ $invoice->currency }}</td>
                                            <td>{{ number_format($invoice->insurance_amount, 2) }} {{ $invoice->currency }}</td>
                                            <td>{{ number_format($invoice->patient_amount, 2) }} {{ $invoice->currency }}</td>
                                            <td>
                                                @switch($invoice->status)
                                                    @case('Brouillon')
                                                        <span class="badge bg-secondary">Brouillon</span>
                                                        @break
                                                    @case('Emise')
                                                        <span class="badge bg-primary">Émise</span>
                                                        @break
                                                    @case('Partiellement_payee')
                                                        <span class="badge bg-info">Partiellement payée</span>
                                                        @break
                                                    @case('Payee')
                                                        <span class="badge bg-success">Payée</span>
                                                        @break
                                                    @case('Annulee')
                                                        <span class="badge bg-danger">Annulée</span>
                                                        @break
                                                    @case('En_retard')
                                                        <span class="badge bg-warning">En retard</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-sm btn-warning">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <a href="{{ route('invoices.print', $invoice) }}" class="btn btn-sm btn-success" target="_blank">
                                                        <i class="bi bi-printer"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info" role="alert">
                            <i class="bi bi-info-circle me-2"></i> Aucune facture enregistrée pour ce patient.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .text-pre-wrap {
        white-space: pre-wrap;
    }

    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }

    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }

    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }

    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }

    .border-left-danger {
        border-left: 0.25rem solid #e74a3b !important;
    }

    .table th {
        font-weight: 600;
    }

    .card {
        transition: transform 0.2s;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }

    .card:hover {
        transform: translateY(-2px);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Activer les onglets Bootstrap
        var triggerTabList = [].slice.call(document.querySelectorAll('#patientTabs a'));
        triggerTabList.forEach(function (triggerEl) {
            var tabTrigger = new bootstrap.Tab(triggerEl);
            triggerEl.addEventListener('click', function (event) {
                event.preventDefault();
                tabTrigger.show();
            });
        });

        // Conserver l'onglet actif dans l'URL
        var url = new URL(window.location);
        var activeTab = url.searchParams.get('tab');

        if (activeTab) {
            var triggerEl = document.querySelector('#patientTabs a[href="#' + activeTab + '"]');
            if (triggerEl) {
                var tab = new bootstrap.Tab(triggerEl);
                tab.show();
            }
        }

        // Mettre à jour l'URL quand un onglet est sélectionné
        document.querySelectorAll('#patientTabs a').forEach(function(tabEl) {
            tabEl.addEventListener('shown.bs.tab', function (event) {
                var tabId = event.target.getAttribute('href').substring(1);
                var url = new URL(window.location);
                url.searchParams.set('tab', tabId);
                window.history.replaceState({}, '', url);
            });
        });
    });
</script>
@endpush

@endsection
