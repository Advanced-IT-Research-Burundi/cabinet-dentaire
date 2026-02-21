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
                        {{ isset($treatment) && $treatment->appointment ? \Carbon\Carbon::parse($treatment->appointment->start_time)->format('d/m/Y H:i') . ' - ' . $treatment->appointment->patient->id . ' - ' . $treatment->appointment->patient->full_name : 'Sélectionnez un rendez-vous' }}
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
                                    {{ $appointment->date->format('d/m/Y') }} - {{ $appointment->patient->id }} - {{ $appointment->patient->full_name }}
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

            <div class="card">
                <div class="card-header bg-light">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control" placeholder="Rechercher un type de traitement..." id="treatmentSearchInput">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <select class="form-select" id="treatmentTypeSelect">
                                <option value="">-- Ajouter un traitement --</option>
                                @foreach($treatmentTypes as $type)
                                    <option value="{{ $type->id }}" data-price="{{ $type->base_price }}" data-name="{{ $type->name }}">
                                        {{ $type->name }} - {{ number_format($type->base_price, 0, ',', ' ') }} FBU
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="treatmentsTable">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 40%;">Traitement</th>
                                    <th style="width: 15%;">Prix unitaire</th>
                                    <th style="width: 15%;">Quantité</th>
                                    <th style="width: 20%;">Sous-total</th>
                                    <th style="width: 10%;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="treatmentsTableBody">
                                {{-- Les lignes seront ajoutées dynamiquement --}}
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="3" class="text-end">Total:</th>
                                    <th id="totalAmount">0 FBU</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div id="noTreatmentsMessage" class="text-center text-muted py-4">
                        <i class="bi bi-inbox fs-1"></i>
                        <p class="mt-2">Aucun traitement ajouté. Sélectionnez un traitement dans la liste ci-dessus.</p>
                    </div>
                </div>
            </div>

            <input type="hidden" name="treatments_data" id="treatmentsData" value="">
            @error('treatments_data')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

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

        tbody.innerHTML = '';

        if (selectedTreatments.size === 0) {
            noMessage.style.display = 'block';
            document.querySelector('#treatmentsTable').style.display = 'none';
        } else {
            noMessage.style.display = 'none';
            document.querySelector('#treatmentsTable').style.display = 'table';

            selectedTreatments.forEach((treatment, id) => {
                const subtotal = (parseFloat(treatment.price) || 0) * (parseInt(treatment.quantity) || 1);
                const row = document.createElement('tr');
                row.dataset.id = id;
                row.innerHTML = `
                    <td>
                        <strong>${treatment.name}</strong>
                    </td>
                    <td>
                        <div class="input-group input-group-sm">
                            <input type="number" class="form-control treatment-price" value="${treatment.price}" min="0" step="100" data-id="${id}">
                            <span class="input-group-text">FBU</span>
                        </div>
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm treatment-quantity" value="${treatment.quantity}" min="1" data-id="${id}">
                    </td>
                    <td class="subtotal fw-bold">${formatNumber(subtotal)} FBU</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-treatment" data-id="${id}">
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

    // Gestion du select pour ajouter un traitement
    document.getElementById('treatmentTypeSelect').addEventListener('change', function(e) {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const id = selectedOption.value;
            const name = selectedOption.dataset.name;
            const price = parseFloat(selectedOption.dataset.price) || 0;
            addTreatment(id, name, price);
            this.value = '';
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

    // Recherche dans les types de traitement
    document.getElementById('treatmentSearchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const select = document.getElementById('treatmentTypeSelect');

        Array.from(select.options).forEach((option, index) => {
            if (index === 0) return;
            const text = option.textContent.toLowerCase();
            option.style.display = text.includes(searchTerm) ? '' : 'none';
        });
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
        });
    });

    // Initialiser l'affichage
    renderTreatmentsTable();
});
</script>

@push('styles')
<link href="{{ asset('css/multiselect.css') }}" rel="stylesheet">
@endpush
