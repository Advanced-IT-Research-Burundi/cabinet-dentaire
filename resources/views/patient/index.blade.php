@extends('layouts.app')

@section('title', 'Liste des Patients')

@section('content')
<div class="px-4 container-fluid">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h1 class="mb-0 text-gray-800 h3">Liste des Patients</h1>
        <a href="{{ route('patients.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus-fill"></i> Nouveau Patient
        </a>
    </div>

    <!-- Search and Filter Card -->
    <div class="mb-4 card">
        <div class="card-body">
            <form action="{{ route('patients.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                               placeholder="Rechercher par nom, téléphone, email...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="gender" class="form-select">
                        <option value="">Tous les genres</option>
                        <option value="M" {{ request('gender') == 'M' ? 'selected' : '' }}>Homme</option>
                        <option value="F" {{ request('gender') == 'F' ? 'selected' : '' }}>Femme</option>
                        <option value="Autre" {{ request('gender') == 'Autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Patients Table Card -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Date de naissance</th>
                            <th>Genre</th>
                            <th>Téléphone</th>
                            <th>Email</th>
                            <th>Assurance</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patients as $patient)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if ($patient->patient_type == 'physique')
                                                <div class="avatar-sm bg-primary-subtle text-primary rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                    <div class="p-2">
                                                        {{ strtoupper(substr($patient->first_name, 0, 1)) }}{{ strtoupper(substr($patient->last_name, 0, 1)) }}
                                                    </div>
                                            </div>
                                            <div>
                                                <div class="fw-medium">{{ $patient->first_name }} {{ $patient->last_name }}</div>
                                                <small class="text-muted">ID: {{ $patient->id }}</small>
                                            </div>
                                        @else
                                            <div class="avatar-sm bg-primary-subtle text-primary rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                <i class="bi bi-building-fill p-2"></i>
                                            </div>
                                            <div>
                                                <div class="fw-medium">{{ $patient->societe }}</div>
                                                <small class="text-muted">ID: {{ $patient->id }}</small>
                                            </div>
                                        @endif

                                    </div>
                                </td>
                                <td>{{ $patient->birth_date ? \Carbon\Carbon::parse($patient->birth_date)->format('d/m/Y') : '-' }}</td>
                                <td>
                                    @if($patient->gender == 'M')
                                        <span class="badge bg-primary">Homme</span>
                                    @elseif($patient->gender == 'F')
                                        <span class="badge bg-info">Femme</span>
                                    @else
                                        <span class="badge bg-secondary">Autre</span>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ $patient->phone ?: '-' }}</div>
                                    @if($patient->secondary_phone)
                                        <small class="text-muted">{{ $patient->secondary_phone }}</small>
                                    @endif
                                </td>
                                <td>{{ $patient->email ?: '-' }}</td>
                                <td>
                                    @if($patient->insurance_number)
                                        <div class="small">{{ $patient->insurance_company }}</div>
                                        <small class="text-muted">{{ $patient->insurance_number }}</small>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('patients.show', $patient) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('patients.edit', $patient) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                onclick="confirmDelete('{{ $patient->id }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    <form id="delete-form-{{ $patient->id }}"
                                          action="{{ route('patients.destroy', $patient) }}"
                                          method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-4 text-center">
                                    <div class="text-muted">
                                        <i class="mb-2 bi bi-inbox-fill fs-2 d-block"></i>
                                        Aucun patient trouvé
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Affichage de {{ $patients->firstItem() ?? 0 }} à {{ $patients->lastItem() ?? 0 }} sur {{ $patients->total() }} patients
                </div>
                {{ $patients->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(patientId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce patient ?')) {
        document.getElementById('delete-form-' + patientId).submit();
    }
}
</script>
@endpush
@endsection
