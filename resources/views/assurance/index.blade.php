@extends('layouts.app')

@section('title', 'Liste des Assurances')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Assurances</h1>
        <a href="{{ route('assurances.create') }}" class="btn btn-primary">
            <i class="bi bi-plus"></i> Ajouter une Assurance
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nom</th>
                        <th>Pourcentage de Couverture</th>
                        <th>Description</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($assurances as $assurance)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $assurance->name }}</td>
                            <td>{{ $assurance->coverage_percentage }}%</td>
                            <td>{{ $assurance->description ?: '-' }}</td>
                            <td class="text-center">
                                <a href="{{ route('assurances.edit', $assurance->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i> Modifier
                                </a>
                                <form action="{{ route('assurances.destroy', $assurance->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette assurance ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i> Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Aucune assurance trouvée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">
                {{ $assurances->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
