@extends('layouts.app')

@section('title', 'Modifier une Commande/Facture')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Modifier une Commande/Facture</h1>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">Retour à la liste</a>
    </div>

    <div class="card">
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
            <form action="{{ route('orders.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="patient_id" class="form-label">Patient</label>
                        <select id="patient_id" name="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                            <option value="" disabled>Choisir un patient</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" data-info="{{ json_encode($patient) }}" {{ old('patient_id', $order->patient_id) == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->first_name }} {{ $patient->last_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('patient_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="patient_info" class="form-label">Informations du Patient</label>
                        <textarea id="patient_info" class="form-control" readonly></textarea>
                    </div>
                </div>

                <div class="mt-4">
                    <h5>Traitements</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Traitement</th>
                                <th>Prix</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="treatment-rows">
                            @foreach($order->treatments as $index => $treatment)
                                <tr>
                                    <td>
                                        <select name="treatments[{{ $index }}][treatment_id]" class="form-select" required>
                                            <option value="" disabled>Choisir un traitement</option>
                                            @foreach($treatments as $availableTreatment)
                                                <option value="{{ $availableTreatment->id }}" data-price="{{ $availableTreatment->price }}" {{ $treatment->id == $availableTreatment->id ? 'selected' : '' }}>
                                                    {{ $availableTreatment->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control treatment-price" value="{{ $treatment->price }}" readonly>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm remove-treatment-row">Supprimer</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-primary btn-sm" id="add-treatment-row">Ajouter un Traitement</button>
                </div>

                <div class="mt-4">
                    <h5>Produits/Stocks</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Quantité</th>
                                <th>Prix Unitaire</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="detail-orders">
                            @foreach($order->detailOrders as $index => $detailOrder)
                                <tr>
                                    <td>
                                        <select name="detail_orders[{{ $index }}][product_id]" class="form-select product-select" required>
                                            <option value="" disabled>Choisir un produit</option>
                                            @foreach($stocks as $stock)
                                                <option value="{{ $stock->id }}" data-quantity="{{ $stock->quantite }}" data-price="{{ $stock->price }}" {{ $detailOrder->product_id == $stock->id ? 'selected' : '' }}>
                                                    {{ $stock->product_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="detail_orders[{{ $index }}][quantite]" class="form-control quantity-input" value="{{ $detailOrder->quantite }}" required>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="detail_orders[{{ $index }}][price_unitaire]" class="form-control price-input" value="{{ $detailOrder->price_unitaire }}" required>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control total-input" value="{{ $detailOrder->total }}" readonly>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm remove-row">Supprimer</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-primary btn-sm" id="add-row">Ajouter un Produit</button>
                </div>

                <div class="mt-4">
                    <h5>Résumé et Paiement</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="amount" class="form-label">Montant Total</label>
                            <input type="text" id="amount" name="amount" class="form-control" value="{{ $order->amount }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="tax_rate" class="form-label">Taxe (%)</label>
                            <input type="number" step="0.01" id="tax_rate" name="tax_rate" class="form-control" value="{{ $order->tax_rate }}">
                        </div>
                        <div class="col-md-4">
                            <label for="final_amount" class="form-label">Montant Final</label>
                            <input type="text" id="final_amount" name="final_amount" class="form-control" value="{{ $order->final_amount }}" readonly>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="type_paiement" class="form-label">Type de Paiement</label>
                            <select id="type_paiement" name="type_paiement" class="form-select @error('type_paiement') is-invalid @enderror" required>
                                <option value="" disabled>Choisir un type de paiement</option>
                                <option value="En espèce" {{ old('type_paiement', $order->type_paiement) == 'En espèce' ? 'selected' : '' }}>En espèce</option>
                                <option value="Banque" {{ old('type_paiement', $order->type_paiement) == 'Banque' ? 'selected' : '' }}>Banque</option>
                                <option value="A crédit" {{ old('type_paiement', $order->type_paiement) == 'A crédit' ? 'selected' : '' }}>A crédit</option>
                            </select>
                            @error('type_paiement')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="assurance_id" class="form-label">Assurance</label>
                            <select id="assurance_id" name="assurance_id" class="form-select @error('assurance_id') is-invalid @enderror">
                                <option value="" disabled>Choisir une assurance</option>
                                @foreach($assurances as $assurance)
                                    <option value="{{ $assurance->id }}" {{ old('assurance_id', $order->assurance_id) == $assurance->id ? 'selected' : '' }}>
                                        {{ $assurance->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('assurance_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('patient_id').addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const patientInfo = selectedOption.dataset.info ? JSON.parse(selectedOption.dataset.info) : {};
        document.getElementById('patient_info').value = `Nom: ${patientInfo.first_name} ${patientInfo.last_name}\nAssurance: ${patientInfo.assurance?.name || 'Aucune'}`;
    });

    function updateRowTotal(row) {
        const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        const total = quantity * price;
        row.querySelector('.total-input').value = total.toFixed(2);
        updateTotalAmount();
    }

    function updateTotalAmount() {
        let totalAmount = 0;
        document.querySelectorAll('.total-input').forEach(input => {
            totalAmount += parseFloat(input.value) || 0;
        });
        document.getElementById('amount').value = totalAmount.toFixed(2);
        updateFinalAmount();
    }

    function updateFinalAmount() {
        const totalAmount = parseFloat(document.getElementById('amount').value) || 0;
        const taxRate = parseFloat(document.getElementById('tax_rate').value) || 0;
        const taxAmount = totalAmount * (taxRate / 100);
        const finalAmount = totalAmount + taxAmount;
        document.getElementById('final_amount').value = finalAmount.toFixed(2);
    }

    document.getElementById('tax_rate').addEventListener('input', updateFinalAmount);

    document.getElementById('add-row').addEventListener('click', function () {
        const tableBody = document.getElementById('detail-orders');
        const rowCount = tableBody.rows.length;
        const newRow = `
            <tr>
                <td>
                    <select name="detail_orders[${rowCount}][product_id]" class="form-select product-select" required>
                        <option value="" disabled>Choisir un produit</option>
                        @foreach($stocks as $stock)
                            <option value="{{ $stock->id }}" data-quantity="{{ $stock->quantite }}" data-price="{{ $stock->price }}">
                                {{ $stock->product_name }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" step="0.01" name="detail_orders[${rowCount}][quantite]" class="form-control quantity-input" required>
                </td>
                <td>
                    <input type="number" step="0.01" name="detail_orders[${rowCount}][price_unitaire]" class="form-control price-input" required>
                </td>
                <td>
                    <input type="text" class="form-control total-input" readonly>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-row">Supprimer</button>
                </td>
            </tr>
        `;
        tableBody.insertAdjacentHTML('beforeend', newRow);
    });

    document.getElementById('add-treatment-row').addEventListener('click', function () {
        const tableBody = document.getElementById('treatment-rows');
        const rowCount = tableBody.rows.length;
        const newRow = `
            <tr>
                <td>
                    <select name="treatments[${rowCount}][treatment_id]" class="form-select" required>
                        <option value="" disabled>Choisir un traitement</option>
                        @foreach($treatments as $treatment)
                            <option value="{{ $treatment->id }}" data-price="{{ $treatment->price }}">
                                {{ $treatment->name }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control treatment-price" readonly>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-treatment-row">Supprimer</button>
                </td>
            </tr>
        `;
        tableBody.insertAdjacentHTML('beforeend', newRow);
    });

    document.addEventListener('change', function (e) {
        if (e.target && e.target.classList.contains('product-select')) {
            const row = e.target.closest('tr');
            const selectedOption = e.target.options[e.target.selectedIndex];
            row.querySelector('.quantity-input').value = selectedOption.dataset.quantity || '';
            row.querySelector('.price-input').value = selectedOption.dataset.price || '';
            updateRowTotal(row);
        }
        if (e.target && e.target.closest('select[name^="treatments"]')) {
            const row = e.target.closest('tr');
            const selectedOption = e.target.options[e.target.selectedIndex];
            row.querySelector('.treatment-price').value = selectedOption.dataset.price || '';
        }
    });

    document.addEventListener('input', function (e) {
        if (e.target && (e.target.classList.contains('quantity-input') || e.target.classList.contains('price-input'))) {
            const row = e.target.closest('tr');
            updateRowTotal(row);
        }
    });

    document.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('remove-row')) {
            e.target.closest('tr').remove();
            updateTotalAmount();
        }
        if (e.target && e.target.classList.contains('remove-treatment-row')) {
            e.target.closest('tr').remove();
        }
    });
</script>
@endsection