@extends('layouts.app')

@section('title', 'Modifier Patient')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="bi bi-pencil-square me-1"></i> Modifier Patient
                    </h5>

                    <form action="{{ route('patients.update', $patient) }}" method="POST" class="row g-3">
                        @include('patient._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.required:after {
    content: '*';
    color: red;
    margin-left: 4px;
}
</style>
@endpush
@endsection
