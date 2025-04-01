@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Ajouter un Dentiste</h1>
                <a href="{{ route('dentists.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Retour
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('dentists.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="user_id" class="form-label">Utilisateur</label>
                            <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                                <option value="">Sélectionner un utilisateur</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->prenom }} {{ $user->nom }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="specialty" class="form-label">Spécialité</label>
                            <input type="text" class="form-control @error('specialty') is-invalid @enderror" 
                                id="specialty" name="specialty" value="{{ old('specialty') }}" required>
                            @error('specialty')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="license_number" class="form-label">Numéro de Licence</label>
                            <input type="text" class="form-control @error('license_number') is-invalid @enderror" 
                                id="license_number" name="license_number" value="{{ old('license_number') }}" required>
                            @error('license_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="biography" class="form-label">Biographie</label>
                            <textarea class="form-control @error('biography') is-invalid @enderror" 
                                id="biography" name="biography" rows="4">{{ old('biography') }}</textarea>
                            @error('biography')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="calendar_color" class="form-label">Couleur du Calendrier</label>
                            <input type="color" class="form-control form-control-color @error('calendar_color') is-invalid @enderror" 
                                id="calendar_color" name="calendar_color" value="{{ old('calendar_color', '#0d6efd') }}" required>
                            @error('calendar_color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input @error('is_active') is-invalid @enderror" 
                                    id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Actif</label>
                            </div>
                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="reset" class="btn btn-light">Réinitialiser</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Instructions</h5>
                    <p class="card-text">Remplissez tous les champs obligatoires (*) pour ajouter un nouveau dentiste.</p>
                    <ul class="list-unstyled mb-0">
                        <li><i class="bi bi-dot"></i> Sélectionnez un utilisateur existant</li>
                        <li><i class="bi bi-dot"></i> Spécifiez la spécialité du dentiste</li>
                        <li><i class="bi bi-dot"></i> Entrez un numéro de licence unique</li>
                        <li><i class="bi bi-dot"></i> Choisissez une couleur pour le calendrier</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
