{{-- edit.blade.php - Page pour modifier un dentiste existant --}}

@extends('layouts.app')

@section('content')
<div class="container py-4">
    {{-- En-tête de la page --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0 text-primary">
                    <i class="bi bi-pencil-square me-2"></i>Modifier un Dentiste
                </h1>
                <div>
                    <a href="{{ route('dentists.show', $dentist) }}" class="btn btn-outline-primary me-2">
                        <i class="bi bi-eye me-1"></i> Voir le profil
                    </a>
                    <a href="{{ route('dentists.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Retour à la liste
                    </a>
                </div>
            </div>
            <nav aria-label="breadcrumb" class="mt-2">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dentists.index') }}">Dentistes</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dentists.show', $dentist) }}">{{ $dentist->user->first_name }} {{ $dentist->user->last_name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Modifier</li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- Affichage des messages de succès ou d'erreur --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Formulaire de modification --}}
    <form action="{{ route('dentists.update', $dentist) }}" method="POST">
        @csrf
        @method('PUT')
        @include('dentist._form')
    </form>
</div>

@endsection
