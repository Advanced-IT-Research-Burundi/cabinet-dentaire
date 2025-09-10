<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture BuDental Services | {{ $invoice->invoice_number }}</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        @page :thermal {
            size: 80mm auto;
            margin: 2mm;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-color: white;
        }

        /* Styles pour impression normale */
        .invoice-container {
            width: 210mm;
            height: 297mm;
            padding: 15mm;
            position: relative;
            box-sizing: border-box;
        }

        /* Styles pour impression thermique */
        .thermal-invoice {
            display: none;
            width: 76mm;
            padding: 2mm;
            font-size: 10px;
            line-height: 1.2;
        }

        .thermal-header {
            text-align: center;
            margin-bottom: 8px;
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
        }

        .thermal-company {
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 3px;
        }

        .thermal-info {
            font-size: 8px;
            margin-bottom: 2px;
        }

        .thermal-section {
            margin: 8px 0;
            padding: 3px 0;
        }

        .thermal-section-title {
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 3px;
        }

        .thermal-row {
            display: flex;
            justify-content: space-between;
            margin: 1px 0;
        }

        .thermal-item {
            border-bottom: 1px dotted #ccc;
            padding: 2px 0;
            margin: 2px 0;
        }

        .thermal-item-name {
            font-weight: bold;
            font-size: 9px;
        }

        .thermal-item-details {
            display: flex;
            justify-content: space-between;
            font-size: 8px;
        }

        .thermal-total {
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            padding: 3px 0;
            margin: 5px 0;
            font-weight: bold;
            text-align: center;
        }

        .thermal-footer {
            text-align: center;
            font-size: 8px;
            margin-top: 8px;
            border-top: 1px dashed #000;
            padding-top: 5px;
        }

        /* Styles originaux conservés */
        .logo-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo-img {
            width: 40px;
            height: 40px;
            background-color: #51a7e0;
            border-radius: 5px;
            position: relative;
            margin-right: 10px;
        }

        .logo-img::before {
            content: "";
            position: absolute;
            width: 25px;
            height: 25px;
            border: 3px solid white;
            border-radius: 50%;
            top: 5px;
            left: 5px;
        }

        .company-name {
            color: #51a7e0;
            font-weight: bold;
            font-size: 24px;
        }

        .company-info {
            margin-top: -30px;
            font-size: 12px;
            line-height: 1.4;
        }

        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .invoice-box {
            border: 1px solid #0a3d71;
            padding: 5px 10px;
            width: 48%;
            background-color: #e9f5ff;
        }

        .invoice-box-title {
            background-color: #0a3d71;
            color: white;
            padding: 3px 8px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 5px;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .invoice-table th {
            background-color: #0a3d71;
            color: white;
            text-align: left;
            padding: 8px;
        }

        .invoice-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .totals {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
        }

        .totals-table {
            width: 300px;
            border-collapse: collapse;
        }

        .totals-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .totals-table .total-row {
            background-color: #e9f5ff;
            font-weight: bold;
        }

        .bank-info {
            margin-top: 20px;
            width: 48%;
        }

        .bank-table {
            width: 100%;
            border-collapse: collapse;
        }

        .bank-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .bank-title {
            background-color: #0a3d71;
            color: white;
            padding: 3px 8px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 5px;
        }

        .approval {
            margin-top: 20px;
        }

        .approval-title {
            background-color: #0a3d71;
            color: white;
            padding: 3px 8px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 5px;
        }

        .signature-line {
            border-bottom: 1px dotted #000;
            display: inline-block;
            width: 200px;
            margin-left: 10px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
        }

        .dental-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.1;
            pointer-events: none;
            z-index: -1;
            overflow: hidden;
        }

        .dental-icon {
            position: absolute;
            color: #51a7e0;
            font-size: 30px;
        }

        .print-button {
            padding: 10px 15px;
            background-color: #0a3d71;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            z-index: 1000;
            margin-right: 10px;
        }

        .thermal-print-button {
            background-color: #28a745;
        }

        .print-show{
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .invoice{
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        @media print {
            .print-button, .thermal-print-button {
                display: none !important;
            }

            .print-show {
                display: none !important;
            }

            /* Mode impression normale */
            body.normal-print .thermal-invoice {
                display: none !important;
            }

            body.normal-print .invoice-container {
                display: block !important;
            }

            /* Mode impression thermique */
            body.thermal-print .invoice-container {
                display: none !important;
            }

            body.thermal-print .thermal-invoice {
                display: block !important;
            }

            body.thermal-print .invoice {
                display: block;
                min-height: auto;
            }

            body.thermal-print {
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="print-show">
        <a href="{{ route('invoices.index') }}" class="btn btn-primary print-button">← Retour à la liste des factures</a>
        <button class="print-button" onclick="printNormal()">🖨️ Imprimer Normal</button>
        <button class="print-button thermal-print-button" onclick="printThermal()">🧾 Imprimer Thermique</button>
    </div>

    <!-- Version normale -->
    <div class="invoice">
        <div class="invoice-container">
            <div class="logo-header">
                <div class="logo">
                        <div class="">
                            <img src="{{ asset('img/logo.png') }}" width="70" alt="Logo Cabinet Dentaire">
                        </div>
                    <div class="company-name">{{ $invoice->company['tp_name'] }}</div>
                </div>
                <div class="company-info">
                    NIF: <span>{{ $invoice->company['tp_TIN'] }}</span><br>
                    RC: <span>{{ $invoice->company['tp_trade_number'] }}</span><br>
                    Tél: <span>{{ $invoice->company['tp_phone_number'] }}</span><br>
                    Email: <span>{{ $invoice->company['tp_email'] }}</span><br>
                    Adresse: <span>{{ $invoice->company['tp_address_avenue'] }}, {{ $invoice->company['tp_address_quartier'] }}, {{ $invoice->company['tp_address_commune'] }}</span><br>
                    <span>{{ $invoice->company['tp_address_privonce'] }}</span>, Burundi <br>
                    Type de Facture : <span>FN</span> <br>
                    Exonerer à la TVA : <span>OUI</span> <br>
                </div>
            </div>

            <div class="invoice-info">
                <div class="invoice-box">
                    <div class="invoice-box-title">Patient (Client)</div>
                    <table style="width: 100%">
                        <tr>
                            <td>Nom</td>
                            <td>  {{ $invoice->client['customer_name'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <td>Adresse</td>
                            <td> {{ $invoice->client['customer_address'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <td>Téléphone</td>
                            <td>{{ $invoice->client['customer_phone'] ?? '' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="invoice-box">
                    <div class="invoice-box-title">FACTURE</div>
                    <table style="width: 100%">
                        <tr>
                            <td>N°</td>
                            <td>{{ $invoice->invoice_number }}</td>
                        </tr>
                        <tr>
                            <td>DATE</td>
                            <td>{{ $invoice->issue_date }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <table class="invoice-table">
                <thead>
                    <tr>
                        <th>DESCRIPTION DES SERVICES</th>
                        <th>UNITES</th>
                        <th>PRIX UNITAIRE</th>
                        <th>MONTANT</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($invoice->description as $item)
                <tr>
                    <td>{{ $item['item_designation'] ?? '-' }}</td>
                    <td>{{ $item['item_quantity'] ?? '-' }}</td>
                    <td>{{ number_format($item['item_price'] ?? 0, 2, ',', ' ') }}</td>
                    <td>{{ number_format($item['item_total_amount'] ?? 0, 2, ',', ' ') }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>

            <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                <div class="bank-info">
                    <div class="bank-title">BANQUE</div>
                    <table class="bank-table">
                        <tr>
                            <td>COMPTE #</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>NOM DE LA BANQUE</td>
                            <td></td>
                        </tr>
                    </table>
                </div>

                <div class="totals">
                    <table class="totals-table">
                        <tr>
                            <td>SOUS-TOTAL</td>
                            <td>{{ number_format($invoice->total_amount, 2, ',', ' ') }}</td>
                        </tr>
                        <tr>
                            <td>TVA</td>
                            <td>{{ number_format($invoice->tax_amount, 2, ',', ' ') }}</td>
                        </tr>
                        <tr>
                            <td>Montant de la TVA</td>
                            <td>{{ number_format($invoice->tax_amount, 2, ',', ' ') }}</td>
                        </tr>
                        <tr class="total-row">
                            <td>TOTAL</td>
                            <td>{{ number_format($invoice->total_amount, 2, ',', ' ') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="approval" style="margin-top: 30px;">
                <div class="approval-title">APPROUVÉ PAR</div>
                <div>
                    Nom <span class="signature-line"></span>
                </div>
                <div style="margin-top: 10px;">
                    Signature <span class="signature-line"></span>
                </div>
            </div>

            <div class="footer">
                <p>Merci pour votre collaboration !</p>
                <p>Si vous avez des questions concernant cette facture, veuillez contacter</p>
                <p>+257 79364090, budentalservices@gmail.com</p>
                <p>ID : <b>{{ $invoice->invoice_identifier }}</b></p>
            </div>
        </div>
    </div>

    <!-- Version thermique -->
    <div class="thermal-invoice">
        <div class="thermal-header">
            <div class="thermal-company">{{ $invoice->company['tp_name'] }}</div>
            <div class="thermal-info">NIF: {{ $invoice->company['tp_TIN'] }}</div>
            <div class="thermal-info">RC: {{ $invoice->company['tp_trade_number'] }}</div>
            <div class="thermal-info">Tel: {{ $invoice->company['tp_phone_number'] }}</div>
            <div class="thermal-info">{{ $invoice->company['tp_address_avenue'] }}, {{ $invoice->company['tp_address_quartier'] }}, {{ $invoice->company['tp_address_commune'] }}</div>
            <div class="thermal-info">{{ $invoice->company['tp_address_privonce'] }}, Burundi</div>
        </div>

        <div class="thermal-section">
            <div class="thermal-section-title">FACTURE</div>
            <div class="thermal-row">
                <span>N°:</span>
                <span>{{ $invoice->invoice_number }}</span>
            </div>
            <div class="thermal-row">
                <span>Date:</span>
                <span>{{ $invoice->issue_date }}</span>
            </div>
        </div>

        <div class="thermal-section">
            <div class="thermal-section-title">PATIENT</div>
            <div>{{ $invoice->client['customer_name'] ?? '' }}</div>
            <div class="thermal-info">{{ $invoice->client['customer_address'] ?? '' }}</div>
            <div class="thermal-info">{{ $invoice->client['customer_phone'] ?? '' }}</div>
        </div>

        <div class="thermal-section">
            <div class="thermal-section-title">SERVICES</div>


            @foreach($invoice->description as $item)
            <div class="thermal-item">
                <div class="thermal-item-name">{{ $item['item_designation'] ?? '-' }}</div>
                <div class="thermal-item-details">
                    <span>{{ $item['item_quantity'] ?? '-' }} x {{ number_format($item['item_price'] ?? 0, 2, ',', ' ') }}</span>
                    <span>{{ number_format($item['item_total_amount'] ?? 0, 2, ',', ' ') }}</span>
                </div>
            </div>
            @endforeach


        </div>

        <div class="thermal-section">
            <div class="thermal-row">
                <span>Sous-total:</span>
                <span>{{ number_format($invoice->total_amount, 2, ',', ' ') }}</span>
            </div>
            <div class="thermal-row">
                <span>TVA (0%):</span>
                <span>{{ number_format($invoice->tax_amount, 2, ',', ' ') }}</span>
            </div>
            <div class="thermal-total">
                TOTAL: {{ number_format($invoice->total_amount, 2, ',', ' ') }}
            </div>
        </div>

        <div class="thermal-section">
            <div class="thermal-section-title">BANQUE</div>
            <div class="thermal-info">COGEBANQUE</div>
            <div class="thermal-info">Compte: {{ $invoice->bank_account }}</div>
        </div>

        <div class="thermal-footer">
            <div>Merci pour votre visite!</div>
            <div>ID: {{ $invoice->invoice_identifier }}</div>
            <div style="margin-top: 8px;">================================</div>
        </div>
    </div>

    <script>
        function printNormal() {
            document.body.className = 'normal-print';
            window.print();
            document.body.className = '';
        }

        function printThermal() {
            document.body.className = 'thermal-print';
            window.print();
            document.body.className = '';
        }
    </script>
</body>
</html>
