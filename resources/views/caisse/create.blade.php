@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-plus-circle-fill me-2"></i>Créer une nouvelle Caisse
                    </h5>
                </div>

                <div class="card-body">
                    @include('caisse._form', [
                        'action' => route('caisses.store'),
                        'users' => $users
                    ])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
