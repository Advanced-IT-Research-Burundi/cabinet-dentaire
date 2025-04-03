{{--
    @extends('layouts.app')

    @section('content')
        <form action="{{ route('patient.update', $patient->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="insurance_id" class="form-label">Assurance</label>
                    <select id="insurance_id" name="insurance_id" class="form-select @error('insurance_id') is-invalid @enderror">
                        <option value="" disabled>Choisir une assurance</option>
                        @foreach($assurances as $assurance)
                            <option value="{{ $assurance->id }}" {{ old('insurance_id', $patient->insurance_id ?? '') == $assurance->id ? 'selected' : '' }}>
                                {{ $assurance->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('insurance_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
    @endsection
--}}
