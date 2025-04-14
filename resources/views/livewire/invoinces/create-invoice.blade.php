<div>
    {{-- Care about people's approval and you will be their prisoner. --}}
  <div class="row">
    <div class="col-md-8">
        <div class="gap-2 d-flex justify-content-between">
            <input type="text" wire:model="patientID" placeholder="Numéro du patient" class="mb-2 form-control form-control-sm" wire:keydown.enter="search">
            <input type="text" wire:model="patientName" placeholder="Nom du patient" class="mb-2 form-control form-control-sm" wire:keydown.enter="search">
            <button wire:click="search" class="btn btn-primary btn-sm">
                <i class="bi bi-search"></i> 
            </button>
            <button wire:click="clear" class="btn btn-danger btn-sm">
                <i class="bi bi-x"></i> 
            </button>
        </div>
        <div>
            @if ($patient)
            <h6>Informations sur les services non payes</h6>
            <div class="alert alert-info">
                <strong>Nombre de services non payés:</strong> {{  $patient->treatementsNotPaids->count() }}<br>
                <br>
                <strong>Total:</strong> {{ $patient->treatementsNotPaids->sum('applied_price') }}
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Medecin</th>
                        <th>Nom du service</th>
                        <th>Prix</th>
                        <th>Date de traitement</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($patient->treatementsNotPaids as $treatement)
                    <tr>
                        <td>{{ $treatement->id }}</td>
                        <td>{{ $treatement->dentist->name }} </td>
                        <td>{{ $treatement->treatmentType->name }}</td>
                        <td>{{ $treatement->applied_price }}</td>
                        <td>{{ $treatement->created_at }}</td>
                        <td>{{ $treatement->status }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            @endif
        </div>
    </div>
    <div class="col-md-4">
        @if ($patient)
        <h6>Informations sur le patient</h6>
        <ul class="list-group">
            <li class="list-group-item"><strong>ID:</strong> {{ $patient['id'] }}</li>
            <li class="list-group-item"><strong>Nom:</strong> {{ $patient['first_name'] }} {{ $patient['middle_name'] }} {{ $patient['last_name'] }}</li>
            <li class="list-group-item"><strong>Date de naissance:</strong> {{ $patient['birth_date'] }}</li>
            <li class="list-group-item"><strong>Genre:</strong> {{ $patient['gender'] }}</li>
            <li class="list-group-item"><strong>Téléphone:</strong> {{ $patient['phone'] }}</li>
            <li class="list-group-item"><strong>Email:</strong> {{ $patient['email'] }}</li>
            <li class="list-group-item"><strong>Adresse:</strong> {{ $patient['address'] }}, {{ $patient['city'] }}, {{ $patient['postal_code'] }}, {{ $patient['country'] }}</li>
            <li class="list-group-item"><strong>Assurance:</strong> {{ $patient['insurance_number'] }} - {{ $patient['insurance_company'] }}</li>
        </ul>
        @endif
    </div>
</div>
  <div class="row">
    <div class="col-md-8">
        <h6>Informations sur les services non payes</h6>
    </div>
    <div class="col-md-4">
        <h6>Historique des factures</h6>
    </div>
  </div>
</div>
