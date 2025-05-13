@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Caisses</h1>

            <a href="{{ route('caisses.create') }}" class="mb-3 btn btn-primary">Nouvelle Caisse</a>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Montant</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($caisses as $caisse)
                    <tr>
                        <td>{{ $caisse->type }}</td>
                        <td>{{ $caisse->date->format('d/m/Y H:i') }}</td>
                        <td>{{ number_format($caisse->montant, 2) }} €</td>
                        <td>{{ $caisse->description }}</td>
                        <td>
                            <span class="badge
                                @if($caisse->status == 'completed') bg-success
                                @elseif($caisse->status == 'pending') bg-warning
                                @else bg-secondary
                                @endif">
                                {{ $caisse->status }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('caisse.edit', $caisse->id) }}" class="btn btn-sm btn-warning">Modifier</a>
                            <form action="{{ route('caisse.destroy', $caisse->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr?')">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Aucune caisse trouvée</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
