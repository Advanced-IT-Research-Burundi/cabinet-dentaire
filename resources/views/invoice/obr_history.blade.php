@extends('layouts.app')

@section('content')

<div class="container mt-4">

    {{-- Formulaire de recherche --}}
    <div class="mb-3 card">
        <div class="card-body">
            <form action="{{ route('invoices_obr') }}" method="GET" class="row g-2">
                <div class="col-md-3">
                    <input type="text" name="facture_no" class="form-control"
                           placeholder="N° Facture" value="{{ request('facture_no') }}">
                </div>
                <div class="col-md-3">
                    <input type="text" name="patient" class="form-control"
                           placeholder="Nom du patient" value="{{ request('patient') }}">
                </div>
                <div class="col-md-3">
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-control">
                        <option value="">-- Statut --</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Envoyée OBR</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Non envoyée OBR</option>
                    </select>
                </div>
                <div class="col-md-12 text-end">
                    <button type="submit" class="btn btn-primary">Rechercher</button>
                    <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Réinitialiser</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Table des factures --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Liste des Factures</h5>
        </div>
        <div class="p-0 card-body">
            <table class="table mb-0 table-striped table-bordered">
                <thead class="">
                    <tr>
                        <th>#</th>
                        <th>FACTURE No</th>
                        <th>PATIENT</th>
                        <th>DATE</th>
                        <th>MONTANT</th>
                        <th>STATUS</th>
                        <th>REPONSE OBR</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($invoices as $key => $order)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->client['customer_name'] ?: '-' }}</td>
                            <td>{{ $order->created_at->format('d/m/Y') }}</td>
                            <td>{{ number_format($order->total_amount, 2, ',', ' ') }}</td>
                            <td>
                                @if($order->is_sent_to_obr)
                                    <span class="badge bg-success">Envoyée OBR</span>
                                @else
                                    <span class="badge bg-danger">Non envoyée</span>
                                @endif
                            </td>
                            <td>{{ $order->obrPointer->result ?? '-' }}</td>
                            <td>
                                <a href="">
                                    Afficher
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Aucune facture trouvée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@stop
