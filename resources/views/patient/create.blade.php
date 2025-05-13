@extends('layouts.app')

@section('title', 'Nouveau Patient')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="bi bi-person-plus me-1"></i> Nouveau Patient
                    </h5>

                    <form action="{{ route('patients.store') }}" method="POST" class="row g-3">
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
