{{-- _form.blade.php - Formulaire commun pour la création et modification d'un dentiste --}}

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary bg-opacity-10">
                <h5 class="mb-0">
                    <i class="bi bi-person-vcard me-2"></i>Informations du dentiste
                </h5>
            </div>
            <div class="card-body">
                {{-- Sélection de l'utilisateur --}}
                <div class="mb-3">
                    <label for="user_id" class="form-label fw-semibold">Utilisateur <span class="text-danger">*</span></label>
                    <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror" required {{ isset($dentist) ? 'disabled' : '' }}>
                        <option value="">Sélectionner un utilisateur</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}"
                                {{ (old('user_id', $dentist->user_id ?? '')) == $user->id ? 'selected' : '' }}>
                                {{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @if(isset($dentist))
                        <input type="hidden" name="user_id" value="{{ $dentist->user_id }}">
                        <div class="form-text text-muted">
                            <i class="bi bi-info-circle me-1"></i>L'utilisateur ne peut pas être modifié une fois le dentiste créé.
                        </div>
                    @endif
                    @error('user_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    {{-- Spécialité --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="specialty" class="form-label fw-semibold">Spécialité <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-award"></i></span>
                                <input type="text" class="form-control @error('specialty') is-invalid @enderror"
                                    id="specialty" name="specialty" value="{{ old('specialty', $dentist->specialty ?? '') }}" required>
                            </div>
                            @error('specialty')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Numéro de licence --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="license_number" class="form-label fw-semibold">Numéro de Licence <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-credit-card"></i></span>
                                <input type="text" class="form-control @error('license_number') is-invalid @enderror"
                                    id="license_number" name="license_number" value="{{ old('license_number', $dentist->license_number ?? '') }}" required>
                            </div>
                            @error('license_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Biographie --}}
                <div class="mb-3">
                    <label for="biography" class="form-label fw-semibold">Biographie</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-file-text"></i></span>
                        <textarea class="form-control @error('biography') is-invalid @enderror"
                            id="biography" name="biography" rows="5">{{ old('biography', $dentist->biography ?? '') }}</textarea>
                    </div>
                    <div class="form-text">Entrez une courte biographie professionnelle du dentiste.</div>
                    @error('biography')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    {{-- Couleur du calendrier --}}
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label for="calendar_color" class="form-label fw-semibold">Couleur du Calendrier <span class="text-danger">*</span></label>
                            <div class="d-flex align-items-center">
                                <input type="color" class="form-control form-control-color me-2 @error('calendar_color') is-invalid @enderror"
                                    id="calendar_color" name="calendar_color" value="{{ old('calendar_color', $dentist->calendar_color ?? '#0d6efd') }}" required>
                                <span id="color_preview" class="text-muted">{{ old('calendar_color', $dentist->calendar_color ?? '#0d6efd') }}</span>
                            </div>
                            <div class="form-text">Cette couleur représentera le dentiste dans le calendrier des rendez-vous.</div>
                            @error('calendar_color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Status (disponible ou non) --}}
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label class="form-label fw-semibold mb-3">Statut</label>
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input @error('available') is-invalid @enderror"
                                    id="available" name="available" value="1"
                                    {{ (old('available', $dentist->available ?? '1') == '1') ? 'checked' : '' }}>
                                <label class="form-check-label" for="available">
                                    <span class="text-success" id="status_label">Disponible</span>
                                </label>
                            </div>
                            <div class="form-text">Indique si le dentiste est actuellement disponible pour prendre des rendez-vous.</div>
                            @error('available')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr class="mb-4">

                {{-- Boutons d'action --}}
                <div class="d-flex justify-content-between">
                    <button type="reset" class="btn btn-light">
                        <i class="bi bi-arrow-clockwise me-1"></i>Réinitialiser
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>
                        {{ isset($dentist) ? 'Mettre à jour' : 'Enregistrer' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        {{-- Carte d'aide --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-info bg-opacity-10">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>Instructions
                </h5>
            </div>
            <div class="card-body">
                <p class="card-text">Remplissez tous les champs obligatoires (*) pour {{ isset($dentist) ? 'modifier' : 'ajouter' }} un dentiste.</p>
                <ul class="list-group list-group-flush mb-3">
                    <li class="list-group-item d-flex align-items-center">
                        <i class="bi bi-person text-primary me-2"></i>
                        <span>{{ isset($dentist) ? 'L\'utilisateur associé ne peut être changé' : 'Sélectionnez un utilisateur existant' }}</span>
                    </li>
                    <li class="list-group-item d-flex align-items-center">
                        <i class="bi bi-award text-primary me-2"></i>
                        <span>Spécifiez la spécialité du dentiste</span>
                    </li>
                    <li class="list-group-item d-flex align-items-center">
                        <i class="bi bi-credit-card text-primary me-2"></i>
                        <span>Entrez un numéro de licence unique</span>
                    </li>
                    <li class="list-group-item d-flex align-items-center">
                        <i class="bi bi-palette text-primary me-2"></i>
                        <span>Choisissez une couleur pour le calendrier</span>
                    </li>
                </ul>
                <div class="alert alert-warning d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2"></i>
                    <div>
                        Assurez-vous que l'utilisateur sélectionné n'est pas déjà associé à un autre dentiste.
                    </div>
                </div>
            </div>
        </div>

        {{-- Carte pour expliquer les différentes spécialités dentaires --}}
        <div class="card shadow-sm">
            <div class="card-header bg-primary bg-opacity-10">
                <h5 class="mb-0">
                    <i class="bi bi-lightbulb me-2"></i>Spécialités Dentaires
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <a href="#" class="list-group-item list-group-item-action" onclick="setSpecialty('Orthodontie'); return false;">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">Orthodontie</h6>
                        </div>
                        <small class="text-muted">Correction de l'alignement des dents</small>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action" onclick="setSpecialty('Implantologie'); return false;">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">Implantologie</h6>
                        </div>
                        <small class="text-muted">Pose d'implants dentaires</small>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action" onclick="setSpecialty('Parodontologie'); return false;">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">Parodontologie</h6>
                        </div>
                        <small class="text-muted">Traitement des maladies gingivales</small>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action" onclick="setSpecialty('Endodontie'); return false;">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">Endodontie</h6>
                        </div>
                        <small class="text-muted">Traitement des canaux dentaires</small>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action" onclick="setSpecialty('Dentisterie générale'); return false;">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">Dentisterie générale</h6>
                        </div>
                        <small class="text-muted">Soins dentaires courants</small>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Mise à jour du libellé de statut lors du changement du switch
    document.addEventListener('DOMContentLoaded', function() {
        const availableSwitch = document.getElementById('available');
        const statusLabel = document.getElementById('status_label');
        const colorInput = document.getElementById('calendar_color');
        const colorPreview = document.getElementById('color_preview');

        // Fonction pour mettre à jour le libellé de statut
        function updateStatusLabel() {
            if (availableSwitch.checked) {
                statusLabel.textContent = 'Disponible';
                statusLabel.className = 'text-success';
            } else {
                statusLabel.textContent = 'Non disponible';
                statusLabel.className = 'text-danger';
            }
        }

        // Fonction pour mettre à jour l'aperçu de la couleur
        function updateColorPreview() {
            colorPreview.textContent = colorInput.value;
        }

        // Initialisation
        updateStatusLabel();

        // Écouteurs d'événements
        availableSwitch.addEventListener('change', updateStatusLabel);
        colorInput.addEventListener('input', updateColorPreview);
    });

    // Fonction pour définir une spécialité
    function setSpecialty(specialty) {
        document.getElementById('specialty').value = specialty;
    }
</script>
@endpush
