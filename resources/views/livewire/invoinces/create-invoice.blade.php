<div>
    {{-- Care about people's approval and you will be their prisoner. --}}
  <div class="row">
    <div class="col-md-8">
        <div class="gap-2 d-flex justify-content-between">
            <input type="text" wire:model="patientID" placeholder="Numéro du patient" class="mb-2 form-control form-control-sm" wire:keydown.enter="search">
            <input type="text" wire:model="patientName" placeholder="Nom du patient" class="mb-2 form-control form-control-sm" wire:keydown.enter="search">
            <button wire:click="search" class="btn btn-primary btn-sm">
                <i class="bi bi-search"></i> Search
            </button>
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
