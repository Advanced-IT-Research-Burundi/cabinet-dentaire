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
                        {{ isset($treatment) && $treatment->appointment ? $treatment->appointment->start_time->format('d/m/Y H:i') . ' - ' . $treatment->appointment->patient->full_name : 'Sélectionnez un rendez-vous' }}
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
                                     data-planned-treatment-id="{{ $appointment->planned_treatment_id ?? '' }}"
                                     data-planned-treatment-name="{{ $appointment->plannedTreatment->name ?? '' }}"
                                     data-planned-treatment-price="{{ $appointment->plannedTreatment->base_price ?? '' }}"
                                     data-date="{{ $appointment->date->format('Y-m-d') }}">
                                    {{ $appointment->date->format('d/m/Y') }}
                                    {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}
                                    #
                                    {{ $appointment->patient->full_name }}
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
                    {{-- <div class="select-dropdown">
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
                    </div> --}}
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
    // Fonction pour auto-compléter les champs lors de la sélection d'un rendez-vous
    const appointmentOptions = document.querySelectorAll('#appointment_options .select-option');

    appointmentOptions.forEach(option => {
        option.addEventListener('click', function() {
            const patientId = this.dataset.patientId;
            const patientName = this.dataset.patientName;
            const dentistId = this.dataset.dentistId;
            const dentistName = this.dataset.dentistName;
            const plannedTreatmentId = this.dataset.plannedTreatmentId;
            const plannedTreatmentName = this.dataset.plannedTreatmentName;
            const plannedTreatmentPrice = this.dataset.plannedTreatmentPrice;
            const appointmentDate = this.dataset.date;

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

            // Compléter le type de traitement si planifié
            if (plannedTreatmentId) {
                document.getElementById('treatment_type_id').value = plannedTreatmentId;
                document.getElementById('treatment_type_selected').textContent = plannedTreatmentName;

                // Compléter le prix appliqué avec le prix de base du traitement planifié
                if (plannedTreatmentPrice) {
                    document.getElementById('applied_price').value = plannedTreatmentPrice;
                }
            }

            // Compléter la date du traitement avec la date du rendez-vous
            if (appointmentDate) {
                document.getElementById('date').value = appointmentDate;
            }
        });
    });
});
</script>
