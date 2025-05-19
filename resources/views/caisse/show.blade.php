@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Caisse Transaction Details</h2>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Transaction Information</h5>
            <table class="table">
                <tr>
                    <th>ID</th>
                    <td>{{ $caisse->id }}</td>
                </tr>
                <tr>
                    <th>Type</th>
                    <td>{{ $caisse->type }}</td>
                </tr>
                <tr>
                    <th>Date</th>
                    <td>{{ \Carbon\Carbon::createFromTimestamp($caisse->date)->format('Y-m-d H:i:s') }}</td>
                </tr>
                <tr>
                    <th>Amount</th>
                    <td>{{ $caisse->montant }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ $caisse->status }}</td>
                </tr>
            </table>

            <h5 class="mt-4 card-title">Caisse Details</h5>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th>Status</th>
                        
                    </tr>
                </thead>
                <tbody>
                    @foreach($caisse->caisseDetails as $detail)
                    <tr>
                        <td>{{ $detail->id }}</td>
                        <td>{{ $detail->type }}</td>
                        <td>{{ $detail->price }}</td>
                        <td>{{ $detail->total }}</td>
                        <td>{{ $detail->status }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
