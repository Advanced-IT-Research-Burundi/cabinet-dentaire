@extends('layouts.app')

@section('title', 'Nouveau Rendez-vous')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Nouveau Rendez-vous</h1>
                <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Retour
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('appointments.store') }}" method="POST">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="patient_id" class="form-label">Patient</label>
                                <select name="patient_id" id="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                                    <option value="">Sélectionner un patient</option>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                            {{ $patient->user->prenom }} {{ $patient->user->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('patient_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="dentist_id" class="form-label">Dentiste</label>
                                <select name="dentist_id" id="dentist_id" class="form-select @error('dentist_id') is-invalid @enderror" required>
                                    <option value="">Sélectionner un dentiste</option>
                                    @foreach($dentists as $dentist)
                                        <option value="{{ $dentist->id }}" {{ old('dentist_id') == $dentist->id ? 'selected' : '' }}>
                                            Dr. {{ $dentist->user->prenom }} {{ $dentist->user->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('dentist_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" class="form-control @error('date') is-invalid @enderror" 
                                    id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="start_time" class="form-label">Heure de début</label>
                                <input type="time" class="form-control @error('start_time') is-invalid @enderror" 
                                    id="start_time" name="start_time" value="{{ old('start_time') }}" required>
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="end_time" class="form-label">Heure de fin</label>
                                <input type="time" class="form-control @error('end_time') is-invalid @enderror" 
                                    id="end_time" name="end_time" value="{{ old('end_time') }}" required>
                                @error('end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="planned_treatment_id" class="form-label">Type de traitement</label>
                            <select name="planned_treatment_id" id="planned_treatment_id" class="form-select @error('planned_treatment_id') is-invalid @enderror" required>
                                <option value="">Sélectionner un traitement</option>
                                @foreach($treatmentTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('planned_treatment_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }} - {{ number_format($type->cost, 2) }} FBU
                                    </option>
                                @endforeach
                            </select>
                            @error('planned_treatment_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="reason" class="form-label">Motif du rendez-vous</label>
                            <textarea class="form-control @error('reason') is-invalid @enderror" 
                                id="reason" name="reason" rows="3">{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes additionnelles</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="status" class="form-label">Statut</label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="En_attente" {{ old('status') == 'En_attente' ? 'selected' : '' }}>En attente</option>
                                <option value="Confirme" {{ old('status') == 'Confirme' ? 'selected' : '' }}>Confirmé</option>
                                <option value="Annule" {{ old('status') == 'Annule' ? 'selected' : '' }}>Annulé</option>
                                <option value="Reporte" {{ old('status') == 'Reporte' ? 'selected' : '' }}>Reporté</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="reset" class="btn btn-light">Réinitialiser</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-calendar-plus me-1"></i> Créer le rendez-vous
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Instructions</h5>
                    <p class="card-text">Veuillez remplir tous les champs obligatoires (*) pour créer un nouveau rendez-vous.</p>
                    <ul class="list-unstyled mb-0">
                        <li><i class="bi bi-dot"></i> Sélectionnez un patient et un dentiste</li>
                        <li><i class="bi bi-dot"></i> Choisissez une date et une plage horaire</li>
                        <li><i class="bi bi-dot"></i> Indiquez le type de traitement prévu</li>
                        <li><i class="bi bi-dot"></i> Ajoutez un motif de consultation</li>
                        <li><i class="bi bi-dot"></i> Définissez le statut initial du rendez-vous</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
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
@endsection
