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

    <div class="row">
        <div class="col-lg-8">
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

        <div class="col-lg-4">
            <div class="card sticky-top" style="top: 1rem;">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="bi bi-clock-history me-2"></i>Historique des traitements</h6>
                </div>
                <div class="card-body" id="patientHistoryPanel" style="max-height: 75vh; overflow-y: auto;">
                    <div class="text-center text-muted py-4" id="patientHistoryEmpty">
                        <i class="bi bi-person-lines-fill fs-1"></i>
                        <p class="mt-2 mb-0">Sélectionnez un rendez-vous pour afficher l'historique du patient</p>
                    </div>
                    <div id="patientHistoryLoading" class="text-center py-4 d-none">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2 mb-0 text-muted">Chargement de l'historique...</p>
                    </div>
                    <div id="patientHistoryList" class="d-none"></div>
                </div>
            </div>
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
