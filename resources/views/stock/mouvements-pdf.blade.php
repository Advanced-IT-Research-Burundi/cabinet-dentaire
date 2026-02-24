<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport Mouvements Stock - {{ date('d/m/Y') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #198754;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #198754;
            margin: 0;
            font-size: 24px;
        }
        .header .date {
            color: #666;
            margin-top: 5px;
        }
        .header .periode {
            color: #333;
            margin-top: 5px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #198754;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            color: white;
            font-size: 8px;
            font-weight: bold;
        }
        .badge-entree { background-color: #198754; }
        .badge-sortie { background-color: #dc3545; }
        .summary {
            margin-top: 30px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .summary h3 {
            margin-top: 0;
            color: #198754;
        }
        .summary-item {
            margin: 5px 0;
        }
        .summary-row {
            display: table;
            width: 100%;
        }
        .summary-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 10px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .total-row {
            background-color: #e9ecef !important;
            font-weight: bold;
        }
        .positive { color: #198754; }
        .negative { color: #dc3545; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rapport des Mouvements de Stock</h1>
        <div class="date">Genere le {{ date('d/m/Y a H:i') }}</div>
        <div class="periode">Periode: Du {{ \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}</div>
        @if($typeMouvement !== 'all')
            <div class="periode">Type: {{ $typeMouvement === 'entrees' ? 'Entrees uniquement' : 'Sorties uniquement' }}</div>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Code</th>
                <th>Designation</th>
                <th>Type</th>
                <th>Quantite</th>
                <th>Unite</th>
                <th>Prix Unit.</th>
                <th>Valeur</th>
                <th>Reference</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalEntrees = 0;
                $totalSorties = 0;
                $valeurEntrees = 0;
                $valeurSorties = 0;
                $mouvementTypes = \App\Models\MouvementStock::MOUVEMENT_TYPES;
            @endphp

            @foreach($mouvements as $mouvement)
                @php
                    $isEntree = str_starts_with($mouvement->item_movement_type, 'E');
                    $valeur = ($mouvement->item_quantity ?? 0) * ($mouvement->item_purchase_or_sale_price ?? 0);

                    if ($isEntree) {
                        $totalEntrees += $mouvement->item_quantity ?? 0;
                        $valeurEntrees += $valeur;
                    } else {
                        $totalSorties += $mouvement->item_quantity ?? 0;
                        $valeurSorties += $valeur;
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $mouvement->item_movement_date ? date('d/m/Y', strtotime($mouvement->item_movement_date)) : '-' }}</td>
                    <td>{{ $mouvement->item_code ?? '-' }}</td>
                    <td>{{ $mouvement->item_designation }}</td>
                    <td class="text-center">
                        <span class="badge {{ $isEntree ? 'badge-entree' : 'badge-sortie' }}">
                            {{ $mouvementTypes[$mouvement->item_movement_type] ?? $mouvement->item_movement_type }}
                        </span>
                    </td>
                    <td class="text-center {{ $isEntree ? 'positive' : 'negative' }}">
                        {{ $isEntree ? '+' : '-' }}{{ number_format($mouvement->item_quantity ?? 0, 0) }}
                    </td>
                    <td class="text-center">{{ $mouvement->item_measurement_unit ?? '-' }}</td>
                    <td class="text-right">{{ number_format($mouvement->item_purchase_or_sale_price ?? 0, 0, ',', ' ') }}</td>
                    <td class="text-right">{{ number_format($valeur, 0, ',', ' ') }}</td>
                    <td>{{ $mouvement->item_movement_invoice_ref ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4" class="text-right"><strong>TOTAUX:</strong></td>
                <td class="text-center">{{ number_format($mouvements->count(), 0) }} mvts</td>
                <td></td>
                <td></td>
                <td class="text-right"><strong>{{ number_format($valeurEntrees - $valeurSorties, 0, ',', ' ') }} FBU</strong></td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="summary">
        <h3>Resume des Mouvements</h3>
        <div class="summary-row">
            <div class="summary-col">
                <div class="summary-item">
                    <strong>Total mouvements:</strong> {{ $mouvements->count() }}
                </div>
                <div class="summary-item">
                    <strong>Entrees:</strong> <span class="positive">+{{ number_format($totalEntrees, 0) }} unites</span>
                </div>
                <div class="summary-item">
                    <strong>Sorties:</strong> <span class="negative">-{{ number_format($totalSorties, 0) }} unites</span>
                </div>
                <div class="summary-item">
                    <strong>Solde net:</strong>
                    <span class="{{ ($totalEntrees - $totalSorties) >= 0 ? 'positive' : 'negative' }}">
                        {{ ($totalEntrees - $totalSorties) >= 0 ? '+' : '' }}{{ number_format($totalEntrees - $totalSorties, 0) }} unites
                    </span>
                </div>
            </div>
            <div class="summary-col">
                <div class="summary-item">
                    <strong>Valeur entrees:</strong> <span class="positive">{{ number_format($valeurEntrees, 0, ',', ' ') }} FBU</span>
                </div>
                <div class="summary-item">
                    <strong>Valeur sorties:</strong> <span class="negative">{{ number_format($valeurSorties, 0, ',', ' ') }} FBU</span>
                </div>
                <div class="summary-item">
                    <strong>Valeur nette:</strong>
                    <span class="{{ ($valeurEntrees - $valeurSorties) >= 0 ? 'positive' : 'negative' }}">
                        {{ number_format($valeurEntrees - $valeurSorties, 0, ',', ' ') }} FBU
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        Rapport genere automatiquement - {{ config('app.name') }}
    </div>
</body>
</html>
