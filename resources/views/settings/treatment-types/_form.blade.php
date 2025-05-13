{{-- _form.blade.php - Formulaire commun pour la création et modification d'un type de traitement --}}

<div class="card">
    <div class="card-header bg-primary bg-opacity-10">
        <h5 class="mb-0">
            <i class="bi bi-clipboard-plus me-2"></i>Informations du type de traitement
        </h5>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="code" class="form-label fw-semibold">Code</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-hash"></i></span>
                    <input type="text" class="form-control @error('code') is-invalid @enderror"
                        id="code" name="code" value="{{ old('code', $treatmentType->code ?? '') }}" maxlength="20">
                </div>
                <div class="form-text">Code unique pour identifier le traitement</div>
                @error('code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="category" class="form-label fw-semibold">Catégorie</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-tag"></i></span>
                    <input type="text" class="form-control @error('category') is-invalid @enderror"
                        id="category" name="category" value="{{ old('category', $treatmentType->category ?? '') }}" maxlength="100">
                </div>
                @error('category')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="name" class="form-label fw-semibold">Nom du traitement <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-file-medical"></i></span>
                <input type="text" class="form-control @error('name') is-invalid @enderror"
                    id="name" name="name" value="{{ old('name', $treatmentType->name ?? '') }}" required maxlength="100">
            </div>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label fw-semibold">Description</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-file-text"></i></span>
                <textarea class="form-control @error('description') is-invalid @enderror"
                    id="description" name="description" rows="3">{{ old('description', $treatmentType->description ?? '') }}</textarea>
            </div>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="base_price" class="form-label fw-semibold">Prix de base (BIF)</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-cash"></i></span>
                    <input type="number" class="form-control @error('base_price') is-invalid @enderror"
                        id="base_price" name="base_price" value="{{ old('base_price', $treatmentType->base_price ?? '') }}" min="0" step="1000">
                </div>
                @error('base_price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="average_duration" class="form-label fw-semibold">Durée moyenne (minutes)</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-clock"></i></span>
                    <input type="number" class="form-control @error('average_duration') is-invalid @enderror"
                        id="average_duration" name="average_duration" value="{{ old('average_duration', $treatmentType->average_duration ?? '') }}" min="1">
                </div>
                @error('average_duration')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold mb-3">Statut</label>
            <div class="form-check form-switch">
                <input type="checkbox" class="form-check-input @error('active') is-invalid @enderror"
                    id="active" name="active" value="1"
                    {{ (old('active', $treatmentType->active ?? '1') == '1') ? 'checked' : '' }}>
                <label class="form-check-label" for="active">
                    <span class="text-success" id="status_label">Actif</span>
                </label>
            </div>
            <div class="form-text">Indique si ce type de traitement est disponible pour les nouveaux rendez-vous.</div>
            @error('active')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <hr class="mb-4">

        {{-- Boutons d'action --}}
        <div class="d-flex justify-content-between">
            <button type="reset" class="btn btn-light">
                <i class="bi bi-arrow-clockwise me-1"></i>Réinitialiser
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i>
                {{ isset($treatmentType) ? 'Mettre à jour' : 'Enregistrer' }}
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const activeSwitch = document.getElementById('active');
        const statusLabel = document.getElementById('status_label');

        // Fonction pour mettre à jour le libellé de statut
        function updateStatusLabel() {
            if (activeSwitch.checked) {
                statusLabel.textContent = 'Actif';
                statusLabel.className = 'text-success';
            } else {
                statusLabel.textContent = 'Inactif';
                statusLabel.className = 'text-danger';
            }
        }

        // Initialisation
        updateStatusLabel();

        // Écouteurs d'événements
        activeSwitch.addEventListener('change', updateStatusLabel);
    });
</script>
@endpush
