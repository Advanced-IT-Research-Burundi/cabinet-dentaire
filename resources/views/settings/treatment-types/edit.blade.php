@extends('layouts.app')

@section('title', isset($treatmentType) ? 'Modifier le type de traitement' : 'Nouveau type de traitement')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">{{ isset($treatmentType) ? 'Modifier le type de traitement' : 'Nouveau type de traitement' }}</h1>
                <a href="{{ route('settings.treatment-types.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Retour
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <form action="{{ isset($treatmentType) ? route('settings.treatment-types.update', $treatmentType) : route('settings.treatment-types.store') }}" method="POST">
                @csrf
                @if(isset($treatmentType))
                    @method('PUT')
                @endif

                @include('settings.treatment-types._form')
            </form>
        </div>

        <div class="col-md-4">
            {{-- Carte d'aide --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info bg-opacity-10">
                    <h5 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>Instructions
                    </h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Remplissez les champs pour {{ isset($treatmentType) ? 'modifier' : 'ajouter' }} un type de traitement.</p>
                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item d-flex align-items-center">
                            <i class="bi bi-hash text-primary me-2"></i>
                            <span>Le code permet d'identifier rapidement le traitement</span>
                        </li>
                        <li class="list-group-item d-flex align-items-center">
                            <i class="bi bi-tag text-primary me-2"></i>
                            <span>La catégorie permet de regrouper les traitements similaires</span>
                        </li>
                        <li class="list-group-item d-flex align-items-center">
                            <i class="bi bi-cash text-primary me-2"></i>
                            <span>Le prix de base peut être ajusté lors de la facturation</span>
                        </li>
                        <li class="list-group-item d-flex align-items-center">
                            <i class="bi bi-clock text-primary me-2"></i>
                            <span>La durée moyenne aide à planifier les rendez-vous</span>
                        </li>
                    </ul>
                    <div class="alert alert-info d-flex align-items-center">
                        <i class="bi bi-lightbulb flex-shrink-0 me-2"></i>
                        <div>
                            Seul le nom du traitement est obligatoire. Les autres champs sont recommandés pour une meilleure organisation.
                        </div>
                    </div>
                </div>
            </div>

            {{-- Exemples de catégories --}}
            <div class="card shadow-sm">
                <div class="card-header bg-primary bg-opacity-10">
                    <h5 class="mb-0">
                        <i class="bi bi-list-ul me-2"></i>Catégories suggérées
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="#" class="list-group-item list-group-item-action" onclick="setCategory('Prévention'); return false;">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Prévention</h6>
                            </div>
                            <small class="text-muted">Nettoyages, examens, radiographies</small>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" onclick="setCategory('Restauration'); return false;">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Restauration</h6>
                            </div>
                            <small class="text-muted">Obturations, couronnes, bridges</small>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" onclick="setCategory('Chirurgie'); return false;">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Chirurgie</h6>
                            </div>
                            <small class="text-muted">Extractions, implants</small>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" onclick="setCategory('Esthétique'); return false;">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Esthétique</h6>
                            </div>
                            <small class="text-muted">Blanchiment, facettes</small>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action" onclick="setCategory('Orthodontie'); return false;">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Orthodontie</h6>
                            </div>
                            <small class="text-muted">Appareils, aligneurs</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function setCategory(category) {
        document.getElementById('category').value = category;
    }
</script>
@endpush
@endsection
