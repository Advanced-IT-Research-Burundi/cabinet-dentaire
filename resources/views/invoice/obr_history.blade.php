@extends('layouts.app')

@section('content')

<div class="mt-2 container-full">

    {{-- Formulaire de recherche --}}
    <div class="mb-1 card">
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
    <div class="">
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
                                @if($order->is_canceled)
                                    <span class="badge bg-danger">Annulée</span>
                                @elseif($order->is_sent_to_obr)
                                    <span class="badge bg-success">Envoyée OBR</span>
                                @else
                                    <span class="badge bg-danger">Non envoyée</span>
                                @endif
                            </td>
                            <td>
                                @if(isset($order->obrPointer->result) )
                                @php
                                    $result = json_decode($order->obrPointer->result);
                                @endphp
                                    <span> N° : {{ $result->invoice_number ?? "" }}</span>
                                    <span> | {{ $result->invoice_registered_date ?? "" }}</span>
                                    <span> | {{ $result->invoice_registered_number ?? "" }}</span>
                                    <span class="badge bg-secondary">
                                        <small>{{$order->obrPointer->msg}}</small>
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <small>{{$order->obrPointer->msg}}</small>
                                    </span>
                                @endif
                                @if($order->is_canceled)
                                 <br>
                                    <span class="badge bg-danger">
                                        <small>{{$order->is_canceled_reason}}</small>
                                    </span>
                                @endif
                            </td>
                            <td class="d-flex gap-2">
                                <a href="{{ route('invoices.show', $order->id) }}">
                                    Afficher
                                </a>
                                <a href="javascript:void(0);" onclick="cancelInvoice({{ $order->id }})">
                                    Annuler
                                    <i class="fas fa-times"></i>
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

            {{ $invoices->links() }}
        </div>
    </div>

</div>

@stop

@section('scripts')
<script>
    function cancelInvoice(orderId) {
        // Prompt for the reason
        var reason = prompt("Please provide a reason for cancellation:");

        if (reason) {
            // If a reason is provided, proceed to the cancellation route
            window.location.href = "{{ route('invoices.cancel-to-obr', ':orderId') }}".replace(':orderId', orderId) + '?reason=' + encodeURIComponent(reason);
        } else {
            alert('Cancellation reason is required.');
        }
    }
</script>
@endsection
