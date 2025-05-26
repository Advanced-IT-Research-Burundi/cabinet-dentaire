@extends('layouts.app')

@section('title', 'Modifier la Catégorie')

@section('content')
<div class="container-fluid px-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i> Modifier la Catégorie</h5>
            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left-circle"></i> Retour à la liste
            </a>
        </div>

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">
                        <i class="bi bi-tag me-1"></i>Nom
                    </label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                           value="{{ old('name', $category->name) }}" required placeholder="Entrez le nom de la catégorie">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold">
                        <i class="bi bi-card-text me-1"></i>Description
                    </label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                              name="description" rows="3" placeholder="Ajoutez une brève description">{{ old('description', $category->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-4 text-end">
                    <a href="{{ route('categories.index') }}" class="btn btn-secondary me-2">
                        <i class="bi bi-x-circle me-1"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save2 me-1"></i> Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
