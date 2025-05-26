@extends('layouts.app')

@section('title', 'Ajouter une Assurance')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="bi bi-shield-plus"></i> Ajouter une Assurance
        </h1>
        <a href="{{ route('assurances.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left-circle"></i> Retour à la liste
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('assurances.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">
                            <i class="bi bi-person-badge"></i> Nom
                        </label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="coverage_percentage" class="form-label">
                            <i class="bi bi-percent"></i> Pourcentage de Couverture
                        </label>
                        <input type="number" step="0.01" class="form-control @error('coverage_percentage') is-invalid @enderror" id="coverage_percentage" name="coverage_percentage" value="{{ old('coverage_percentage') }}" required>
                        @error('coverage_percentage')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="description" class="form-label">
                            <i class="bi bi-card-text"></i> Description
                        </label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-4 gap-2">

                        <button type="reset" class="btn btn-secondary">
                                <i class="bi bi-arrow-clockwise me-1"></i> Réinitialiser
                            </button>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Enregistrer
                        </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
