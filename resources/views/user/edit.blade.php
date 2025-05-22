@extends('layouts.app')

@section('title', 'Modifier Utilisateur')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center" >
                    <h5 class="card-title mb-0">
                        <i class="bi bi-person-gear me-2"></i>
                        Modifier l'utilisateur
                    </h5>
                     <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Retour à la liste
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <!-- Photo de profil -->
                            <div class="col-12">
                                <div class="text-center mb-4">
                                    <div class="photo-preview-container position-relative d-inline-block">
                                        <img id="photo-preview"
                                             src="{{ $user->photo_url ? Storage::url($user->photo_url) : 'data:image/svg+xml,%3csvg width=\'120\' height=\'120\' xmlns=\'http://www.w3.org/2000/svg\'%3e%3crect width=\'100%25\' height=\'100%25\' fill=\'%23dee2e6\'/%3e%3ctext x=\'50%25\' y=\'50%25\' font-size=\'20\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%236c757d\'%3ePhoto%3c/text%3e%3c/svg%3e' }}"
                                             alt="Photo de profil"
                                             class="rounded-circle border"
                                             style="width: 120px; height: 120px; object-fit: cover;">
                                        <label for="photo" class="position-absolute bottom-0 end-0 btn btn-primary btn-sm rounded-circle p-1" style="transform: translate(25%, 25%);">
                                            <i class="bi bi-camera-fill"></i>
                                        </label>
                                    </div>
                                    @if($user->photo_url)
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                <i class="bi bi-info-circle me-1"></i>
                                                Photo actuelle
                                            </small>
                                        </div>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label for="photo" class="form-label">
                                        <i class="bi bi-image me-1"></i>
                                        Nouvelle photo de profil
                                    </label>
                                    <input type="file" class="form-control @error('photo') is-invalid @enderror"
                                           id="photo" name="photo" accept="image/*">
                                    <div class="form-text">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Laissez vide pour conserver la photo actuelle. Formats acceptés: JPG, PNG, GIF. Taille maximale: 2MB
                                    </div>
                                    @error('photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Informations personnelles -->
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">
                                    <i class="bi bi-person me-1"></i>
                                    Prénom <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                       id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="last_name" class="form-label">
                                    <i class="bi bi-person me-1"></i>
                                    Nom <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                       id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope me-1"></i>
                                    Email <span class="text-danger">*</span>
                                </label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label">
                                    <i class="bi bi-telephone me-1"></i>
                                    Téléphone
                                </label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                       id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Mot de passe (optionnel pour modification) -->
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Mot de passe :</strong> Laissez les champs vides si vous ne souhaitez pas modifier le mot de passe.
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock me-1"></i>
                                    Nouveau mot de passe
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                           id="password" name="password">
                                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                        <i class="bi bi-eye" id="togglePasswordIcon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">
                                    <i class="bi bi-lock-fill me-1"></i>
                                    Confirmer le nouveau mot de passe
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control"
                                           id="password_confirmation" name="password_confirmation">
                                    <button type="button" class="btn btn-outline-secondary" id="togglePasswordConfirm">
                                        <i class="bi bi-eye" id="togglePasswordConfirmIcon"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Rôle et Statut -->
                            <div class="col-md-6">
                                <label for="role" class="form-label">
                                    <i class="bi bi-shield-check me-1"></i>
                                    Rôle <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('role') is-invalid @enderror"
                                        id="role" name="role" required>
                                    <option value="">Sélectionner un rôle</option>
                                    @foreach (ROLE_USERS as $key => $value)
                                        <option value="{{ $key }}" {{ old('role', $user->role) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="statut" class="form-label">
                                    <i class="bi bi-toggle-on me-1"></i>
                                    Statut <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('statut') is-invalid @enderror"
                                        id="statut" name="statut" required>
                                    <option value="Actif" {{ old('statut', $user->statut) == 'Actif' ? 'selected' : '' }}>
                                        Actif
                                    </option>
                                    <option value="Inactif" {{ old('statut', $user->statut) == 'Inactif' ? 'selected' : '' }}>
                                        Inactif
                                    </option>
                                </select>
                                @error('statut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Informations supplémentaires -->
                            <div class="col-md-6">
                                <label for="secondary_phone" class="form-label">
                                    <i class="bi bi-telephone-plus me-1"></i>
                                    Téléphone secondaire
                                </label>
                                <input type="tel" class="form-control @error('secondary_phone') is-invalid @enderror"
                                       id="secondary_phone" name="secondary_phone" value="{{ old('secondary_phone', $user->secondary_phone) }}">
                                @error('secondary_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="secondary_email" class="form-label">
                                    <i class="bi bi-envelope-plus me-1"></i>
                                    Email secondaire
                                </label>
                                <input type="email" class="form-control @error('secondary_email') is-invalid @enderror"
                                       id="secondary_email" name="secondary_email" value="{{ old('secondary_email', $user->secondary_email) }}">
                                @error('secondary_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="adresse" class="form-label">
                                    <i class="bi bi-house me-1"></i>
                                    Adresse
                                </label>
                                <input type="text" class="form-control @error('adresse') is-invalid @enderror"
                                       id="adresse" name="adresse" value="{{ old('adresse', $user->adresse) }}">
                                @error('adresse')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="ville" class="form-label">
                                    <i class="bi bi-building me-1"></i>
                                    Ville
                                </label>
                                <input type="text" class="form-control @error('ville') is-invalid @enderror"
                                       id="ville" name="ville" value="{{ old('ville', $user->ville) }}">
                                @error('ville')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="code_postal" class="form-label">
                                    <i class="bi bi-mailbox me-1"></i>
                                    Code postal
                                </label>
                                <input type="text" class="form-control @error('code_postal') is-invalid @enderror"
                                       id="code_postal" name="code_postal" value="{{ old('code_postal', $user->code_postal) }}">
                                @error('code_postal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="pays" class="form-label">
                                    <i class="bi bi-globe me-1"></i>
                                    Pays
                                </label>
                                <input type="text" class="form-control @error('pays') is-invalid @enderror"
                                       id="pays" name="pays" value="{{ old('pays', $user->pays) }}">
                                @error('pays')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <hr>
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left me-1"></i>
                                        Retour
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Mettre à jour
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Aperçu de l'image
    const photoInput = document.getElementById('photo');
    const photoPreview = document.getElementById('photo-preview');

    photoInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                photoPreview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // Toggle password visibility
    function togglePasswordVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);

        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    }

    document.getElementById('togglePassword').addEventListener('click', function() {
        togglePasswordVisibility('password', 'togglePasswordIcon');
    });

    document.getElementById('togglePasswordConfirm').addEventListener('click', function() {
        togglePasswordVisibility('password_confirmation', 'togglePasswordConfirmIcon');
    });
});
</script>
@endsection
