<div class="card">
    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
    <div class="card-body">
        <form action="{{ $appointment->exists ? route('appointments.update', $appointment) : route('appointments.store') }}" method="POST">
            @csrf
            @if($appointment->exists)
                @method('PUT')
            @endif

            <div class="mb-3 row">
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
                                            <div class="select-option"
                                                data-value="{{ $patient->id }}"
                                                data-display="{{ $patient->id }} - {{ $patient->full_name }}">
                                                {{ $patient->id }} - {{ $patient->full_name }}
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

            <div class="mb-3 row">
                <div class="col-md-4">
                    <label for="date" class="form-label">Date</label>
                    <input type="date" class="form-control @error('date') is-invalid @enderror"
                        id="date" name="date" value="{{ old('date', $appointment->date ?? date('Y-m-d')) }}" required>
                    @error('date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="start_time" class="form-label">Heure de début</label>
                    <input type="time" class="form-control @error('start_time') is-invalid @enderror"
                        id="start_time" name="start_time" value="{{ old('start_time', $appointment->start_time) }}" required>
                    @error('start_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="end_time" class="form-label">Heure de fin</label>
                    <input type="time" class="form-control @error('end_time') is-invalid @enderror"
                        id="end_time" name="end_time" value="{{ old('end_time', $appointment->end_time) }}" required>
                    @error('end_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

             <div class="col-md-12">
                <div class="form-group mb-3">
                    <label for="treatment_type_select" class="form-label fw-bold">Type de traitement</label>
                    <div class="multi-select-wrapper">
                        <div class="multi-select-display border rounded p-2 d-flex flex-wrap gap-1 align-items-center @error('planned_treatment_id') is-invalid @enderror" id="multiSelectDisplay">
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
                                        data-value="{{ $type->id }}" data-price="{{ $type->base_price }}">
                                        <input type="checkbox" class="option-check-input form-check-input"
                                            @if(isset($treatment) && $treatment->treatmentTypes && $treatment->treatmentTypes->contains('id', $type->id)) checked @endif>
                                        <span>{{ $type->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <input type="hidden" name="planned_treatment_id" id="selectedTreatmentTypes"
                            value="{{ old('planned_treatment_id', isset($treatment) && $treatment->treatmentTypes ? $treatment->treatmentTypes->pluck('id')->implode(',') : '') }}">
                        @error('planned_treatment_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="reason" class="form-label">Motif du rendez-vous</label>
                <textarea class="form-control @error('reason') is-invalid @enderror"
                    id="reason" name="reason" rows="3">{{ old('reason', $appointment->reason) }}</textarea>
                @error('reason')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">Notes additionnelles</label>
                <textarea class="form-control @error('notes') is-invalid @enderror"
                    id="notes" name="notes" rows="3">{{ old('notes', $appointment->notes) }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="status" class="form-label">Statut</label>
                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="En_attente" {{ old('status', $appointment->status) == 'En_attente' ? 'selected' : '' }}>En attente</option>
                    <option value="Confirme" {{ old('status', $appointment->status) == 'Confirme' ? 'selected' : '' }}>Confirmé</option>
                    <option value="Annule" {{ old('status', $appointment->status) == 'Annule' ? 'selected' : '' }}>Annulé</option>
                    <option value="Reporte" {{ old('status', $appointment->status) == 'Reporte' ? 'selected' : '' }}>Reporté</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="gap-2 d-flex justify-content-end">
                <button type="reset" class="btn btn-light">Réinitialiser</button>
                <button type="submit" class="btn btn-primary">
                    @if($appointment->exists)
                        <i class="bi bi-calendar-check me-1"></i> Mettre à jour
                    @else
                        <i class="bi bi-calendar-plus me-1"></i> Créer le rendez-vous
                    @endif
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/multiselect.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-calculate end time based on start time (add 30 minutes by default)
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');

    startTimeInput.addEventListener('change', function() {
        if (this.value) {
            const startTime = new Date(`2000-01-01T${this.value}`);
            startTime.setMinutes(startTime.getMinutes() + 30);
            const hours = String(startTime.getHours()).padStart(2, '0');
            const minutes = String(startTime.getMinutes()).padStart(2, '0');
            endTimeInput.value = `${hours}:${minutes}`;
        }
    });
});
</script>
@endpush
@push('styles')
<link href="{{ asset('css/multiselect.css') }}" rel="stylesheet">
@endpush
