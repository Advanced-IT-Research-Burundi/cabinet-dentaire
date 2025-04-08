@extends('layouts.app')

@section('title', 'Créer une Commande/Facture')

@section('content')
<div class="container-fluid px-4 overflow-hidden">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Créer une Facture</h1>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">Retour à la liste</a>
    </div>

    <livewire:order />
    
</div>


@endsection
