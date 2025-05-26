@extends('layouts.app')

@section('title', 'Créer une Nouvelle Catégorie')

@section('content')
<div class="container-fluid px-4">

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i> Créer une Nouvelle Catégorie</h5>
            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left-circle"></i> Retour à la liste
        </a>
        </div>
        <div class="card-body">
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">
                        <i class="bi bi-tag me-1"></i>Nom
                    </label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required placeholder="Entrez le nom de la catégorie">
                </div>

                <!-- Champ Description -->
                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold">
                        <i class="bi bi-card-text me-1"></i>Description
                    </label>
                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="Ajoutez une brève description">{{ old('description') }}</textarea>
                </div>

                <div class="mt-4 text-end">
                    <button type="reset" class="btn btn-secondary me-2">
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
@endsection
