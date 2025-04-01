@extends('layouts.app')

@section('title', 'Paramètres')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">Paramètres du système</h1>
        </div>
    </div>

    <div class="row g-4">
        <!-- Types de traitements -->
        <div class="col-md-6 col-xl-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-clipboard2-pulse text-primary me-2"></i>
                            Types de traitements
                        </h5>
                        <a href="{{ route('settings.treatment-types.index') }}" class="btn btn-sm btn-primary">
                            Gérer
                        </a>
                    </div>
                    <p class="card-text text-muted">
                        Configurez les différents types de traitements disponibles, leurs coûts et durées.
                    </p>
                </div>
            </div>
        </div>

        <!-- Méthodes de paiement -->
        <div class="col-md-6 col-xl-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-credit-card text-success me-2"></i>
                            Méthodes de paiement
                        </h5>
                        <a href="{{ route('settings.payment-methods.index') }}" class="btn btn-sm btn-success">
                            Gérer
                        </a>
                    </div>
                    <p class="card-text text-muted">
                        Gérez les différentes méthodes de paiement acceptées par votre cabinet.
                    </p>
                </div>
            </div>
        </div>

        <!-- Placeholder pour futures options -->
        <div class="col-md-6 col-xl-4">
            <div class="card h-100 bg-light">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-plus-circle text-muted me-2"></i>
                            Plus d'options à venir
                        </h5>
                    </div>
                    <p class="card-text text-muted">
                        D'autres options de configuration seront ajoutées selon vos besoins.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
