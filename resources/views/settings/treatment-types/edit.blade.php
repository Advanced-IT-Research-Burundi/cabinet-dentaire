@extends('layouts.app')

@section('title', 'Modifier le type de traitement')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Modifier le type de traitement</h1>
                <a href="{{ route('settings.treatment-types.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Retour
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('settings.treatment-types.update', $treatmentType) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="code" class="form-label">Code</label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                    id="code" name="code" value="{{ old('code', $treatmentType->code) }}" maxlength="20">
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Code unique pour identifier le traitement</div>
                            </div>

                            <div class="col-md-6">
                                <label for="category" class="form-label">Catégorie</label>
                                <input type="text" class="form-control @error('category') is-invalid @enderror" 
                                    id="category" name="category" value="{{ old('category', $treatmentType->category) }}" maxlength="100">
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Nom du traitement</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                id="name" name="name" value="{{ old('name', $treatmentType->name) }}" required maxlength="100">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                id="description" name="description" rows="3">{{ old('description', $treatmentType->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="base_price" class="form-label">Prix de base (BIF)</label>
                                <input type="number" class="form-control @error('base_price') is-invalid @enderror" 
                                    id="base_price" name="base_price" value="{{ old('base_price', $treatmentType->base_price) }}" min="0" step="1000">
                                @error('base_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="average_duration" class="form-label">Durée moyenne (minutes)</label>
                                <input type="number" class="form-control @error('average_duration') is-invalid @enderror" 
                                    id="average_duration" name="average_duration" value="{{ old('average_duration', $treatmentType->average_duration) }}" min="1">
                                @error('average_duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input @error('active') is-invalid @enderror" 
                                    id="active" name="active" value="1" {{ old('active', $treatmentType->active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="active">Actif</label>
                                @error('active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
