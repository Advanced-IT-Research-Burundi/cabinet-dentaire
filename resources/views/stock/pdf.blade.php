<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport Stock - {{ date('d/m/Y') }}</title>
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
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #0d6efd;
            margin: 0;
            font-size: 24px;
        }
        .header .date {
            color: #666;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #0d6efd;
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
        .badge-success { background-color: #28a745; }
        .badge-warning { background-color: #ffc107; color: #000; }
        .badge-danger { background-color: #dc3545; }
        .badge-info { background-color: #17a2b8; }
        .summary {
            margin-top: 30px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .summary h3 {
            margin-top: 0;
            color: #0d6efd;
        }
        .summary-item {
            margin: 5px 0;
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
    </style>
</head>
<body>
    <div class="header">
        <h1>Rapport de Stock</h1>
        <div class="date">Généré le {{ date('d/m/Y à H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Produit</th>
                <th>Marque</th>
                <th>Catégorie</th>
                <th>Qté Stock</th>
                <th>Qté Alerte</th>
                <th>Prix HTVA</th>
                <th>Prix TTC</th>
                <th>Valeur Totale</th>
                <th>Statut</th>
                <th>Expiration</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalQuantite = 0;
                $totalValeur = 0;
                $stockDisponible = 0;
                $stockFaible = 0;
                $stockRupture = 0;
                $stockExpire = 0;
            @endphp

            @foreach($stocks as $stock)
                @php
                    $totalQuantite += $stock->quantite;
                    $totalValeur += ($stock->quantite * $stock->price);

                    switch($stock->status) {
                        case 'Disponible': $stockDisponible++; break;
                        case 'Faible_stock': $stockFaible++; break;
                        case 'En_rupture': $stockRupture++; break;
                        case 'Expire': $stockExpire++; break;
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $stock->id }}</td>
                    <td>{{ $stock->product_name }}</td>
                    <td>{{ $stock->marque ?? '-' }}</td>
                    <td>{{ $stock->category->name ?? 'Non spécifiée' }}</td>
                    <td class="text-center">{{ number_format($stock->quantite, 0) }}</td>
                    <td class="text-center">{{ number_format($stock->quantite_alert, 0) }}</td>
                    <td class="text-right">{{ number_format($stock->price, 0, ',', ' ') }} FBU</td>
                    <td class="text-right">{{ number_format($stock->price_ttc, 0, ',', ' ') }} FBU</td>
                    <td class="text-right">{{ number_format($stock->quantite * $stock->price, 0, ',', ' ') }} FBU</td>
                    <td class="text-center">
                        @switch($stock->status)
                            @case('Disponible')
                                <span class="badge badge-success">En stock</span>
                                @break
                            @case('Faible_stock')
                                <span class="badge badge-warning">Stock faible</span>
                                @break
                            @case('En_rupture')
                                <span class="badge badge-danger">Rupture</span>
                                @break
                            @case('Expire')
                                <span class="badge badge-info">Expiré</span>
                                @break
                        @endswitch
                    </td>
                    <td class="text-center">
                        {{ $stock->date_expiration ? $stock->date_expiration->format('d/m/Y') : '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4" class="text-right"><strong>TOTAUX:</strong></td>
                <td class="text-center"><strong>{{ number_format($totalQuantite, 0) }}</strong></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="text-right"><strong>{{ number_format($totalValeur, 0, ',', ' ') }} FBU</strong></td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>

    <div class="summary">
        <h3>Résumé du Stock</h3>
        <div class="summary-item">
            <strong>Total des produits:</strong> {{ $stocks->count() }}
        </div>
        <div class="summary-item">
            <strong>Quantité totale:</strong> {{ number_format($totalQuantite, 0) }} unités
        </div>
        <div class="summary-item">
            <strong>Valeur totale:</strong> {{ number_format($totalValeur, 0, ',', ' ') }} FBU
        </div>
        <div class="summary-item">
            <strong>Répartition par statut:</strong>
            <ul style="margin: 5px 0;">
                <li>Stock disponible: {{ $stockDisponible }} produits</li>
                <li>Stock faible: {{ $stockFaible }} produits</li>
                <li>En rupture: {{ $stockRupture }} produits</li>
                <li>Expirés: {{ $stockExpire }} produits</li>
            </ul>
        </div>
    </div>

    <div class="footer">
        Rapport généré automatiquement - {{ config('app.name') }}
    </div>
</body>
</html>
