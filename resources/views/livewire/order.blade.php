<div>
    <div class="row g-4">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h5>Informations</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('orders.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="{{ $type }}">
                        <div>
                            <label for="patient_id" class="form-label">Patient <span>({{ $selectedPatient ? $selectedPatient->first_name . " " . $selectedPatient->last_name : 'Pas de patient selectionné' }})</span></label>
                            <div class="input-group">
                                <input type="text" id="selected_patient_name" class="form-control" placeholder="Sélectionner un patient" readonly>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#patientModal">Choisir</button>
                            </div>
                            <input type="hidden" id="patient_id" name="patient_id" required>
                            @error('patient_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Modal for selecting a patient -->
                        <div class="modal fade" id="patientModal" tabindex="-1" aria-labelledby="patientModalLabel" aria-hidden="true" wire:ignore.self>
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="patientModalLabel">Sélectionner un Patient</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="text" id="search_patient" wire:model.live="search_patient" class="form-control mb-3" placeholder="Rechercher un patient..." autofocus>
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Nom</th>
                                                    <th>Email</th>
                                                    <th>Téléphone</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody id="patient_list">
                                                @if (count($patients) > 0)
                                                    @foreach ($patients as $patient)
                                                        <tr>
                                                            <td valign="middle">{{ $patient->first_name }} {{ $patient->last_name }}</td>
                                                            <td valign="middle">{{ $patient->email }}</td>
                                                            <td valign="middle">{{ $patient->phone }}</td>
                                                            <td>
                                                                <button type="button" wire:click="selectPatient({{$patient}})" data-bs-dismiss="modal" class="btn btn-sm btn-primary select-patient">
                                                                    Sélectionner
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="4" class="text-center">Aucun patient trouvé pour <b>"{{ $search_patient }}"</b></td>
                                                    </tr>
                                                @endif
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if ($selectedPatient && $treatments)
                            <div class="mt-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5>Traitements </h5>
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#treatmentModal" id="add-treatment-row">Ajouter un Traitement</button>
                                </div>
                                
                                <table class="table table-bordered mt-3">
                                    <thead>
                                        <tr>
                                            <th>Traitement</th>
                                            <th>Prix</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="treatment-rows">
                                        @if (count($selectedTreatments) > 0)
                                            @foreach($selectedTreatments as $index => $treatment)
                                                <tr>
                                                    <td valign="middle">                                                            
                                                        {{ $treatment['description'] }}
                                                    </td>
                                                    <td valign="middle">
                                                        {{ $treatment['applied_price'] }}
                                                    </td>
                                                    <td>
                                                        <button type="button" wire:click="removeTreatment({{$index}})" class="btn btn-danger btn-sm remove-treatment-row">Supprimer</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                            <td colspan="3" class="text-center">Pas de treatment ajouté!</td>
                                            </tr> 
                                        @endif
                                        
                                    </tbody>
                                </table>
                            </div>

                            <!-- Modal for adding treatment -->
                            <div class="modal fade" id="treatmentModal" tabindex="-1" aria-labelledby="treatmentModalLabel" aria-hidden="true" wire:ignore.self>
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="patientModalLabel">Ajouter les traitements</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            {{-- <input type="text" id="search_patient" wire:model.live="search_patient" class="form-control mb-3" placeholder="Rechercher un patient..." autofocus> --}}
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Date de création</th>
                                                        <th>Type de traitement</th>
                                                        <th>Descrpiption</th>
                                                        <th>Prix</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="treatment_list">
                                                    @if (count($treatments) > 0)
                                                        @foreach ($treatments as $index => $treatment)
                                                            <tr>
                                                                <td valign="middle">{{ $treatment->created_at->format('d-m-Y') }}</td>
                                                                <td valign="middle">{{ $treatment->treatmentType->name }}</td>
                                                                <td valign="middle">{{ $treatment->description }}</td>
                                                                <td valign="middle">{{ $treatment->applied_price }}</td>
                                                                <td>
                                                                    <button type="button" wire:click="addTreatment({{$treatment}})" data-bs-dismiss="modal" class="btn btn-sm btn-primary select-treatment">
                                                                        Ajouter
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="5" class="text-center">Aucun treatment trouvé pour <b>"{{ $selectedPatient->first_name }} {{ $selectedPatient->last_name }}"</b></td>
                                                        </tr>
                                                    @endif
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5>Produits </h5>
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#productModal" id="add-treatment-row">Ajouter un Produit</button>
                                </div>
                                
                                <table class="table table-bordered mt-3">
                                    <thead>
                                        <tr>
                                            <th>Produit</th>
                                            <th>Quantité</th>
                                            <th>Prix U.</th>
                                            <th>Prix Totale</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="treatment-rows">
                                        @if (count($selectedProducts) > 0)
                                            @foreach($selectedProducts as $index => $product)
                                                <tr>
                                                    <td valign="middle">                                                            
                                                        {{ $product['product_name'] }}
                                                    </td>

                                                    <td valign="middle">
                                                        <input type="number" max="selectedProducts.{{$index}}.max_qty" class="form-control" wire:model.live="selectedProducts.{{$index}}.qty">
                                                    </td>
                                                    <td valign="middle">
                                                        <input type="text" class="form-control" wire:model.live="selectedProducts.{{$index}}.price">
                                                    </td>
                                                    <td valign="middle">
                                                        <input type="text" class="form-control" wire:model.live="selectedProducts.{{$index}}.total_price" readonly>
                                                    </td>
                                                    <td>
                                                        <button type="button" wire:click="removeProduct({{$index}})" class="btn btn-danger btn-sm remove-treatment-row">Supprimer</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                            <td colspan="5" class="text-center">Pas de produit ajouté!</td>
                                            </tr> 
                                        @endif
                                        
                                    </tbody>
                                </table>
                            </div>

                            <!-- Modal for adding product -->
                            <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="treatmentModalLabel" aria-hidden="true" wire:ignore.self>
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="patientModalLabel">Ajouter les produits</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            {{-- <input type="text" id="search_patient" wire:model.live="search_patient" class="form-control mb-3" placeholder="Rechercher un patient..." autofocus> --}}
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Nom du produit</th>
                                                        <th>Quantité</th>
                                                        <th>Prix</th>
                                                        <th>Status</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="product_list">
                                                    @if (count($products) > 0)
                                                        @foreach ($products as $index => $product)
                                                            <tr>
                                                                <td valign="middle">{{ $product->product_name }}</td>
                                                                <td valign="middle">{{ $product->quantite }}</td>
                                                                <td valign="middle">{{ $product->price }}</td>
                                                                <td valign="middle">{{ $product->status }}</td>
                                                                <td>
                                                                    <button type="button" wire:click="addProduct({{$product}})" data-bs-dismiss="modal" class="btn btn-sm btn-primary select-product">
                                                                        Ajouter
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="5" class="text-center">Aucun produit trouvé pour <b>"{{ $searchProduct }}"</b></td>
                                                        </tr>
                                                    @endif
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h5>Résumé et Paiement</h5>
                </div>
                <div class="card-body">
                    <div class="">
                        <label class="form-label">Le patient</label>
                        <table class="table table-bordered">
                            <tr>
                                <td>Nom & Prénom: </td>
                                <td>{{ $selectedPatient->first_name ?? '-' }} {{ $selectedPatient->last_name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Assurance</td>
                                <td>{{ $selectedPatient->assurance->name ?? '-'}} | {{$selectedPatient->assurance->coverage_percentage ?? '-' }}%</td>
                            </tr>
                            <tr>
                                <td>No. d'assurance</td>
                                <td>{{ $selectedPatient->insurance_number ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="mt-4">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <label for="amount" class="form-label">Montant Total</label>
                                <input type="text" id="amount" name="amount" wire:model="totalAmount" class="form-control" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="final_amount" class="form-label">Montant(Patient)</label>
                                <input type="text" id="final_amount" wire:model="patientAmount" name="final_amount" class="form-control" readonly>
                                <span class="badge text-bg-success mt-2">Assurance: {{ $insuranceAmount }}</span>
                            </div>
                        </div>
                        {{-- <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="type_paiement" class="form-label">Type de Paiement</label>
                                <select id="type_paiement" name="type_paiement" class="form-select @error('type_paiement') is-invalid @enderror" required>
                                    <option value="" disabled selected>Choisir un type de paiement</option>
                                    <option value="En espèce" {{ old('type_paiement') == 'En espèce' ? 'selected' : '' }}>En espèce</option>
                                    <option value="Banque" {{ old('type_paiement') == 'Banque' ? 'selected' : '' }}>Banque</option>
                                    <option value="A crédit" {{ old('type_paiement') == 'A crédit' ? 'selected' : '' }}>A crédit</option>
                                </select>
                                @error('type_paiement')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div> --}}
                    </div>

                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-primary">Enregistrer la facture</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
