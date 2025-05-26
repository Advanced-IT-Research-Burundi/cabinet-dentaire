<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture BuDental Services</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            background-color: white;
        }

        .invoice-container {
            width: 210mm;
            height: 297mm;
            padding: 15mm;
            position: relative;
            box-sizing: border-box;
        }

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
            margin-top: 15px;
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
        }
        .print-show{
            position: absolute;
            top: 10px;
            right: 10px;

        }

        @media print {
            .print-button {
                display: none;
            }
        }
        .invoice{
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
    </style>
</head>
<body>
    <div class="print-show">
        <a href="{{ url()->previous() }}" class="btn btn-primary print-button"><i class="bi bi-arrow-left"></i> Retour à la liste des factures</a>
        <button class="print-button" onclick="window.print()"><i class="bi bi-printer"></i> Imprimer</button>
    </div>

    <div class="invoice">
    <div class="invoice-container">
        <div class="logo-header">
            <div class="logo">
                <div class="">
                    <img src="{{ asset('img/logo.png') }}" width="70" alt="Logo Cabinet Dentaire">
                </div>
                <div class="company-name">{{ $invoice->company['tp_name'] ?? 'BuDental Services' }}</div>
            </div>
            <div class="company-info">
                NIF: <span>{{ $invoice->company['tp_TIN'] ?? '40001647371' }}</span><br>
                RC: <span>{{ $invoice->company['tp_trade_number'] ?? '29723/21' }}</span><br>
                Tél: <span>{{ $invoice->company['tp_phone_number'] ?? '+257 62 10 63 08 / +257 62 50 50 00' }}</span><br>
                Email: <span>{{ $invoice->company['tp_email'] ?? 'budentalservices@gmail.com' }}</span><br>
                Adresse: <span>{{ $invoice->company['tp_address_avenue'] ?? 'N°12, Ave d\'Italie' }}, {{ $invoice->company['tp_address_quartier'] ?? 'Rohero' }}, {{ $invoice->company['tp_address_commune'] ?? 'Mukaza' }}</span><br>
                <span>{{ $invoice->company['tp_address_privonce'] ?? 'BUJUMBURA-MAIRIE' }}</span>, Burundi
            </div>
        </div>

        <div class="invoice-info">
            <div class="invoice-box">
                <div class="invoice-box-title"> Patient  (Client)</div>
                <table style="width: 100%">
                    <tr>
                        <td>Nom</td>
                        <td>{{ $invoice->client['first_name'] ?? '' }} {{ $invoice->client['middle_name'] ?? '' }} {{ $invoice->client['last_name'] ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>Nom de la société</td>
                        <td>{{ $invoice->client['insurance_company'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Addresse</td>
                        <td>{{ $invoice->client['address'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td>{{ $invoice->client['email'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Téléphone</td>
                        <td>{{ $invoice->client['phone'] ?? 'N/A' }}</td>
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
                        <td>{{ $invoice->issue_date->format('d/m/y') }}</td>
                    </tr>
                    {{-- <tr>
                        <td>ÉCHÉANCE</td>
                        <td>{{ $invoice->due_date->format('d/m/y') }}</td>
                    </tr> --}}
                    {{-- <tr>
                        <td>STATUT</td>
                        <td>{{ $invoice->status }}</td>
                    </tr> --}}
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

                @for($i = count($invoice->description); $i < 6; $i++)
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @endfor
            </tbody>
        </table>

        <div style="display: flex; justify-content: space-between; margin-top: 20px;">
            <div class="bank-info">
                <div class="bank-title">BANQUE</div>
                <table class="bank-table">
                    <tr>
                        <td>COMPTE #</td>
                        <td>{{ $invoice->company['tp_account_number'] ?? '' }}</td>
                    </tr>
                    <tr>
                        <td>NOM DE LA BANQUE</td>
                        <td>{{ $invoice->company['tp_bank'] ?? '' }}</td>
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
                        <td>0,00</td>
                    </tr>
                    <tr>
                        <td>Montant de la TVA</td>
                        <td>0,00</td>
                    </tr>
                    <tr class="total-row">
                        <td>TOTAL</td>
                        <td>{{ number_format($invoice->total_amount, 2, ',', ' ') }} FBu</td>
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
            <p>{{ $invoice->company['tp_phone_number'] ?? '+257 79364090' }}, {{ $invoice->company['tp_email'] ?? 'budentalservices@gmail.com' }}</p>
        </div>

        <!-- Dental background pattern -->
        <div class="dental-pattern">
            <!-- Pattern sera généré avec PHP/CSS -->
            @php
                $icons = ['🦷', '✖', '👄', '⚕️'];
                for ($i = 0; $i < 100; $i++) {
                    $icon = $icons[array_rand($icons)];
                    $left = rand(1, 100);
                    $top = rand(1, 100);
                    $rotate = rand(0, 360);
                    echo "<div class='dental-icon' style='left: {$left}%; top: {$top}%; transform: rotate({$rotate}deg);'>{$icon}</div>";
                }
            @endphp
        </div>
    </div>
    </div>
</body>
</html>
