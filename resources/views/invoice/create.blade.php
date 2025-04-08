
    @extends('layouts.app')

    @section('content')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Nouvelle facture</h5>
                    </div>
                    <div class="card-body">
                                <livewire:invoinces.create-invoice />
                    </div>
                </div>
            </div>
        </div>
    @endsection
