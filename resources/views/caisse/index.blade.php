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
                        <th>Nom</th>
                        <th>Utilisateur</th>
                        <th>Date de Création</th>
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
                        <td>{{ $caisse->name }}</td>
                        <td>{{ $caisse->user->name }}</td>
                        <td>{{ $caisse->created_at->format('Y-m-d H:i') }}</td>
                        <td>{{ number_format($caisse->montant, 2) }} </td>
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
                            <a href="{{ route('caisses.edit', $caisse->id) }}" class="btn btn-sm btn-warning">Modifier</a>
                            <a href="{{ route('caisses.show', $caisse->id) }}" class="btn btn-sm btn-primary">Voir</a>
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
