{{-- create.blade.php - Page pour créer un nouveau dentiste --}}

@extends('layouts.app')

@section('content')
<div class="container py-4">
    {{-- En-tête de la page --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0 text-primary">
                    <i class="bi bi-person-plus-fill me-2"></i>Ajouter un Dentiste
                </h1>
                <a href="{{ route('dentists.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Retour à la liste
                </a>
            </div>
            <nav aria-label="breadcrumb" class="mt-2">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dentists.index') }}">Dentistes</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Ajouter</li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- Formulaire de création --}}
    <form action="{{ route('dentists.store') }}" method="POST">
        @csrf
        @include('dentist._form')
    </form>
</div>

@endsection
