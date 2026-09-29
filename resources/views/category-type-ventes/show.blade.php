@extends('layouts.app')

@section('title', 'Détails du Type de Vente')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 text-gray-800">
                        <i class="bi bi-info-circle-fill text-primary me-2"></i> Détails du Type de Vente
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('category-type-ventes.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left-circle me-1"></i> Retour à la liste
                        </a>
                        <a href="{{ route('category-type-ventes.edit', $categoryTypeVente) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-pencil me-1"></i> Modifier
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-12">
                            <div class="p-3 bg-light rounded-3">
                                <label class="text-muted small text-uppercase fw-semibold mb-1 d-block">
                                    <i class="bi bi-tag text-primary me-1"></i> Nom du Type de Vente
                                </label>
                                <h4 class="mb-0 text-dark fw-bold">{{ $categoryTypeVente->name }}</h4>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="p-3 bg-light rounded-3">
                                <label class="text-muted small text-uppercase fw-semibold mb-1 d-block">
                                    <i class="bi bi-card-text text-primary me-1"></i> Description
                                </label>
                                <p class="mb-0 text-dark">
                                    {{ $categoryTypeVente->description ?: 'Aucune description fournie.' }}
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3">
                                <label class="text-muted small text-uppercase fw-semibold mb-1 d-block">
                                    <i class="bi bi-calendar-check text-primary me-1"></i> Date de création
                                </label>
                                <span class="text-dark">{{ $categoryTypeVente->created_at ? $categoryTypeVente->created_at->format('d/m/Y à H:i:s') : '-' }}</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3">
                                <label class="text-muted small text-uppercase fw-semibold mb-1 d-block">
                                    <i class="bi bi-calendar-event text-primary me-1"></i> Dernière mise à jour
                                </label>
                                <span class="text-dark">{{ $categoryTypeVente->updated_at ? $categoryTypeVente->updated_at->format('d/m/Y à H:i:s') : '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
