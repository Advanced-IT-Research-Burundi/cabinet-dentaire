<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rapport Mensuel</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 12px; }
        th { background-color: #f2f2f2; }
        h1 { text-align: center; color: #333; }
        h2 { color: #555; border-bottom: 2px solid #eee; padding-bottom: 5px; }
        .summary-box { background: #f9f9f9; padding: 15px; border: 1px solid #ddd; margin-bottom: 20px; }
        .text-right { text-align: right; }
        .total-row { font-weight: bold; background-color: #e9ecef; }
    </style>
</head>
<body>
    <h1>Rapport Mensuel - {{ \Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y') }}</h1>

    <div class="summary-box">
        <p><strong>Total Revenu:</strong> {{ number_format($totalRevenue, 0, ',', ' ') }} FBU</p>
        <p><strong>Total Traitements:</strong> {{ $treatments->count() }}</p>
    </div>

    <h2>Performance par Dentiste</h2>
    <table>
        <thead>
            <tr>
                <th>Dentiste</th>
                <th>Nombre de traitements</th>
                <th class="text-right">Montant Total (FBU)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($revenueByDentist as $stat)
            <tr>
                <td>{{ $stat['name'] }}</td>
                <td>{{ $stat['count'] }}</td>
                <td class="text-right">{{ number_format($stat['total'], 0, ',', ' ') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Détail des Traitements</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Patient</th>
                <th>Dentiste</th>
                <th>Type</th>
                <th class="text-right">Montant (FBU)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($treatments as $treatment)
            <tr>
                <td>{{ $treatment->date->format('d/m/Y') }}</td>
                <td>{{ $treatment->patient->full_name }}</td>
                <td>{{ $treatment->dentist ? ($treatment->dentist->user->full_name ?? 'Dentiste #' . $treatment->dentist->id) : 'Non assigné' }}</td>
                <td>
                    @foreach($treatment->treatmentTypes as $type)
                        {{ $type->name }}<br>
                    @endforeach
                </td>
                <td class="text-right">{{ number_format($treatment->applied_price, 0, ',', ' ') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
