@extends('layouts.app')

@section('content')

<div class="container">
    <div class="mb-4 row">
        <div class="col">
            <h1>Nouveau traitement</h1>
        </div>
        <div class="col text-end">
            <a href="{{ route('treatments.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Retour
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('treatments.store') }}" method="POST">
                @include('treatment._form')

                <div class="d-flex justify-content-end mt-4 gap-2">
                    @if(isset($treatment))
                        <a href="{{ route('treatments.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-1"></i> Annuler
                        </a>
                    @else
                        <button type="reset" class="btn btn-secondary">
                            <i class="bi bi-arrow-clockwise me-1"></i> Réinitialiser
                        </button>
                    @endif

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Enregistrer
                        </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('js/multiselect.js') }}"></script>
@endpush
@push('styles')
<link href="{{ asset('css/multiselect.css') }}" rel="stylesheet">
@endpush
