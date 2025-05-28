
    @extends('layouts.app')

    @section('content')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Nouvelle facture</h5>
                        <div>

                            <div >
                                <h5>
                                    <i class="bi bi-cash-stack"></i>
                                    Caisse: <strong> {{ Auth::user()->caisses()->sum('montant') ?? '0' }} Fbu</strong>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                                <livewire:invoinces.create-invoice />
                    </div>
                </div>
            </div>
        </div>
    @endsection
