@extends('layouts.app')

@section('title', 'Nouvel Utilisateur')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Nouvel Utilisateur</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            <!-- Informations personnelles -->
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">Prénom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                       id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                       id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label">Téléphone</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Mot de passe -->
                            <div class="col-md-6">
                                <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirmer le mot de passe <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" required>
                            </div>

                            <!-- Rôle et Statut -->
                            <div class="col-md-6">
                                <label for="role" class="form-label">Rôle <span class="text-danger">*</span></label>
                                <select class="form-select @error('role') is-invalid @enderror" 
                                        id="role" name="role" required>
                                    <option value="">Sélectionner un rôle</option>
                                    <option value="Admin" {{ old('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="Dentiste" {{ old('role') == 'Dentiste' ? 'selected' : '' }}>Dentiste</option>
                                    <option value="Secretaire" {{ old('role') == 'Secretaire' ? 'selected' : '' }}>Secrétaire</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="statut" class="form-label">Statut <span class="text-danger">*</span></label>
                                <select class="form-select @error('statut') is-invalid @enderror" 
                                        id="statut" name="statut" required>
                                    <option value="Actif" {{ old('statut') == 'Actif' ? 'selected' : '' }}>Actif</option>
                                    <option value="Inactif" {{ old('statut') == 'Inactif' ? 'selected' : '' }}>Inactif</option>
                                </select>
                                @error('statut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Informations supplémentaires -->
                            <div class="col-md-6">
                                <label for="secondary_phone" class="form-label">Téléphone secondaire</label>
                                <input type="tel" class="form-control @error('secondary_phone') is-invalid @enderror" 
                                       id="secondary_phone" name="secondary_phone" value="{{ old('secondary_phone') }}">
                                @error('secondary_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="secondary_email" class="form-label">Email secondaire</label>
                                <input type="email" class="form-control @error('secondary_email') is-invalid @enderror" 
                                       id="secondary_email" name="secondary_email" value="{{ old('secondary_email') }}">
                                @error('secondary_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="adresse" class="form-label">Adresse</label>
                                <input type="text" class="form-control @error('adresse') is-invalid @enderror" 
                                       id="adresse" name="adresse" value="{{ old('adresse') }}">
                                @error('adresse')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="ville" class="form-label">Ville</label>
                                <input type="text" class="form-control @error('ville') is-invalid @enderror" 
                                       id="ville" name="ville" value="{{ old('ville') }}">
                                @error('ville')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="code_postal" class="form-label">Code postal</label>
                                <input type="text" class="form-control @error('code_postal') is-invalid @enderror" 
                                       id="code_postal" name="code_postal" value="{{ old('code_postal') }}">
                                @error('code_postal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="pays" class="form-label">Pays</label>
                                <input type="text" class="form-control @error('pays') is-invalid @enderror" 
                                       id="pays" name="pays" value="{{ old('pays') }}">
                                @error('pays')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="photo_url" class="form-label">URL de la photo</label>
                                <input type="text" class="form-control @error('photo_url') is-invalid @enderror" 
                                       id="photo_url" name="photo_url" value="{{ old('photo_url') }}">
                                @error('photo_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <hr>
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Annuler</a>
                                    <button type="submit" class="btn btn-primary">Créer l'utilisateur</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
