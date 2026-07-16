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
                        {{ isset($treatment) && $treatment->appointment ? \Carbon\Carbon::parse($treatment?->appointment?->start_time)->format('d/m/Y H:i') . ' - ' . $treatment?->appointment?->patient?->id . ' - ' . $treatment?->appointment?->patient?->full_name : 'Sélectionnez un rendez-vous' }}
                    </div>
                    <div class="select-dropdown">
                        <div class="select-search">
                            <input type="text" class="form-control" placeholder="Rechercher un rendez-vous..." id="appointment_search" >
                        </div>
                        <div class="select-options" id="appointment_options">
                            @foreach($appointments as $appointment)
                                <div class="select-option"
                                     data-value="{{ $appointment->id }}"
                                     data-patient-id="{{ $appointment?->patient_id }}"
                                     data-patient-name="{{ $appointment?->patient?->full_name }}"
                                     data-dentist-id="{{ $appointment?->dentist_id }}"
                                     data-dentist-name="{{ $appointment?->dentist?->user?->full_name }}"
                                     data-date="{{ $appointment?->date?->format('Y-m-d') }}"
                                     data-planned-treatments="{{ $appointment?->plannedTreatments?->count() > 0 ? $appointment?->plannedTreatments?->map(function($pt) { return ['id' => $pt->id, 'name' => $pt->name, 'price' => $pt->base_price]; })->toJson() : ($appointment?->plannedTreatment ? json_encode([['id' => $appointment?->plannedTreatment?->id, 'name' => $appointment?->plannedTreatment?->name, 'price' => $appointment?->plannedTreatment?->base_price]]) : '[]') }}">
                                    {{ $appointment?->date?->format('d/m/Y') }} - {{ $appointment?->patient?->id }} - {{ $appointment?->patient?->full_name }}
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
                        {{ isset($treatment) && $treatment->patient ? $treatment->patient->id . ' - ' . $treatment->patient->full_name : 'Sélectionnez un patient' }}
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
                                {{ $treatment->dentist ? ($treatment?->dentist?->user?->full_name ?? "#{$treatment?->dentist_id}") : 'Sélectionnez un dentiste' }}
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
            <label for="date" class="form-label fw-bold">
                <i class="bi bi-calendar-date-fill me-2"></i>Date du traitement <span class="text-danger">*</span>
            </label>
            <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', isset($treatment) ? $treatment->date->format('Y-m-d') : '') }}" required>
            @error('date')
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
            <label class="form-label fw-bold">
                <i class="bi bi-clipboard2-pulse-fill me-2"></i>Types de traitement <span class="text-danger">*</span>
            </label>

            {{-- Sélecteur de traitement avec recherche --}}
            <div class="treatment-selector mb-3">
                <div class="position-relative">
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-primary text-white">
                            <i class="bi bi-plus-lg"></i>
                        </span>
                        <input type="text"
                               class="form-control form-control-lg"
                               placeholder="Tapez pour rechercher un traitement..."
                               id="treatmentSearchInput"
                               autocomplete="off">
                    </div>
                    <div id="treatmentDropdown" class="treatment-dropdown-menu">
                        <div class="treatment-options-list" id="treatmentOptionsList">
                            @foreach($treatmentTypes as $type)
                                <div class="treatment-option-item"
                                     data-id="{{ $type->id }}"
                                     data-price="{{ $type->base_price }}"
                                     data-name="{{ $type->name }}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="bi bi-clipboard2-pulse text-primary me-2"></i>
                                            <span class="treatment-name">{{ $type->name }}</span>
                                        </div>
                                        <span class="badge bg-success rounded-pill">{{ number_format($type->base_price, 0, ',', ' ') }} FBU</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="no-results-message" id="noResultsMessage">
                            <i class="bi bi-emoji-frown fs-3 mb-2"></i>
                            <p class="mb-0">Aucun traitement trouvé</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table des traitements sélectionnés --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0"><i class="bi bi-list-check me-2"></i>Traitements sélectionnés</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="treatmentsTable">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 35%;">Traitement</th>
                                <th style="width: 20%;">Prix unitaire (FBU)</th>
                                <th style="width: 15%;">Quantité</th>
                                <th style="width: 20%;">Sous-total</th>
                                <th style="width: 10%;" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="treatmentsTableBody">
                        </tbody>
                        <tfoot class="table-light">
                            <tr class="fw-bold">
                                <td colspan="3" class="text-end">Total Général:</td>
                                <td id="totalAmount" class="text-success fs-5">0 FBU</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div id="noTreatmentsMessage" class="text-center py-5">
                    <i class="bi bi-cart3 fs-1 text-muted"></i>
                    <p class="text-muted mt-2 mb-0">Aucun traitement ajouté</p>
                    <small class="text-muted">Utilisez le champ de recherche ci-dessus pour ajouter des traitements</small>
                </div>
            </div>

            <input type="hidden" name="treatments_data" id="treatmentsData" value="">
            @error('treatments_data')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<style>
.treatment-selector .input-group-lg .form-control {
    font-size: 1rem;
    padding: 0.75rem 1rem;
}
.treatment-dropdown-menu {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: #fff;
    border: 1px solid #dee2e6;
    border-top: none;
    border-radius: 0 0 0.5rem 0.5rem;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    z-index: 1050;
    max-height: 350px;
    overflow-y: auto;
}
.treatment-dropdown-menu.show {
    display: block;
}
.treatment-option-item {
    padding: 0.75rem 1rem;
    cursor: pointer;
    border-bottom: 1px solid #f0f0f0;
    transition: all 0.15s ease;
}
.treatment-option-item:last-child {
    border-bottom: none;
}
.treatment-option-item:hover {
    background-color: #e7f1ff;
}
.treatment-option-item.highlighted {
    background-color: #0d6efd;
    color: #fff;
}
.treatment-option-item.highlighted .badge {
    background-color: #fff !important;
    color: #198754 !important;
}
.treatment-option-item.highlighted .text-primary {
    color: #fff !important;
}
.no-results-message {
    display: none;
    text-align: center;
    padding: 2rem;
    color: #6c757d;
}
.no-results-message.show {
    display: block;
}
#treatmentsTable {
    display: none;
}
#treatmentsTable.has-items {
    display: table;
}
</style>

<div class="mb-4 row">
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="applied_price" class="form-label fw-bold">
                <i class="bi bi-currency-exchange me-2"></i>Prix total appliqué
            </label>
            <div class="input-group">
                <input type="number" step="0.01" name="applied_price" id="applied_price" class="form-control @error('applied_price') is-invalid @enderror" value="{{ old('applied_price', isset($treatment) ? $treatment->applied_price : '') }}" readonly>
                <span class="input-group-text">FBU</span>
            </div>
            @error('applied_price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<!-- <div class="mb-4 row">
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

-->

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
    let selectedTreatments = new Map();

    // Initialiser les traitements déjà sélectionnés (en cas de modification)
    @if(isset($treatment) && $treatment->treatmentTypes && $treatment->treatmentTypes->count() > 0)
        @foreach($treatment->treatmentTypes as $selectedType)
            @php
                $pivot = \App\Models\TreatementTreatmentType::where('treatment_id', $treatment->id)
                    ->where('treatment_type_id', $selectedType->id)->first();
                $qty = $pivot ? $pivot->quantity : 1;
                $price = $pivot ? $pivot->price : $selectedType->base_price;
            @endphp
            selectedTreatments.set('{{ $selectedType->id }}', {
                id: '{{ $selectedType->id }}',
                name: '{{ $selectedType->name }}',
                basePrice: {{ $selectedType->base_price }},
                price: {{ $price }},
                quantity: {{ $qty }}
            });
        @endforeach
        renderTreatmentsTable();
    @endif

    function formatNumber(num) {
        return new Intl.NumberFormat('fr-FR').format(num);
    }

    function updateTotal() {
        let total = 0;
        selectedTreatments.forEach(treatment => {
            total += (parseFloat(treatment.price) || 0) * (parseInt(treatment.quantity) || 1);
        });

        document.getElementById('totalAmount').textContent = formatNumber(total) + ' FBU';
        document.getElementById('applied_price').value = total;

        // Mettre à jour le champ hidden avec les données JSON
        const treatmentsArray = Array.from(selectedTreatments.values());
        document.getElementById('treatmentsData').value = JSON.stringify(treatmentsArray);
    }

    function renderTreatmentsTable() {
        const tbody = document.getElementById('treatmentsTableBody');
        const noMessage = document.getElementById('noTreatmentsMessage');
        const table = document.getElementById('treatmentsTable');

        tbody.innerHTML = '';

        if (selectedTreatments.size === 0) {
            noMessage.style.display = 'block';
            table.classList.remove('has-items');
        } else {
            noMessage.style.display = 'none';
            table.classList.add('has-items');

            selectedTreatments.forEach((treatment, id) => {
                const subtotal = (parseFloat(treatment.price) || 0) * (parseInt(treatment.quantity) || 1);
                const row = document.createElement('tr');
                row.dataset.id = id;
                row.innerHTML = `
                    <td>
                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                        <strong>${treatment.name}</strong>
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm treatment-price" value="${treatment.price}" min="0" step="100" data-id="${id}">
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm treatment-quantity text-center" value="${treatment.quantity}" min="1" data-id="${id}" style="width: 80px;">
                    </td>
                    <td class="subtotal fw-bold text-primary">${formatNumber(subtotal)} FBU</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger remove-treatment" data-id="${id}" title="Supprimer">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        updateTotal();
    }

    function addTreatment(id, name, price) {
        if (selectedTreatments.has(id)) {
            // Si déjà présent, augmenter la quantité
            const existing = selectedTreatments.get(id);
            existing.quantity += 1;
            selectedTreatments.set(id, existing);
        } else {
            selectedTreatments.set(id, {
                id: id,
                name: name,
                basePrice: price,
                price: price,
                quantity: 1
            });
        }
        renderTreatmentsTable();
    }

    function removeTreatment(id) {
        selectedTreatments.delete(id);
        renderTreatmentsTable();
    }

    // Fonction pour pré-remplir les types de traitement à partir du rendez-vous
    function fillTreatmentTypesFromAppointment(plannedTreatments) {
        try {
            const treatments = JSON.parse(plannedTreatments);
            if (treatments && treatments.length > 0) {
                selectedTreatments.clear();

                treatments.forEach(treatment => {
                    selectedTreatments.set(treatment.id.toString(), {
                        id: treatment.id.toString(),
                        name: treatment.name,
                        basePrice: parseFloat(treatment.price) || 0,
                        price: parseFloat(treatment.price) || 0,
                        quantity: 1
                    });
                });

                renderTreatmentsTable();
            }
        } catch (e) {
            console.log('Erreur lors du parsing des traitements planifiés:', e);
        }
    }

    // Gestion du dropdown de recherche de traitement
    const searchInput = document.getElementById('treatmentSearchInput');
    const dropdown = document.getElementById('treatmentDropdown');
    const optionsList = document.getElementById('treatmentOptionsList');
    const treatmentOptions = optionsList.querySelectorAll('.treatment-option-item');
    const noResultsMsg = document.getElementById('noResultsMessage');
    let highlightedIndex = -1;

    // Afficher le dropdown
    function showDropdown() {
        dropdown.classList.add('show');
        filterOptions(searchInput.value);
    }

    // Masquer le dropdown
    function hideDropdown() {
        dropdown.classList.remove('show');
        highlightedIndex = -1;
        removeAllHighlights();
    }

    // Supprimer tous les highlights
    function removeAllHighlights() {
        treatmentOptions.forEach(opt => opt.classList.remove('highlighted'));
    }

    // Filtrer les options
    function filterOptions(searchTerm) {
        searchTerm = searchTerm.toLowerCase().trim();
        let hasVisibleOptions = false;
        let visibleOptions = [];

        treatmentOptions.forEach(option => {
            const name = option.dataset.name.toLowerCase();
            const matches = searchTerm === '' || name.includes(searchTerm);
            option.style.display = matches ? 'block' : 'none';
            if (matches) {
                hasVisibleOptions = true;
                visibleOptions.push(option);
            }
        });

        if (hasVisibleOptions) {
            noResultsMsg.classList.remove('show');
            optionsList.style.display = 'block';
        } else {
            noResultsMsg.classList.add('show');
            optionsList.style.display = 'none';
        }

        highlightedIndex = -1;
        removeAllHighlights();
    }

    // Événements du champ de recherche
    searchInput.addEventListener('focus', showDropdown);

    searchInput.addEventListener('input', function(e) {
        showDropdown();
        filterOptions(e.target.value);
    });

    // Navigation au clavier
    searchInput.addEventListener('keydown', function(e) {
        const visibleOptions = Array.from(treatmentOptions).filter(opt => opt.style.display !== 'none');

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            highlightedIndex = Math.min(highlightedIndex + 1, visibleOptions.length - 1);
            updateHighlight(visibleOptions);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            highlightedIndex = Math.max(highlightedIndex - 1, 0);
            updateHighlight(visibleOptions);
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (highlightedIndex >= 0 && visibleOptions[highlightedIndex]) {
                selectOption(visibleOptions[highlightedIndex]);
            }
        } else if (e.key === 'Escape') {
            hideDropdown();
            searchInput.blur();
        }
    });

    function updateHighlight(visibleOptions) {
        removeAllHighlights();
        if (highlightedIndex >= 0 && visibleOptions[highlightedIndex]) {
            visibleOptions[highlightedIndex].classList.add('highlighted');
            visibleOptions[highlightedIndex].scrollIntoView({ block: 'nearest' });
        }
    }

    // Sélection d'une option
    function selectOption(option) {
        const id = option.dataset.id;
        const name = option.dataset.name;
        const price = parseFloat(option.dataset.price) || 0;

        addTreatment(id, name, price);

        searchInput.value = '';
        hideDropdown();
        searchInput.focus();
    }

    // Clic sur une option
    treatmentOptions.forEach(option => {
        option.addEventListener('click', function(e) {
            e.stopPropagation();
            selectOption(this);
        });
    });

    // Fermer le dropdown en cliquant ailleurs
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.treatment-selector')) {
            hideDropdown();
        }
    });

    // Gestion de la modification de prix et quantité
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('treatment-price')) {
            const id = e.target.dataset.id;
            const treatment = selectedTreatments.get(id);
            if (treatment) {
                treatment.price = parseFloat(e.target.value) || 0;
                selectedTreatments.set(id, treatment);
                const row = e.target.closest('tr');
                const subtotal = treatment.price * treatment.quantity;
                row.querySelector('.subtotal').textContent = formatNumber(subtotal) + ' FBU';
                updateTotal();
            }
        }

        if (e.target.classList.contains('treatment-quantity')) {
            const id = e.target.dataset.id;
            const treatment = selectedTreatments.get(id);
            if (treatment) {
                treatment.quantity = parseInt(e.target.value) || 1;
                selectedTreatments.set(id, treatment);
                const row = e.target.closest('tr');
                const subtotal = treatment.price * treatment.quantity;
                row.querySelector('.subtotal').textContent = formatNumber(subtotal) + ' FBU';
                updateTotal();
            }
        }
    });

    // Suppression d'un traitement
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-treatment') || e.target.closest('.remove-treatment')) {
            const btn = e.target.classList.contains('remove-treatment') ? e.target : e.target.closest('.remove-treatment');
            const id = btn.dataset.id;
            removeTreatment(id);
        }
    });

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

            if (patientId) {
                document.getElementById('patient_id').value = patientId;
                document.getElementById('patient_selected').textContent = `${patientId} - ${patientName}`;
            }

            if (dentistId) {
                document.getElementById('dentist_id').value = dentistId;
                document.getElementById('dentist_selected').textContent = `${dentistId} - ${dentistName}`;
            }

            if (appointmentDate) {
                document.getElementById('date').value = appointmentDate;
            }

            if (plannedTreatments && plannedTreatments !== '[]') {
                fillTreatmentTypesFromAppointment(plannedTreatments);
            }

            if (patientId) {
                loadPatientHistory(patientId);
            }
        });
    });

    // Chargement de l'historique des traitements du patient (panneau de droite)
    function loadPatientHistory(patientId) {
        const emptyEl = document.getElementById('patientHistoryEmpty');
        const loadingEl = document.getElementById('patientHistoryLoading');
        const listEl = document.getElementById('patientHistoryList');

        if (!emptyEl || !loadingEl || !listEl) {
            return;
        }

        emptyEl.classList.add('d-none');
        listEl.classList.add('d-none');
        loadingEl.classList.remove('d-none');

        fetch(`{{ url('treatments/patient-history') }}/${patientId}`, {
            headers: { 'Accept': 'application/json' }
        })
            .then(response => response.json())
            .then(data => {
                loadingEl.classList.add('d-none');
                renderPatientHistory(data.treatments || []);
            })
            .catch(() => {
                loadingEl.classList.add('d-none');
                emptyEl.classList.remove('d-none');
                emptyEl.querySelector('p').textContent = "Impossible de charger l'historique du patient.";
            });
    }

    function escapeHtml(value) {
        const div = document.createElement('div');
        div.textContent = value ?? '';
        return div.innerHTML;
    }

    function renderPatientHistory(treatments) {
        const emptyEl = document.getElementById('patientHistoryEmpty');
        const listEl = document.getElementById('patientHistoryList');

        if (!treatments.length) {
            listEl.classList.add('d-none');
            emptyEl.classList.remove('d-none');
            emptyEl.querySelector('p').textContent = 'Aucun traitement antérieur pour ce patient.';
            return;
        }

        emptyEl.classList.add('d-none');
        listEl.classList.remove('d-none');

        listEl.innerHTML = treatments.map(t => `
            <div class="border rounded p-3 mb-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="fw-bold"><i class="bi bi-calendar-date me-1"></i>${escapeHtml(t.date) || 'N/A'}</span>
                    <span class="badge bg-secondary">${escapeHtml(t.status)}</span>
                </div>
                <div class="mb-1">
                    <i class="bi bi-person-badge-fill text-primary me-1"></i>
                    <strong>Dentiste :</strong> ${escapeHtml(t.dentist_name) || 'Non spécifié'}
                </div>
                ${t.treatment_types ? `
                <div class="mb-1">
                    <i class="bi bi-clipboard2-pulse text-primary me-1"></i>
                    <strong>Traitements :</strong> ${escapeHtml(t.treatment_types)}
                </div>` : ''}
                ${t.description ? `
                <div class="mb-1">
                    <i class="bi bi-card-text text-primary me-1"></i>
                    <strong>Description :</strong> ${escapeHtml(t.description)}
                </div>` : ''}
                ${t.medical_notes ? `
                <div class="mb-0">
                    <i class="bi bi-journal-medical text-primary me-1"></i>
                    <strong>Notes médicales :</strong> ${escapeHtml(t.medical_notes)}
                </div>` : ''}
            </div>
        `).join('');
    }

    @if(isset($treatment) && $treatment->patient_id)
        loadPatientHistory({{ $treatment->patient_id }});
    @endif

    // Initialiser l'affichage
    renderTreatmentsTable();
});
</script>

@push('styles')
<link href="{{ asset('css/multiselect.css') }}" rel="stylesheet">
@endpush
