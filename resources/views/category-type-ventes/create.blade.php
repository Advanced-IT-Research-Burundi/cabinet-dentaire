@extends('layouts.app')

@section('title', 'Nouveau Type de Vente')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 text-gray-800">
                        <i class="bi bi-plus-circle-fill text-primary me-2"></i> Créer un Nouveau Type de Vente
                    </h5>
                    <a href="{{ route('category-type-ventes.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left-circle me-1"></i> Retour à la liste
                    </a>
                </div>

                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <strong>Veuillez corriger les erreurs suivantes :</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('category-type-ventes.store') }}" method="POST">
                        @csrf

                        <!-- Champ Nom -->
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">
                                <i class="bi bi-tag me-1 text-primary"></i>Nom du Type de Vente <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   required
                                   placeholder="ex: ACTES, MEDICAMENTS, MATERIELS, PARAMEDICAUX...">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted">
                                Indiquez le nom de la catégorie ou du type de vente (ex. ACTES, MEDICAMENTS, etc.)
                            </div>
                        </div>

                        <!-- Champ Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">
                                <i class="bi bi-card-text me-1 text-primary"></i>Description
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="4"
                                      placeholder="Ajoutez une brève description de cette catégorie de vente...">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('category-type-ventes.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i> Annuler
                            </a>
                            <button type="reset" class="btn btn-light border">
                                <i class="bi bi-arrow-clockwise me-1"></i> Réinitialiser
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save2 me-1"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
