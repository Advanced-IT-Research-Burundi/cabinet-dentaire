@extends('layouts.app')

@section('title', 'Liste des Commandes')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Liste des Factures</h1>

        <a href="{{ route('invoices.create') }}" class="btn btn-primary" type="button">
            Créer une Facture
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Patient</th>
                        <th>Date d'Émission</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $order->patient->name ?? '-' }}</td>
                            <td>{{ $order->date_emission ?? '-' }}</td>
                            <td>{{ number_format($order->amount, 2) }} Fbu</td>
                            <td>{{ $order->status }}</td>
                            <td class="text-center">
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i> Voir
                                </a>
                                <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i> Modifier
                                </a>
                                <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette commande ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i> Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Aucune commande trouvée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
