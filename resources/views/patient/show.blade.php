@extends('layouts.app')

@section('title', 'Détails du Patient')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            {{ $patient->first_name }} {{ $patient->last_name }}
        </h1>
        <div>
            <a href="{{ route('patients.edit', $patient) }}" class="btn btn-primary">
                <i class="bi bi-pencil"></i> Modifier
            </a>
            <a href="{{ route('patients.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Informations personnelles -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-person-vcard me-1"></i> Informations personnelles
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="fw-bold">Prénom</label>
                            <p>{{ $patient->first_name }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label class="fw-bold">Nom</label>
                            <p>{{ $patient->last_name }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label class="fw-bold">Date de naissance</label>
                            <p>{{ $patient->birth_date ? $patient->birth_date->format('d/m/Y') : '-' }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label class="fw-bold">Genre</label>
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
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-telephone me-1"></i> Contact
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label class="fw-bold">Téléphone</label>
                            <p>{{ $patient->phone ?: '-' }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label class="fw-bold">Téléphone secondaire</label>
                            <p>{{ $patient->secondary_phone ?: '-' }}</p>
                        </div>
                        <div class="col-12">
                            <label class="fw-bold">Email</label>
                            <p>{{ $patient->email ?: '-' }}</p>
                        </div>
                        <div class="col-12">
                            <label class="fw-bold">Adresse</label>
                            <p>
                                {{ $patient->address }}<br>
                                {{ $patient->postal_code }} {{ $patient->city }}<br>
                                {{ $patient->country }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assurance -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-shield-check me-1"></i> Assurance
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="fw-bold">Compagnie d'assurance</label>
                            <p>{{ $patient->insurance_company ?: '-' }}</p>
                        </div>
                        <div class="col-12">
                            <label class="fw-bold">Numéro d'assurance</label>
                            <p>{{ $patient->insurance_number ?: '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations médicales -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clipboard2-pulse me-1"></i> Informations médicales
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="fw-bold">Antécédents médicaux</label>
                            <p class="text-pre-wrap">{{ $patient->medical_history ?: 'Aucun antécédent médical enregistré' }}</p>
                        </div>
                        <div class="col-12">
                            <label class="fw-bold">Allergies</label>
                            <p class="text-pre-wrap">{{ $patient->allergies ?: 'Aucune allergie enregistrée' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rendez-vous -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-calendar-check me-1"></i> Rendez-vous
                    </h5>
                </div>
                <div class="card-body">
                    @if($patient->appointments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
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
                                            <td>{{ $appointment->start_time->format('H:i') }} - {{ $appointment->end_time->format('H:i') }}</td>
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
                                                <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-4">Aucun rendez-vous enregistré</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Traitements -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clipboard2-pulse me-1"></i> Traitements
                    </h5>
                </div>
                <div class="card-body">
                    @if($patient->treatments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
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
                                            <td>{{ $treatment->treatment_type->name }}</td>
                                            <td>{{ $treatment->dentist->user->first_name }} {{ $treatment->dentist->user->last_name }}</td>
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
                                                <a href="{{ route('treatments.show', $treatment) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-4">Aucun traitement enregistré</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Factures -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-receipt me-1"></i> Factures
                    </h5>
                </div>
                <div class="card-body">
                    @if($patient->invoices->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
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
                                        <tr>
                                            <td>{{ $invoice->invoice_number }}</td>
                                            <td>{{ $invoice->issue_date->format('d/m/Y') }}</td>
                                            <td>{{ $invoice->due_date->format('d/m/Y') }}</td>
                                            <td>{{ number_format($invoice->total_amount, 2) }} €</td>
                                            <td>{{ number_format($invoice->insurance_amount, 2) }} €</td>
                                            <td>{{ number_format($invoice->patient_amount, 2) }} €</td>
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
                                                <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-4">Aucune facture enregistrée</p>
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
</style>
@endpush
@endsection
