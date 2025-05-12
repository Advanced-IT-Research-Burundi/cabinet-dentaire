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
                @include('treatment._form')

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
