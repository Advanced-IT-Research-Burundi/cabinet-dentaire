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

    <div class="card mb-3">
        <div class="card-header bg-light py-1">
            <h6 class="mb-0">
                <i class="bi bi-funnel me-2"></i> Filtres
            </h6>
        </div>
        <div class="card-body p-2">
                    <form action="{{ route('treatments.index') }}" method="GET" class="row g-2 align-items-end">


                        <div class="col-md-3 col-lg-2">
                            <label for="dentist_id" class="form-label small mb-1">Dentiste</label>
                            <select name="dentist_id" id="dentist_id" class="form-select form-select-sm">
                                <option value="">Tous</option>
                                @foreach($dentists as $dentist)
                                    <option value="{{ $dentist->id }}" {{ request('dentist_id') == $dentist->id ? 'selected' : '' }}>
                                        {{ $dentist->user->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 col-lg-2">
                            <label for="treatment_type_id" class="form-label small mb-1">Type</label>
                            <select name="treatment_type_id" id="treatment_type_id" class="form-select form-select-sm">
                                <option value="">Tous</option>
                                @foreach($treatmentTypes as $type)
                                    <option value="{{ $type->id }}" {{ request('treatment_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 col-lg-2">
                            <label for="status" class="form-label small mb-1">Statut</label>
                            <select name="status" id="status" class="form-select form-select-sm">
                                <option value="">Tous</option>
                                @foreach($statuses as $key => $value)
                                    <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 col-lg-2">
                            <label for="date_start" class="form-label small mb-1">Date début</label>
                            <input type="date" name="date_start" id="date_start" class="form-control form-control-sm" value="{{ request('date_start') }}">
                        </div>

                        <div class="col-md-3 col-lg-2">
                            <label for="date_end" class="form-label small mb-1">Date fin</label>
                            <input type="date" name="date_end" id="date_end" class="form-control form-control-sm" value="{{ request('date_end') }}">
                        </div>

                        <div class="col-md-3 col-lg-2">
                            <label for="sort_by" class="form-label small mb-1">Trier par</label>
                            <select name="sort_by" id="sort_by" class="form-select form-select-sm">
                                <option value="created_at" {{ request('sort_by') == 'created_at' || !request('sort_by') ? 'selected' : '' }}>Création</option>
                                <option value="date" {{ request('sort_by') == 'date' ? 'selected' : '' }}>Date trt</option>
                                <option value="id" {{ request('sort_by') == 'id' ? 'selected' : '' }}>ID</option>
                                <option value="applied_price" {{ request('sort_by') == 'applied_price' ? 'selected' : '' }}>Prix</option>
                            </select>
                        </div>

                        <div class="col-md-3 col-lg-2">
                            <label for="sort_order" class="form-label small mb-1">Ordre</label>
                            <select name="sort_order" id="sort_order" class="form-select form-select-sm">
                                <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Desc</option>
                                <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Asc</option>
                            </select>
                        </div>

                        <div class="col-12 col-lg-2 d-flex gap-1">
                            <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                                <i class="bi bi-filter"></i> Filtrer
                            </button>
                            <a href="{{ route('treatments.index') }}" class="btn btn-secondary btn-sm" title="Réinitialiser">
                                <i class="bi bi-x-circle"></i>
                            </a>
                        </div>
                    </form>
        </div>
    </div>


    <div class="card">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Liste des traitements</h5>
            <div class="dropdown">
                {{-- <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-download me-1"></i> Exporter
                </button> --}}
                {{-- <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                    <li><a class="dropdown-item" href="{{ route('treatments.export', ['format' => 'pdf'] + request()->all()) }}">PDF</a></li>
                    <li><a class="dropdown-item" href="{{ route('treatments.export', ['format' => 'excel'] + request()->all()) }}">Excel</a></li>
                </ul> --}}
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Patient</th>
                            <th>Dentiste</th>
                            <th>Enregistré par</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Date création</th>
                            <th>Prix</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($treatments as  $treatment)
                            <tr>
                                <td>{{ $treatment->id  }}</td>
                                <td>
                                    {{ $treatment?->patient?->full_name }}
                                </td>
                                <td>
                                    {{ $treatment?->dentist?->user?->full_name }}
                                </td>
                                <td>{{ $treatment?->user?->full_name }}</td>
                                 <td>
                                    @forelse ( $treatment->treatmentTypes as $traitement)
                                        <li>{{ $traitement?->name ?? 'N/A' }}</li>
                                    @empty
                                        {{ $treatment?->treatmentType?->name ?? 'N/A' }}</td>
                                    @endforelse

                                </td>
                                <td>{{ \Carbon\Carbon::parse($treatment->date)->format('d/m/Y') }}</td>
                                <td>{{ $treatment->created_at->format('d/m/Y H:i') }}</td>
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

            <div class="mt-3 d-flex justify-content-between align-items-center">
                <div>
                    Affichage de {{ $treatments->firstItem() ?? 0 }} à {{ $treatments->lastItem() ?? 0 }} sur {{ $treatments->total() }} traitements
                </div>
                <div>
                    {{ $treatments->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
