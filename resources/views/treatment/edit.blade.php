@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mb-4 row">
        <div class="col">
            <h1>Modifier le traitement</h1>
        </div>
        <div class="col text-end">
            <a href="{{ route('treatments.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Retour
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('treatments.update', $treatment) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3 row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="patient_id" class="form-label">Patient</label>
                            <select name="patient_id" id="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                                <option value="">Sélectionnez un patient</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ (old('patient_id', $treatment->patient_id) == $patient->id) ? 'selected' : '' }}>
                                        {{ $patient->id }} -
                                        {{ $patient->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('patient_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="dentist_id" class="form-label">Dentiste</label>
                            <select name="dentist_id" id="dentist_id" class="form-select @error('dentist_id') is-invalid @enderror" required>
                                <option value="">Sélectionnez un dentiste</option>
                                @foreach($dentists as $dentist)
                                    <option value="{{ $dentist->id }}" {{ (old('dentist_id', $treatment->dentist_id) == $dentist->id) ? 'selected' : '' }}>
                                        {{ $dentist->id }} -
                                        {{ $dentist->user->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('dentist_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="treatment_type_id" class="form-label">Type de traitement</label>
                            <select name="treatment_type_id" id="treatment_type_id" class="form-select @error('treatment_type_id') is-invalid @enderror" required>
                                <option value="">Sélectionnez un type</option>
                                @foreach($treatmentTypes as $type)
                                    <option value="{{ $type->id }}" {{ (old('treatment_type_id', $treatment->treatment_type_id) == $type->id) ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('treatment_type_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="appointment_id" class="form-label">Rendez-vous</label>
                            <select name="appointment_id" id="appointment_id" class="form-select @error('appointment_id') is-invalid @enderror" required>
                                <option value="">Sélectionnez un rendez-vous</option>
                                @foreach($appointments as $appointment)
                                    <option value="{{ $appointment->id }}" {{ (old('appointment_id', $treatment->appointment_id) == $appointment->id) ? 'selected' : '' }}>
                                        {{ $appointment->date->format('d/m/Y H:i') }} - {{ $appointment->patient->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('appointment_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date" class="form-label">Date du traitement</label>
                            <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', $treatment->date->format('Y-m-d')) }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="applied_price" class="form-label">Prix appliqué</label>
                            <div class="input-group">
                                <input type="number" step="0.01" name="applied_price" id="applied_price" class="form-control @error('applied_price') is-invalid @enderror" value="{{ old('applied_price', $treatment->applied_price) }}">
                                <span class="input-group-text">€</span>
                            </div>
                            @error('applied_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $treatment->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="medical_notes" class="form-label">Notes médicales</label>
                            <textarea name="medical_notes" id="medical_notes" rows="3" class="form-control @error('medical_notes') is-invalid @enderror">{{ old('medical_notes', $treatment->medical_notes) }}</textarea>
                            @error('medical_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status" class="form-label">Statut</label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="Planifie" {{ old('status', $treatment->status) == 'Planifie' ? 'selected' : '' }}>Planifié</option>
                                <option value="En_cours" {{ old('status', $treatment->status) == 'En_cours' ? 'selected' : '' }}>En cours</option>
                                <option value="Termine" {{ old('status', $treatment->status) == 'Termine' ? 'selected' : '' }}>Terminé</option>
                                <option value="Annule" {{ old('status', $treatment->status) == 'Annule' ? 'selected' : '' }}>Annulé</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Mettre à jour
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
