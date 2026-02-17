<table>
    <thead>
    <tr>
        <th colspan="4" style="font-weight: bold; font-size: 14px; text-align: center;">
            Rapport Mensuel - {{ \Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y') }}
        </th>
    </tr>
    <tr></tr>
    <tr>
        <th colspan="4" style="font-weight: bold; background-color: #f0f0f0;">Résumé par Dentiste</th>
    </tr>
    <tr>
        <th style="font-weight: bold;">Dentiste</th>
        <th style="font-weight: bold;">Nombre de traitements</th>
        <th style="font-weight: bold;">Montant Total</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach($revenueByDentist as $stat)
        <tr>
            <td>{{ $stat['name'] }}</td>
            <td>{{ $stat['count'] }}</td>
            <td>{{ $stat['total'] }}</td>
            <td></td>
        </tr>
    @endforeach
    <tr></tr>
    <tr>
        <th colspan="4" style="font-weight: bold; background-color: #f0f0f0;">Détails des Traitements</th>
    </tr>
    <tr>
        <th style="font-weight: bold;">Date</th>
        <th style="font-weight: bold;">Patient</th>
        <th style="font-weight: bold;">Dentiste</th>
        <th style="font-weight: bold;">Montant</th>
    </tr>
    @foreach($treatments as $treatment)
        <tr>
            <td>{{ $treatment->date->format('d/m/Y') }}</td>
            <td>{{ $treatment->patient->full_name }}</td>
            <td>{{ $treatment->dentist ? ($treatment->dentist->user->full_name ?? 'Dentiste #' . $treatment->dentist->id) : 'Non assigné' }}</td>
            <td>{{ $treatment->applied_price }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
