<!-- _form.blade.php - Formulaire commun pour create et edit -->

@csrf
@if(isset($patient) && $patient->id)
    @method('PUT')
@endif

<!-- Type de patient -->
<div class="col-md-4">
    <label for="patient_type" class="form-label required">Type de patient</label>
    <select class="form-select @error('patient_type') is-invalid @enderror"
            id="patient_type" name="patient_type" required>
        <option value="">Sélectionner...</option>
        <option value="physique" {{ old('patient_type', $patient->patient_type ?? '') == 'physique' ? 'selected' : '' }}>Personne physique</option>
        <option value="morale" {{ old('patient_type', $patient->patient_type ?? '') == 'morale' ? 'selected' : '' }}>Personne morale</option>
    </select>
    @error('patient_type')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<!-- NIF (Visible uniquement pour personne morale) -->
<div class="col-md-8" id="nif_container" style="display: {{ old('patient_type', $patient->patient_type ?? '') == 'morale' ? 'flex' : 'none' }}; flex-wrap: wrap;">
    <div class="row w-100">
        <div class="col-md-6">
            <label for="nif" class="form-label required">NIF</label>
            <input type="text" class="form-control @error('nif') is-invalid @enderror"
                   id="nif" name="nif" value="{{ old('nif', $patient->nif ?? '') }}"
                   {{ old('patient_type', $patient->patient_type ?? '') == 'morale' ? 'required' : '' }}>
            @error('nif')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label for="societe" class="form-label required">Société</label>
            <input type="text" class="form-control @error('societe') is-invalid @enderror"
                   id="societe" name="societe" value="{{ old('societe', $patient->societe ?? '') }}"
                   {{ old('patient_type', $patient->patient_type ?? '') == 'morale' ? 'required' : '' }}>
            @error('societe')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>


<!-- Informations personnelles -->
<div class="col-md-4">
    <label for="first_name" class="form-label required">Prénom</label>
    <input type="text" class="form-control @error('first_name') is-invalid @enderror"
           id="first_name" name="first_name" required
           value="{{ old('first_name', $patient->first_name ?? '') }}">
    @error('first_name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="col-md-4">
    <label for="middle_name" class="form-label">Deuxième prénom</label>
    <input type="text" class="form-control @error('middle_name') is-invalid @enderror"
           id="middle_name" name="middle_name"
           value="{{ old('middle_name', $patient->middle_name ?? '') }}">
    @error('middle_name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="col-md-4">
    <label for="last_name" class="form-label">Nom</label>
    <input type="text" class="form-control @error('last_name') is-invalid @enderror"
           id="last_name" name="last_name"
           value="{{ old('last_name', $patient->last_name ?? '') }}">
    @error('last_name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="col-md-4">
    <label for="birth_date" class="form-label required">Date de naissance</label>
    <input type="date" class="form-control @error('birth_date') is-invalid @enderror"
           id="birth_date" name="birth_date" required
           value="{{ old('birth_date', isset($patient->birth_date) ? $patient->birth_date->format('Y-m-d') : '') }}">
    @error('birth_date')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="col-md-4">
    <label for="gender" class="form-label required">Genre</label>
    <select class="form-select @error('gender') is-invalid @enderror"
            id="gender" name="gender" required>
        <option value="">Sélectionner...</option>
        <option value="M" {{ old('gender', $patient->gender ?? '') == 'M' ? 'selected' : '' }}>Homme</option>
        <option value="F" {{ old('gender', $patient->gender ?? '') == 'F' ? 'selected' : '' }}>Femme</option>
        <option value="Autre" {{ old('gender', $patient->gender ?? '') == 'Autre' ? 'selected' : '' }}>Autre</option>
    </select>
    @error('gender')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<!-- Contact -->
<div class="col-md-4">
    <label for="phone" class="form-label">Téléphone</label>
    <input type="tel" class="form-control @error('phone') is-invalid @enderror"
           id="phone" name="phone"
           value="{{ old('phone', $patient->phone ?? '') }}">
    @error('phone')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="col-md-4">
    <label for="secondary_phone" class="form-label">Téléphone secondaire</label>
    <input type="tel" class="form-control @error('secondary_phone') is-invalid @enderror"
           id="secondary_phone" name="secondary_phone"
           value="{{ old('secondary_phone', $patient->secondary_phone ?? '') }}">
    @error('secondary_phone')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="col-md-4">
    <label for="email" class="form-label">Email</label>
    <input type="email" class="form-control @error('email') is-invalid @enderror"
           id="email" name="email"
           value="{{ old('email', $patient->email ?? '') }}">
    @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<!-- Adresse -->
<div class="col-12">
    <label for="address" class="form-label">Adresse</label>
    <input type="text" class="form-control @error('address') is-invalid @enderror"
           id="address" name="address"
           value="{{ old('address', $patient->address ?? '') }}">
    @error('address')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="col-md-4">
    <label for="city" class="form-label">Ville</label>
    <input type="text" class="form-control @error('city') is-invalid @enderror"
           id="city" name="city"
           value="{{ old('city', $patient->city ?? '') }}">
    @error('city')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="col-md-4">
    <label for="postal_code" class="form-label">Code postal</label>
    <input type="text" class="form-control @error('postal_code') is-invalid @enderror"
           id="postal_code" name="postal_code"
           value="{{ old('postal_code', $patient->postal_code ?? '') }}">
    @error('postal_code')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="col-md-4">
    <label for="country" class="form-label">Pays</label>
    <input type="text" class="form-control @error('country') is-invalid @enderror"
           id="country" name="country"
           value="{{ old('country', $patient->country ?? '') }}">
    @error('country')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<!-- Assurance -->
<div class="col-md-6">
    <label for="insurance_number" class="form-label">Numéro d'assurance</label>
    <input type="text" class="form-control @error('insurance_number') is-invalid @enderror"
           id="insurance_number" name="insurance_number"
           value="{{ old('insurance_number', $patient->insurance_number ?? '') }}">
    @error('insurance_number')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="col-md-6">
    <label for="insurance_id" class="form-label">Assurance</label>
    <select id="insurance_id" name="insurance_id" class="form-select @error('insurance_id') is-invalid @enderror">
        <option value="" disabled selected>Choisir une assurance</option>
        @foreach($assurances as $assurance)
            <option value="{{ $assurance->id }}" {{ old('insurance_id', $patient->insurance_id ?? '') == $assurance->id ? 'selected' : '' }}>
                {{ $assurance->name }}
            </option>
        @endforeach
    </select>
    @error('insurance_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<!-- Informations médicales -->
<div class="col-md-6">
    <label for="medical_history" class="form-label">Antécédents médicaux</label>
    <textarea class="form-control @error('medical_history') is-invalid @enderror"
              id="medical_history" name="medical_history" rows="3">{{ old('medical_history', $patient->medical_history ?? '') }}</textarea>
    @error('medical_history')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="col-md-6">
    <label for="allergies" class="form-label">Allergies</label>
    <textarea class="form-control @error('allergies') is-invalid @enderror"
              id="allergies" name="allergies" rows="3">{{ old('allergies', $patient->allergies ?? '') }}</textarea>
    @error('allergies')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="col-12 mt-4">
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-save me-1"></i>
        {{ isset($patient) && $patient->id ? 'Mettre à jour' : 'Enregistrer' }}
    </button>
    <a href="{{ route('patients.index') }}" class="btn btn-secondary">
        <i class="bi bi-x-circle me-1"></i>
        Annuler
    </a>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const patientTypeSelect = document.getElementById('patient_type');
        const nifContainer = document.getElementById('nif_container');
        const nifInput = document.getElementById('nif');

        // Fonction pour afficher/masquer le champ NIF
        function toggleNifField() {
            if (patientTypeSelect.value === 'morale') {
                nifContainer.style.display = 'block';
                nifInput.setAttribute('required', 'required');
            } else {
                nifContainer.style.display = 'none';
                nifInput.removeAttribute('required');
            }
        }

        // Initial check
        toggleNifField();

        // Event listener pour les changements
        patientTypeSelect.addEventListener('change', toggleNifField);
    });
</script>
@endpush
