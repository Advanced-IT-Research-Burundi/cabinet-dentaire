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
            <h6>Informations sur les services non payés</h6>
            <div class="alert alert-info">
                <strong>Nombre de services non payés:</strong> {{ $patient->treatementsNotPaids->count() }}<br>
                <br>
                <strong>Total:</strong> {{ $patient->treatementsNotPaids->sum('applied_price') }}
            </div>
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
                        <td>
                            @forelse ( $treatement->treatmentTypes as $traitement)
                                <li>{{ $traitement?->name ?? 'N/A' }}</li>
                            @empty
                                {{ $treatement?->treatmentType?->name ?? 'N/A' }}
                            @endforelse
                        </td>
                        <td>{{ $treatement->applied_price }}</td>
                        <td>{{ $treatement->created_at }}</td>
                        <td>{{ $treatement->status }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Section des produits choisis -->
            @if(count($productsChoosed) > 0)
            <div>
                <h6>Produits pharmaceutiques choisis</h6>
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
                                <input min="0" type="number" wire:model="productsChoosed.{{ $index }}.price"
                                class="form-control form-control-sm"
                                step="0.01"
                                oninput="this.value = Math.max(0, this.value)"
                                >
                            </td>
                            <td>
                                <input type="number"
                                    min="0"
                                    wire:model="productsChoosed.{{ $index }}.quantite"
                                    class="form-control form-control-sm
                                    @if ($product['quantite'] > $product['quantite_disponible'])
                                        is-invalid
                                    @endif"
                                    step="0.01"
                                    oninput="this.value = Math.max(0, this.value)"
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
                        <tr>
                            <td colspan="5"><strong>Total Produits Pharmaceutiques</strong></td>
                            <td><strong>{{ $this->totalPrixProduits }}</strong></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @endif

            <!-- Section des types de traitement choisis -->
            @if(count($typetreatmentsChoosed) > 0)
            <div>
                <h6>Types de traitement choisis</h6>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom du traitement</th>
                            <th>Catégorie</th>
                            <th>Prix</th>
                            <th>Quantité</th>
                            <th>Montant</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($typetreatmentsChoosed as $index => $treatmentType)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $treatmentType['name'] }}</td>
                            <td>{{ $treatmentType['category'] ?? 'N/A' }}</td>
                            <td>
                                <input min="0" type="number" wire:model="typetreatmentsChoosed.{{ $index }}.price"
                                class="form-control form-control-sm"
                                step="0.01"
                                oninput="this.value = Math.max(0, this.value)"
                                >
                            </td>
                            <td>
                                <input type="number"
                                    min="0"
                                    wire:model="typetreatmentsChoosed.{{ $index }}.quantite"
                                    class="form-control form-control-sm"
                                    step="1"
                                    disabled
                                    oninput="this.value = Math.max(1, this.value)"
                                >
                            </td>
                            <td>
                                {{ $typetreatmentsChoosed[$index]['price'] * $typetreatmentsChoosed[$index]['quantite'] }}
                            </td>
                            <td>
                                <button wire:click="removeTreatmentType({{ $treatmentType['id'] }})" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="5"><strong>Total Types de Traitement</strong></td>
                            <td><strong>{{ $this->totalPrixTypeTraitements }}</strong></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @endif

            <div class="flex-wrap d-flex justify-content-between align-items-start">
                <div>
                    <div class="gap-2 d-flex">
                        <span class="me-3 d-block">Total traitements sélectionnés:
                            {{ $patient->treatementsNotPaids->whereIn('id', $selectedTreatments)->sum('applied_price') }}
                        </span>
                        <span class="me-3 d-block">Total produits: {{ $this->totalPrixProduits }}</span>
                        <span class="me-3 d-block">Total types de traitement: {{ $this->totalPrixTypeTraitements }}</span>
                    </div>
                    <button class="btn btn-secondary btn-sm" wire:click="deselectAll">
                        <i class="bi bi-x-square"></i> Désélectionner tout
                    </button>
                </div>
                <div class="mt-3 mt-md-0">
                    <div>

                        <span class="me-3 d-block">Total de la Facture:
                            <b>{{ number_format(
                                $patient->treatementsNotPaids->whereIn('id', $selectedTreatments)->sum('applied_price')
                                + $this->totalPrixProduits
                                + $this->totalPrixTypeTraitements, 2) }}</b>
                        </span>
                    </div>

                    <div class="mt-2">
                        <button class="btn btn-success btn-sm me-2" wire:click="validateSelection">
                            <i class="bi bi-check2-circle"></i> Valider
                        </button>

                        <button class="btn btn-primary btn-sm" wire:click="createInvoice">
                            <i class="bi bi-receipt"></i> Créer la facture
                        </button>
                    </div>
                </div>
            </div>


            @endif

            <div class="mt-4 col-md-12">
                {{-- <div class="gap-2 d-flex justify-content-between">
                    <h6>Facturation sur les produits Pharmaceutiques</h6>
                    <h6>Facturation sur les types de traitement</h6>
                </div> --}}
                @if ($patient)
                <div class="gap-2 d-flex justify-content-between">
                    <!-- Section Produits Pharmaceutiques -->
                    <div class="col-md-6">
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
                                        <button wire:click="addProduct({{ $product->id }})" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif
                    </div>

                    <!-- Section Types de Traitement -->
                    <div class="col-md-6">
                        <div>
                            <div class="gap-2 d-flex">
                                <input type="text" wire:model="treatmentTypeName" placeholder="Nom du traitement" class="mb-2 form-control form-control-sm" wire:keydown="searchTreatmentType">
                                <button wire:click="searchTreatmentType" class="btn btn-primary btn-sm">
                                    <i class="bi bi-search"></i>
                                </button>
                                <button wire:click="clearTreatmentType" class="btn btn-danger btn-sm">
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
                        @if ($typetreatments)
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nom du traitement</th>
                                    <th>Prix</th>
                                    <th>Durée moyenne</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($typetreatments as $type)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $type->name }}</td>
                                    <td>{{ $type->base_price }}</td>
                                    <td>{{ $type->average_duration }}</td>
                                    <td>
                                        <button wire:click="addTreatmentType({{ $type->id }})" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        @if ($patient)
        <div class="card">
            <div class="text-white card-header bg-primary">
                <h6 class="mb-0">
                    <i class="bi bi-person-fill me-2"></i>
                    Informations sur le patient
                </h6>
            </div>
            <div class="p-0 card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <i class="bi bi-hash me-2 text-primary"></i>
                        <strong>ID:</strong>
                        <span class="badge bg-secondary ms-2">{{ $patient['id'] }}</span>
                    </li>
                    <li class="list-group-item">
                        <i class="bi bi-person-badge me-2 text-success"></i>
                        <strong>Nom:</strong>
                        {{ $patient['full_name'] }}
                    </li>
                    <li class="list-group-item">
                        <i class="bi bi-calendar-date me-2 text-info"></i>
                        <strong>Date de naissance:</strong>
                        {{ \Carbon\Carbon::parse($patient['date_of_birth'])->format('d-m-Y') }}
                    </li>
                    <li class="list-group-item">
                        <i class="bi bi-{{ $patient['gender'] === 'M' || $patient['gender'] === 'Masculin' ? 'person-standing' : 'person-standing-dress' }} me-2 text-warning"></i>
                        <strong>Genre:</strong>
                        <span class="badge bg-{{ $patient['gender'] === 'M' || $patient['gender'] === 'Masculin' ? 'primary' : 'secondary' }}">
                            {{ $patient['gender'] ?? 'Société' }}
                        </span>
                    </li>
                    <li class="list-group-item">
                        <i class="bi bi-telephone-fill me-2 text-success"></i>
                        <strong>Téléphone:</strong>
                        <a href="tel:{{ $patient['phone'] }}" class="text-decoration-none">
                            {{ $patient['phone'] }}
                        </a>
                    </li>
                    <li class="list-group-item">
                        <i class="bi bi-envelope-fill me-2 text-danger"></i>
                        <strong>Email:</strong>
                        <a href="mailto:{{ $patient['email'] }}" class="text-decoration-none">
                            {{ $patient['email'] }}
                        </a>
                    </li>
                    <li class="list-group-item">
                        <i class="bi bi-geo-alt-fill me-2 text-warning"></i>
                        <strong>Adresse:</strong>
                        <div class="mt-1">
                            <i class="bi bi-house-door me-1 text-muted"></i>{{ $patient['address'] }}<br>
                            <i class="bi bi-building me-1 text-muted"></i>{{ $patient['city'] }}, {{ $patient['postal_code'] }}<br>
                            <i class="bi bi-flag me-1 text-muted"></i>{{ $patient['country'] }}
                        </div>
                    </li>
                    <li class="list-group-item">
                        <i class="bi bi-shield-check me-2 text-primary"></i>
                        <strong>Assurance:</strong>
                        <div class="mt-1">
                            <span class="badge bg-info me-2">{{ $patient['insurance_number'] }}</span>
                            <br>
                            <small class="text-muted">
                                <i class="bi bi-building-check me-1"></i>
                                {{ $patient['insurance_company'] }}
                            </small>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        @else
        <div class="card">
            <div class="text-center card-body">
                <i class="mb-2 bi bi-person-x display-4 text-muted"></i>
                <h6 class="text-muted">Aucun patient sélectionné</h6>
                <p class="mb-0 text-muted small">Veuillez rechercher et sélectionner un patient</p>
            </div>
        </div>
        @endif

        <div class="card">
            <div class="text-white card-header bg-info">
                <h6 class="mb-0">
                    <i class="bi bi-receipt me-2"></i>
                    Historique des factures
                </h6>
            </div>
            <div class="p-0 card-body">
                @if ($patient?->invoices?->count() > 0)
                    <div class="table-responsive">
                        <table class="table mb-0 table-hover table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">
                                        <i class="bi bi-hash me-1"></i>
                                        N°
                                    </th>
                                    <th>
                                        <i class="bi bi-calendar-date me-1"></i>
                                        Date
                                    </th>
                                    <th>
                                        <i class="bi bi-cash-stack me-1"></i>
                                        Montant
                                    </th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($patient->invoices->take(10) as $invoice)
                                <tr>
                                    <td class="text-center">
                                        <span class="badge bg-secondary">
                                            {{ $invoice->invoice_number }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <i class="bi bi-calendar-check me-1"></i>
                                            {{ \Carbon\Carbon::parse($invoice->issue_date)->format('d/m/Y') }}
                                        </small>
                                    </td>
                                    <td>
                                        <strong class="text-success">
                                            {{ number_format($invoice->total_amount, 0) }} FBU
                                        </strong>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('invoices.show', $invoice->id) }}"
                                        class="btn btn-outline-primary btn-sm"
                                        title="Visualiser la facture">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($patient->invoices->count() > 10)
                    <div class="text-center card-footer">
                        <a href="{{ route('invoices.index', ['patient_id' => $patient['id']]) }}"
                        class="btn btn-outline-info btn-sm">
                            <i class="bi bi-eye me-1"></i>
                            Voir toutes les factures ({{ $patient->invoices->count() }})
                        </a>
                    </div>
                    @endif
                @else
                    <div class="py-4 text-center card-body">
                        <i class="mb-2 bi bi-receipt-cutoff display-4 text-muted"></i>
                        <h6 class="text-muted">Aucune facture</h6>
                        <p class="mb-3 text-muted small">Ce patient n'a pas encore de factures enregistrées</p>

                        <p class="mb-3 text-muted small">Ce patient n'a pas encore de factures enregistrées</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
