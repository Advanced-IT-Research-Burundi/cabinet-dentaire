@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="mb-4 row">
        <div class="col">
            <h1>Historique des traitements</h1>
        </div>
        <div class="col text-end">
            <a href="{{ route('treatments.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Nouveau traitement
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Patient</th>
                            <th>Dentiste</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Prix</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($treatments as $treatment)
                            <tr>
                                <td>{{ $treatment->id }}</td>
                                <td>
                                <span class="badge bg-primary">
                                    
                                    {{  $treatment->patient->id }}
                                </span>
                                {{ $treatment->patient->full_name }}
                            </td>
                                <td>{{ $treatment->dentist->name }}

                                </td>
                                <td>{{ $treatment->treatmentType->name }}</td>
                                <td>{{ $treatment->date }}</td>
                                <td>{{ number_format($treatment->applied_price, 2) }} FBU</td>
                                <td>
                                    @switch($treatment->status)
                                        @case('Planifie')
                                            <span class="badge bg-info">Planifié</span>
                                            @break
                                        @case('En_cours')
                                            <span class="badge bg-warning">En cours</span>
                                            @break
                                        @case('Termine')
                                            <span class="badge bg-success">Terminé</span>
                                            @break
                                        @case('Annule')
                                            <span class="badge bg-danger">Annulé</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('treatments.show', $treatment) }}" class="btn btn-sm btn-info" title="Voir">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('treatments.edit', $treatment) }}" class="btn btn-sm btn-warning" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('treatments.destroy', $treatment) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce traitement ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Aucun traitement trouvé</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
   
            <div class="mt-3">
                {{ $treatments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
