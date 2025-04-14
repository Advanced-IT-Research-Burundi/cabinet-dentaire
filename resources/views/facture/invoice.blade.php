<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin: 2cm;
        }
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .company-info {
            text-align: right;
            margin-bottom: 20px;
        }
        .invoice-info {
            margin-bottom: 30px;
        }
        .invoice-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .invoice-info th, .invoice-info td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .invoice-info th {
            background-color: #f5f5f5;
        }
        .items {
            margin-top: 20px;
        }
        .items table {
            width: 100%;
            border-collapse: collapse;
        }
        .items th, .items td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        .items th {
            background-color: #f5f5f5;
        }
        .total {
            margin-top: 20px;
            text-align: right;
        }
        .total table {
            width: 100%;
            border-collapse: collapse;
        }
        .total th, .total td {
            padding: 12px;
            text-align: right;
            border-top: 2px solid #000;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>FACTURE</h1>
        <h2>N° {{ $invoice->invoice_number }}</h2>
    </div>

    <div class="company-info">
        <h3>{{ $invoice->company['tp_name'] }}</h3>
        <p>
            {{ $invoice->company['tp_address_privonce'] }}<br>
            {{ $invoice->company['tp_address_avenue'] }}, {{ $invoice->company['tp_address_quartier'] }}<br>
            {{ $invoice->company['tp_address_commune'] }}, {{ $invoice->company['tp_address_number'] }}<br>
            Tél: {{ $invoice->company['tp_phone_number'] }}<br>
            TIN: {{ $invoice->company['tp_TIN'] }}
        </p>
    </div>

    <div class="invoice-info">
        <table>
            <tr>
                <th>Date d'émission</th>
                <th>Client</th>
            </tr>
            <tr>
                <td>{{ \Carbon\Carbon::parse($invoice->issue_date)->format('d/m/Y') }}</td>
                <td>{{ $invoice->client['first_name'] }} {{ $invoice->client['last_name'] }}</td>
            </tr>
            <tr>
                <td>Date d'échéance</td>
                <td>{{ $invoice->client['phone'] }}</td>
            </tr>
            <tr>
                <td>{{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}</td>
                <td>{{ $invoice->client['address'] }}</td>
            </tr>
        </table>
    </div>

    <div class="items">
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Dentiste</th>
                    <th>Prix</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->description as $item)
                <tr>
                    <td>{{ $item['treatmentType'] }}</td>
                    <td>{{ $item['dentist'] }}</td>
                    <td>{{ number_format($item['applied_price'], 2, ',', ' ') }} FCFA</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="total">
        <table>
            <tr>
                <th colspan="2">Total</th>
                <td>{{ number_format($invoice->total_amount, 2, ',', ' ') }} FCFA</td>
            </tr>
            <tr>
                <th colspan="2">Montant assurance</th>
                <td>{{ number_format($invoice->insurance_amount, 2, ',', ' ') }} FCFA</td>
            </tr>
            <tr>
                <th colspan="2">Montant à payer</th>
                <td>{{ number_format($invoice->patient_amount, 2, ',', ' ') }} FCFA</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Merci de votre confiance. Pour toute question, n'hésitez pas à nous contacter.</p>
    </div>
</body>
</html>