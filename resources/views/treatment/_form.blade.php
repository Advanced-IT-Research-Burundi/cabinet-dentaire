@csrf
@if(isset($treatment))
    @method('PUT')
@endif

<div class="mb-4 row">
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="appointment_select" class="form-label fw-bold">
                <i class="bi bi-calendar-check-fill me-2"></i>Rendez-vous <span class="text-danger">*</span>
            </label>
            <div class="select-container">
                <div class="custom-select @error('appointment_id') is-invalid @enderror">
                    <div class="select-selected" id="appointment_selected">
                        {{ isset($treatment) && $treatment->appointment ? \Carbon\Carbon::parse($treatment->appointment->start_time)->format('d/m/Y H:i') . ' - ' . $treatment->appointment->patient->full_name : 'Sélectionnez un rendez-vous' }}
                    </div>
                    <div class="select-dropdown">
                        <div class="select-search">
                            <input type="text" class="form-control" placeholder="Rechercher un rendez-vous..." id="appointment_search" >
                        </div>
                        <div class="select-options" id="appointment_options">
                            @foreach($appointments as $appointment)
                                <div class="select-option"
                                     data-value="{{ $appointment->id }}"
                                     data-patient-id="{{ $appointment->patient_id }}"
                                     data-patient-name="{{ $appointment->patient->full_name }}"
                                     data-dentist-id="{{ $appointment->dentist_id }}"
                                     data-dentist-name="{{ $appointment->dentist->user->full_name }}"
                                     data-date="{{ $appointment->date->format('Y-m-d') }}"
                                     data-planned-treatments="{{ $appointment->plannedTreatments->count() > 0 ? $appointment->plannedTreatments->map(function($pt) { return ['id' => $pt->id, 'name' => $pt->name, 'price' => $pt->base_price]; })->toJson() : ($appointment->plannedTreatment ? json_encode([['id' => $appointment->plannedTreatment->id, 'name' => $appointment->plannedTreatment->name, 'price' => $appointment->plannedTreatment->base_price]]) : '[]') }}">
                                    {{ $appointment->date->format('d/m/Y') }} - {{ $appointment->patient->full_name }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <input type="hidden" name="appointment_id" id="appointment_id" value="{{ old('appointment_id', isset($treatment) ? $treatment->appointment_id : '') }}">
                @error('appointment_id')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="mb-4 row">
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="patient_select" class="form-label fw-bold">
                <i class="bi bi-person-fill me-2"></i>Patient <span class="text-danger">*</span>
            </label>
            <div class="select-container">
                <div class="custom-select @error('patient_id') is-invalid @enderror">
                    <div class="select-selected bg-gray" id="patient_selected" >
                        {{ isset($treatment) && $treatment->patient ? $treatment->patient->full_name : 'Sélectionnez un patient' }}
                    </div>
                </div>
                <input type="hidden" name="patient_id" id="patient_id" value="{{ old('patient_id', isset($treatment) ? $treatment->patient_id : '') }}">
                @error('patient_id')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="dentist_select" class="form-label fw-bold">
                <i class="bi bi-person-badge-fill me-2"></i>Dentiste <span class="text-danger">*</span>
            </label>
            <div class="select-container">
                <div class="custom-select @error('dentist_id') is-invalid @enderror">
                    <div class="select-selected" id="dentist_selected">
                        @if (isset($treatment))
                                {{ $treatment->dentist ? ($treatment->dentist->user?->full_name ?? "#{$treatment->dentist_id}") : 'Sélectionnez un dentiste' }}
                            @else
                                Sélectionnez un dentiste
                            @endif

                    </div>
                    <div class="select-dropdown">
                        <div class="select-search">
                            <input type="text" class="form-control" placeholder="Rechercher un dentiste..." id="dentist_search">
                        </div>
                        <div class="select-options" id="dentist_options">
                            @foreach($dentists as $dentist)
                                <div class="select-option" data-value="{{ $dentist->id }}" data-display="{{ $dentist->id }} - {{ isset($treatment) ? $dentist->user->full_name : $dentist->user->first_name . ' ' . $dentist->user->last_name }}">
                                    {{ $dentist->id }} - {{ isset($treatment) ? $dentist->user->full_name : $dentist->user->first_name . ' ' . $dentist->user->last_name }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <input type="hidden" name="dentist_id" id="dentist_id" value="{{ old('dentist_id', isset($treatment) ? $treatment->dentist_id : '') }}">
                @error('dentist_id')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>

<div class="mb-4 row">
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="treatment_type_select" class="form-label fw-bold">
                <i class="bi bi-clipboard2-pulse-fill me-2"></i>Type de traitement <span class="text-danger">*</span>
            </label>
            <div class="multi-select-wrapper">
                <div class="multi-select-display border rounded p-2 d-flex flex-wrap gap-1 align-items-center @error('treatment_type_id') is-invalid @enderror" id="multiSelectDisplay">
                    @if(isset($treatment) && $treatment->treatmentTypes && $treatment->treatmentTypes->count() > 0)
                        {{-- Afficher les types déjà sélectionnés lors de la modification --}}
                        @foreach($treatment->treatmentTypes as $selectedType)
                            <span class="selected-item-tag badge rounded-pill px-2 py-1 bg-primary text-white">
                                {{ $selectedType->name }}
                                <span class="tag-close-btn ms-1" data-value="{{ $selectedType->id }}">×</span>
                            </span>
                        @endforeach
                    @else
                        <span class="placeholder-message text-muted fst-italic">Sélectionnez un ou plusieurs types</span>
                    @endif
                </div>
                <div class="multi-select-menu" id="multiSelectMenu">
                    <div class="p-2 border-bottom">
                        <input type="text" class="form-control form-control-sm" placeholder="Rechercher un type..." id="treatmentSearchInput">
                    </div>
                    <div class="menu-options-container" id="menuOptionsContainer">
                        @foreach($treatmentTypes as $type)
                            <div class="menu-option-item p-2 border-bottom d-flex align-items-center gap-2
                                @if(isset($treatment) && $treatment->treatmentTypes && $treatment->treatmentTypes->contains('id', $type->id)) option-selected @endif"
                                data-value="{{ $type->id }}" data-price="{{ $type->base_price }}" data-name="{{ $type->name }}">
                                <input type="checkbox" class="option-check-input form-check-input"
                                    @if(isset($treatment) && $treatment->treatmentTypes && $treatment->treatmentTypes->contains('id', $type->id)) checked @endif>
                                <span>{{ $type->name }} - {{ number_format($type->base_price, 0, ',', ' ') }} FBU</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <input type="hidden" name="treatment_type_id" id="selectedTreatmentTypes"
                    value="{{ old('treatment_type_id', isset($treatment) && $treatment->treatmentTypes ? $treatment->treatmentTypes->pluck('id')->implode(',') : '') }}">
                @error('treatment_type_id')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="date" class="form-label fw-bold">
                <i class="bi bi-calendar-date-fill me-2"></i>Date du traitement <span class="text-danger">*</span>
            </label>
            <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', isset($treatment) ? $treatment->date->format('Y-m-d') : '') }}" required>
            @error('date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="mb-4 row">
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="applied_price" class="form-label fw-bold">
                <i class="bi bi-currency-exchange me-2"></i>Prix appliqué
            </label>
            <div class="input-group">
                <input type="number" step="0.01" name="applied_price" id="applied_price" class="form-control @error('applied_price') is-invalid @enderror" value="{{ old('applied_price', isset($treatment) ? $treatment->applied_price : '') }}">
                <span class="input-group-text">FBU</span>
            </div>
            @error('applied_price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="status" class="form-label fw-bold">
                <i class="bi bi-flag-fill me-2"></i>Statut <span class="text-danger">*</span>
            </label>
            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                <option value="Planifie" {{ old('status', isset($treatment) ? $treatment->status : '') == 'Planifie' ? 'selected' : '' }}>Planifié</option>
                <option value="En_cours" {{ old('status', isset($treatment) ? $treatment->status : '') == 'En_cours' ? 'selected' : '' }}>En cours</option>
                <option value="Termine" {{ old('status', isset($treatment) ? $treatment->status : '') == 'Termine' ? 'selected' : '' }}>Terminé</option>
                <option value="Annule" {{ old('status', isset($treatment) ? $treatment->status : '') == 'Annule' ? 'selected' : '' }}>Annulé</option>
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="mb-4 row">
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="description" class="form-label fw-bold">
                <i class="bi bi-card-text me-2"></i>Description
            </label>
            <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', isset($treatment) ? $treatment->description : '') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="mb-4 row">
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="medical_notes" class="form-label fw-bold">
                <i class="bi bi-journal-medical me-2"></i>Notes médicales
            </label>
            <textarea name="medical_notes" id="medical_notes" rows="3" class="form-control @error('medical_notes') is-invalid @enderror">{{ old('medical_notes', isset($treatment) ? $treatment->medical_notes : '') }}</textarea>
            @error('medical_notes')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let selectedTreatments = new Map(); // Pour stocker les traitements sélectionnés avec leurs prix

    // Initialiser les traitements déjà sélectionnés (en cas de modification)
    @if(isset($treatment) && $treatment->treatmentTypes && $treatment->treatmentTypes->count() > 0)
        @foreach($treatment->treatmentTypes as $selectedType)
            selectedTreatments.set('{{ $selectedType->id }}', {
                id: '{{ $selectedType->id }}',
                name: '{{ $selectedType->name }}',
                price: {{ $selectedType->base_price }}
            });
        @endforeach
        updateTotalCalculated();
    @endif

    // Fonction pour mettre à jour le total calculé directement dans le prix appliqué
    function updateTotalCalculated() {
        let total = 0;
        selectedTreatments.forEach(treatment => {
            total += parseFloat(treatment.price) || 0;
        });

        // Mettre à jour directement le prix appliqué avec le total calculé
        const appliedPriceInput = document.getElementById('applied_price');
        appliedPriceInput.value = total;
    }

    // Fonction pour mettre à jour l'affichage du multi-select
    function updateMultiSelectDisplay() {
        const display = document.getElementById('multiSelectDisplay');
        const placeholder = display.querySelector('.placeholder-message');

        // Supprimer le placeholder s'il existe
        if (placeholder) {
            placeholder.remove();
        }

        // Supprimer tous les tags existants
        const existingTags = display.querySelectorAll('.selected-item-tag');
        existingTags.forEach(tag => tag.remove());

        // Ajouter les nouveaux tags
        selectedTreatments.forEach(treatment => {
            const tag = document.createElement('span');
            tag.className = 'selected-item-tag badge rounded-pill px-2 py-1 bg-primary text-white';
            tag.innerHTML = `${treatment.name} <span class="tag-close-btn ms-1" data-value="${treatment.id}">×</span>`;
            display.appendChild(tag);
        });

        // Ajouter le placeholder si aucun élément sélectionné
        if (selectedTreatments.size === 0) {
            const placeholderSpan = document.createElement('span');
            placeholderSpan.className = 'placeholder-message text-muted fst-italic';
            placeholderSpan.textContent = 'Sélectionnez un ou plusieurs types';
            display.appendChild(placeholderSpan);
        }

        // Mettre à jour l'input hidden
        const selectedIds = Array.from(selectedTreatments.keys()).join(',');
        document.getElementById('selectedTreatmentTypes').value = selectedIds;

        // Calculer et mettre le total dans le prix appliqué
        updateTotalCalculated();
    }

    // Fonction pour pré-remplir les types de traitement à partir du rendez-vous
    function fillTreatmentTypesFromAppointment(plannedTreatments) {
        try {
            const treatments = JSON.parse(plannedTreatments);
            if (treatments && treatments.length > 0) {
                // Vider la sélection actuelle
                selectedTreatments.clear();

                // Décocher toutes les options
                document.querySelectorAll('#menuOptionsContainer .option-check-input').forEach(checkbox => {
                    checkbox.checked = false;
                    checkbox.closest('.menu-option-item').classList.remove('option-selected');
                });

                // Ajouter les nouveaux traitements
                treatments.forEach(treatment => {
                    selectedTreatments.set(treatment.id.toString(), {
                        id: treatment.id.toString(),
                        name: treatment.name,
                        price: parseFloat(treatment.price) || 0
                    });

                    // Cocher l'option correspondante
                    const option = document.querySelector(`#menuOptionsContainer .menu-option-item[data-value="${treatment.id}"]`);
                    if (option) {
                        const checkbox = option.querySelector('.option-check-input');
                        if (checkbox) {
                            checkbox.checked = true;
                            option.classList.add('option-selected');
                        }
                    }
                });

                updateMultiSelectDisplay();
            }
        } catch (e) {
            console.log('Erreur lors du parsing des traitements planifiés:', e);
        }
    }

    // Fonction pour auto-compléter les champs lors de la sélection d'un rendez-vous
    const appointmentOptions = document.querySelectorAll('#appointment_options .select-option');

    appointmentOptions.forEach(option => {
        option.addEventListener('click', function() {
            const patientId = this.dataset.patientId;
            const patientName = this.dataset.patientName;
            const dentistId = this.dataset.dentistId;
            const dentistName = this.dataset.dentistName;
            const appointmentDate = this.dataset.date;
            const plannedTreatments = this.dataset.plannedTreatments;

            // Compléter le patient
            if (patientId) {
                document.getElementById('patient_id').value = patientId;
                document.getElementById('patient_selected').textContent = `${patientId} - ${patientName}`;
            }

            // Compléter le dentiste
            if (dentistId) {
                document.getElementById('dentist_id').value = dentistId;
                document.getElementById('dentist_selected').textContent = `${dentistId} - ${dentistName}`;
            }

            // Compléter la date du traitement avec la date du rendez-vous
            if (appointmentDate) {
                document.getElementById('date').value = appointmentDate;
            }

            // Pré-remplir les types de traitement à partir du rendez-vous
            if (plannedTreatments && plannedTreatments !== '[]') {
                fillTreatmentTypesFromAppointment(plannedTreatments);
            }
        });
    });

    // Gestion du multi-select pour les types de traitement - uniquement sur les checkboxes
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('option-check-input')) {
            const option = e.target.closest('.menu-option-item');
            const treatmentId = option.dataset.value;
            const treatmentName = option.dataset.name;
            const treatmentPrice = parseFloat(option.dataset.price) || 0;

            if (e.target.checked) {
                option.classList.add('option-selected');
                selectedTreatments.set(treatmentId, {
                    id: treatmentId,
                    name: treatmentName,
                    price: treatmentPrice
                });
            } else {
                option.classList.remove('option-selected');
                selectedTreatments.delete(treatmentId);
            }

            updateMultiSelectDisplay();
        }
    });

    // Gestion des événements de clic
    document.addEventListener('click', function(e) {
        // Gestion de la suppression des tags
        if (e.target.classList.contains('tag-close-btn')) {
            const treatmentId = e.target.dataset.value;
            selectedTreatments.delete(treatmentId);

            // Décocher l'option correspondante
            const option = document.querySelector(`#menuOptionsContainer .menu-option-item[data-value="${treatmentId}"]`);
            if (option) {
                const checkbox = option.querySelector('.option-check-input');
                if (checkbox) {
                    checkbox.checked = false;
                    option.classList.remove('option-selected');
                }
            }

            updateMultiSelectDisplay();
        }

        // Gestion de l'affichage/masquage du menu
        if (e.target.closest('#multiSelectDisplay')) {
            const menu = document.getElementById('multiSelectMenu');
            menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
        } else if (!e.target.closest('#multiSelectMenu')) {
            document.getElementById('multiSelectMenu').style.display = 'none';
        }
    });

    // Recherche dans les types de traitement
    document.getElementById('treatmentSearchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const options = document.querySelectorAll('#menuOptionsContainer .menu-option-item');

        options.forEach(option => {
            const text = option.textContent.toLowerCase();
            option.style.display = text.includes(searchTerm) ? 'block' : 'none';
        });
    });

    // Calculer et mettre le total initial dans le prix appliqué si des traitements sont déjà sélectionnés
    updateTotalCalculated();
});
</script>

@push('styles')
<link href="{{ asset('css/multiselect.css') }}" rel="stylesheet">
@endpush
