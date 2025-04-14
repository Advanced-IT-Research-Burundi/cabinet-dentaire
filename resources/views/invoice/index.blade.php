@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Factures</h1>
            <a href="{{ route('invoices.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nouvelle facture
            </a>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('invoices.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label for="patient" class="form-label">Patient</label>
                        <input type="text" class="form-control" id="patient" name="patient" value="{{ request('patient') }}" placeholder="Nom ou téléphone">
                    </div>

                    <div class="col-md-3">
                        <label for="date_from" class="form-label">Date de début</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                    </div>

                    <div class="col-md-3">
                        <label for="date_to" class="form-label">Date de fin</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                    </div>

                    <div class="col-md-2">
                        <label for="status" class="form-label">Statut</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Tous</option>
                            <option value="Brouillon" {{ request('status') === 'Brouillon' ? 'selected' : '' }}>Brouillon</option>
                            <option value="Payée" {{ request('status') === 'Payée' ? 'selected' : '' }}>Payée</option>
                            <option value="Impayée" {{ request('status') === 'Impayée' ? 'selected' : '' }}>Impayée</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Rechercher</button>
                        <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Réinitialiser</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>N° Facture</th>
                                <th>Patient</th>
                                <th>Date d'émission</th>
                                <th>Échéance</th>
                                <th>Montant</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoices as $invoice)
                                <tr>
                                    <td>{{ $invoice->id }}</td>
                                    <td>{{ $invoice->patient->full_name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($invoice->issue_date)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}</td>
                                    <td>{{ number_format($invoice->total_amount, 2, ',', ' ') }} FCFA</td>
                                    <td>
                                        <span class="badge bg-{{ $invoice->status === 'Brouillon' ? 'warning' : ($invoice->status === 'Payée' ? 'success' : 'danger') }}">
                                            {{ $invoice->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette facture ?')">
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

                <div class="d-flex justify-content-end mt-3">
                    {{ $invoices->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
