@extends('layouts.app')

@section('title', 'Types de traitements')

@section('content')
<div class="container-fluid">
    <div class="mb-4 row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="mb-0 h3">Types de traitements</h1>
                <a href="{{ route('settings.treatment-types.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Nouveau type
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if($treatmentTypes->isEmpty())
                        <div class="py-5 text-center">
                            <i class="mb-3 bi bi-clipboard2-x fs-1 text-muted"></i>
                            <p class="mb-0 text-muted">Aucun type de traitement trouvé</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table align-middle table-hover">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Nom</th>
                                        <th>Catégorie</th>
                                        <th>Prix de base (BIF)</th>
                                        <th>Durée (min)</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($treatmentTypes as $type)
                                        <tr>
                                            <td><code>{{ $type->code ?? '-' }}</code></td>
                                            <td>{{ $type->name }}</td>
                                            <td>{{ $type->category ?? '-' }}</td>
                                            <td>{{ number_format($type->base_price ?? 0, 0, ',', ' ') }}</td>
                                            <td>{{ $type->average_duration ?? '-' }}</td>
                                            <td>
                                                @if($type->active)
                                                    <span class="badge bg-success">Actif</span>
                                                @else
                                                    <span class="badge bg-danger">Inactif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('settings.treatment-types.edit', $type) }}"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    {{-- <form action="{{ route('settings.treatment-types.destroy', $type) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce type de traitement ?')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form> --}}
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 d-flex justify-content-center">
                            {{ $treatmentTypes->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
