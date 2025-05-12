@csrf
@if(isset($treatment))
    @method('PUT')
@endif

<div class="mb-4 row">
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="patient_select" class="form-label fw-bold">Patient <span class="text-danger">*</span></label>
            <div class="select-container">
                <div class="custom-select @error('patient_id') is-invalid @enderror">
                    <div class="select-selected" id="patient_selected">
                        {{ isset($treatment) && $treatment->patient ? $treatment->patient->full_name : 'Sélectionnez un patient' }}
                    </div>
                    <div class="select-dropdown">
                        <div class="select-search">
                            <input type="text" class="form-control" placeholder="Rechercher un patient..." id="patient_search">
                        </div>
                        <div class="select-options" id="patient_options">
                            @foreach($patients as $patient)
                                <div class="select-option" data-value="{{ $patient->id }}" data-display="{{ $patient->id }} - {{ isset($treatment) ? $patient->full_name : $patient->first_name . ' ' . $patient->last_name }}">
                                    {{ $patient->id }} - {{ isset($treatment) ? $patient->full_name : $patient->first_name . ' ' . $patient->last_name }}
                                </div>
                            @endforeach
                        </div>
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
            <label for="dentist_select" class="form-label fw-bold">Dentiste <span class="text-danger">*</span></label>
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
            <label for="treatment_type_select" class="form-label fw-bold">Type de traitement <span class="text-danger">*</span></label>
            <div class="select-container">
                <div class="custom-select @error('treatment_type_id') is-invalid @enderror">
                    <div class="select-selected" id="treatment_type_selected">
                        {{ isset($treatment) && $treatment->treatmentType ? $treatment->treatmentType->name : 'Sélectionnez un type' }}
                    </div>
                    <div class="select-dropdown">
                        <div class="select-search">
                            <input type="text" class="form-control" placeholder="Rechercher un type..." id="treatment_type_search">
                        </div>
                        <div class="select-options" id="treatment_type_options">
                            @foreach($treatmentTypes as $type)
                                <div class="select-option" data-value="{{ $type->id }}" data-price="{{ $type->base_price }}">
                                    {{ $type->name }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <input type="hidden" name="treatment_type_id" id="treatment_type_id" value="{{ old('treatment_type_id', isset($treatment) ? $treatment->treatment_type_id : '') }}">
                @error('treatment_type_id')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="appointment_select" class="form-label fw-bold">Rendez-vous <span class="text-danger">*</span></label>
            <div class="select-container">
                <div class="custom-select @error('appointment_id') is-invalid @enderror">
                    <div class="select-selected" id="appointment_selected">
                        {{ isset($treatment) && $treatment->appointment ? $treatment->appointment->date->format('d/m/Y H:i') . ' - ' . $treatment->appointment->patient->full_name : 'Sélectionnez un rendez-vous' }}
                    </div>
                    <div class="select-dropdown">
                        <div class="select-search">
                            <input type="text" class="form-control" placeholder="Rechercher un rendez-vous..." id="appointment_search">
                        </div>
                        <div class="select-options" id="appointment_options">
                            @foreach($appointments as $appointment)
                                <div class="select-option" data-value="{{ $appointment->id }}">
                                    {{ $appointment->date->format('d/m/Y H:i') }} - {{ $appointment->patient->full_name }}
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
            <label for="date" class="form-label fw-bold">Date du traitement <span class="text-danger">*</span></label>
            <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', isset($treatment) ? $treatment->date->format('Y-m-d') : '') }}" required>
            @error('date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="applied_price" class="form-label fw-bold">Prix appliqué</label>
            <div class="input-group">
                <input type="number" step="0.01" name="applied_price" id="applied_price" class="form-control @error('applied_price') is-invalid @enderror" value="{{ old('applied_price', isset($treatment) ? $treatment->applied_price : '') }}">
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
            <label for="description" class="form-label fw-bold">Description</label>
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
            <label for="medical_notes" class="form-label fw-bold">Notes médicales</label>
            <textarea name="medical_notes" id="medical_notes" rows="3" class="form-control @error('medical_notes') is-invalid @enderror">{{ old('medical_notes', isset($treatment) ? $treatment->medical_notes : '') }}</textarea>
            @error('medical_notes')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="mb-4 row">
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="status" class="form-label fw-bold">Statut <span class="text-danger">*</span></label>
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

<style>
.custom-select {
    position: relative;
    width: 100%;
    cursor: pointer;
    user-select: none;
}

.select-selected {
    border: 1px solid #ced4da;
    padding: 0.375rem 0.75rem;
    border-radius: 0.25rem;
    background-color: #fff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 38px;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.select-selected:after {
    content: '\25BC';
    margin-left: 10px;
    font-size: 12px;
    transition: transform 0.3s;
}

.custom-select.active .select-selected:after {
    transform: rotate(180deg);
}

.custom-select.active .select-selected {
    border-color: #86b7fe;
    outline: 0;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.select-dropdown {
    display: none;
    position: absolute;
    width: 100%;
    background-color: #fff;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    z-index: 100;
    border-radius: 0.25rem;
    border: 1px solid #ced4da;
    margin-top: 2px;
    max-height: 300px;
    overflow-y: auto;
}

.custom-select.active .select-dropdown {
    display: block;
}

.select-search {
    padding: 8px;
    position: sticky;
    top: 0;
    background-color: #fff;
    z-index: 1;
    border-bottom: 1px solid #e9ecef;
}

.select-search input {
    width: 100%;
}

.select-options {
    max-height: 240px;
    overflow-y: auto;
}

.select-option {
    padding: 8px 12px;
    cursor: pointer;
    transition: background-color 0.2s;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.select-option:hover {
    background-color: rgba(13, 110, 253, 0.1);
}

.select-option.selected {
    background-color: rgba(13, 110, 253, 0.25);
    font-weight: 500;
}

.is-invalid .select-selected {
    border-color: #dc3545;
}

.form-label {
    margin-bottom: 0.5rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all custom selects
    initCustomSelects();

    // Setup treatment type change to update price
    setupTreatmentTypePrice();
});

function initCustomSelects() {
    const customSelects = document.querySelectorAll('.custom-select');

    customSelects.forEach(select => {
        const selectedDiv = select.querySelector('.select-selected');
        const hiddenInput = select.nextElementSibling;
        const options = select.querySelectorAll('.select-option');
        const searchInput = select.querySelector('input[type="text"]');

        // Set initial selection if value exists
        if (hiddenInput.value) {
            options.forEach(option => {
                if (option.dataset.value === hiddenInput.value) {
                    option.classList.add('selected');
                    selectedDiv.textContent = option.textContent.trim();
                }
            });
        }

        // Toggle dropdown on click
        selectedDiv.addEventListener('click', function() {
            closeAllCustomSelects();
            select.classList.toggle('active');
            if (select.classList.contains('active') && searchInput) {
                setTimeout(() => searchInput.focus(), 100);
            }
        });

        // Handle search input
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const filter = this.value.toLowerCase();
                const options = select.querySelectorAll('.select-option');

                options.forEach(option => {
                    const text = option.textContent.toLowerCase();
                    option.style.display = text.includes(filter) ? '' : 'none';
                });
            });

            // Prevent dropdown close when clicking search
            searchInput.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }

        // Handle option selection
        options.forEach(option => {
            option.addEventListener('click', function() {
                const value = this.dataset.value;
                hiddenInput.value = value;

                // Remove previous selection
                options.forEach(opt => opt.classList.remove('selected'));
                this.classList.add('selected');

                // Update selected display
                selectedDiv.textContent = this.textContent.trim();

                // Close dropdown
                select.classList.remove('active');

                // Trigger change event on hidden input
                const event = new Event('change', { bubbles: true });
                hiddenInput.dispatchEvent(event);
            });
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.custom-select')) {
            closeAllCustomSelects();
        }
    });
}

function closeAllCustomSelects() {
    const activeSelects = document.querySelectorAll('.custom-select.active');
    activeSelects.forEach(select => select.classList.remove('active'));
}

function setupTreatmentTypePrice() {
    const treatmentTypeInput = document.getElementById('treatment_type_id');
    const priceInput = document.getElementById('applied_price');

    treatmentTypeInput.addEventListener('change', function() {
        const options = document.querySelectorAll('#treatment_type_options .select-option');

        options.forEach(option => {
            if (option.dataset.value === this.value && option.dataset.price) {
                priceInput.value = option.dataset.price;
            }
        });
    });
}
</script>
