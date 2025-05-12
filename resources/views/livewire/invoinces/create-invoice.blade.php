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
            @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif

            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
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
                        <th>
                            <input type="checkbox" wire:click="selectAll">
                        </th>
                        <th>ID</th>
                        <th>Médecin</th>
                        <th>Nom du service</th>
                        <th>Prix</th>
                        <th>Date de traitement</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($patient->treatementsNotPaids as $treatement)
                    <tr>
                        <td>
                            <input type="checkbox" wire:model.live="selectedTreatments" value="{{ $treatement->id }}">
                        </td>
                        <td>{{ $treatement->id }}</td>
                        <td>{{ $treatement->dentist->name }}</td>
                        <td>{{ $treatement->treatmentType->name }}</td>
                        <td>{{ $treatement->applied_price }}</td>
                        <td>{{ $treatement->created_at }}</td>
                        <td>{{ $treatement->status }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div>
                <h4>Produits choisis</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Qte en Stock</th>
                            <th>Prix</th>
                            <th>Quantité</th>
                            <th>Montant</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productsChoosed as $index => $product)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $product['product_name'] }}</td>
                            <td>{{ $product['quantite_disponible'] }}</td>
                            <td>
                                <input type="number" wire:model="productsChoosed.{{ $index }}.price"
                                class="form-control form-control-sm"
                                step="0.01"
                                >
                            </td>
                            <td>
                                <input type="number" wire:model="productsChoosed.{{ $index }}.quantite"
                                class="form-control form-control-sm
                                @if ($product['quantite'] > $product['quantite_disponible'])
                                is-invalid
                                @endif

                                "
                                step="0.01"
                                >
                            </td>
                            <td>
                                {{ $productsChoosed[$index]['price'] * $productsChoosed[$index]['quantite'] }}
                            </td>
                            <td>
                                <button wire:click="removeProduct({{ $product['id'] }})" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                        @if ($productsChoosed)
                        <tr>
                            <td colspan="3">Total</td>
                            <td colspan="2">
                                <button wire:click="addProductToInvoice" class="btn btn-primary btn-sm">
                                    Valider
                                </button>
                            </td>
                            <td>{{  $this->totalPrixProduits  }}</td>
                            <td></td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <button class="btn btn-secondary btn-sm" wire:click="deselectAll">
                        <i class="bi bi-x-square"></i> Désélectionner tout
                    </button>
                </div>
                <div class="mt-4">
                    <span class="me-3">Total sélectionné: {{ $patient->treatementsNotPaids->whereIn('id', $selectedTreatments)->sum('applied_price') }}</span>
                    <button class="btn btn-primary btn-sm" wire:click="createInvoice">
                        <i class="bi bi-receipt"></i> Créer la facture
                    </button>
                </div>
            </div>
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
        <h6>Facturation sur les produits Pharmaceutiques</h6>
        @if ($patient)
        <div>
            <div>
            <div class="gap-2 d-flex">
            <input type="text" wire:model="productName" placeholder="Nom du produit" class="mb-2 form-control form-control-sm" wire:keydown="searchProduct">
            <button wire:click="searchProduct" class="btn btn-primary btn-sm">
                <i class="bi bi-search"></i>
            </button>
            <button wire:click="clearProduct" class="btn btn-danger btn-sm">
                <i class="bi bi-x"></i>
            </button>
            </div>

            @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
            @endif
            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
            </div>
            @if ($products)
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prix</th>
                        <th>Quantité</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ $product->price }}</td>
                        <td>{{ $product->quantite }}</td>
                        <td>
                            <button wire:click="addProduct({{ $product->id }})" class="btn-sm">
                                <i class="bi bi-plus"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
        @endif
    </div>
    <div class="col-md-4">
        <h6>Historique des factures</h6>
    </div>
  </div>
</div>
