<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Caisse {{ $caisse->name }} - {{ $dateDebut->format('d/m/Y') }}{{ $dateDebut->isSameDay($dateFin) ? '' : ' au ' . $dateFin->format('d/m/Y') }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #222; margin: 20px; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #0d6efd; padding-bottom: 10px; margin-bottom: 15px; }
        .header img { max-height: 70px; }
        .company h2 { margin: 0 0 4px; font-size: 16px; }
        .company div { line-height: 1.5; }
        h1 { text-align: center; font-size: 18px; margin: 10px 0 4px; text-transform: uppercase; }
        .periode { text-align: center; margin-bottom: 15px; }
        .infos { display: flex; justify-content: space-between; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #999; padding: 5px 6px; text-align: left; }
        th { background: #f0f0f0; }
        td.num, th.num { text-align: right; white-space: nowrap; }
        tfoot th { font-size: 13px; }
        .signatures { display: flex; justify-content: space-between; margin-top: 50px; }
        .signatures div { width: 40%; text-align: center; border-top: 1px solid #222; padding-top: 5px; }
        .actions { text-align: right; margin-bottom: 15px; }
        .actions button { padding: 6px 14px; cursor: pointer; }
        @media print {
            .actions { display: none; }
            body { margin: 0; }
            th { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>

    <div class="actions">
        <button onclick="window.print()">Imprimer</button>
        <button onclick="window.close()">Fermer</button>
    </div>

    <div class="header">
        <div class="company">
            <h2>{{ $company->tp_name ?? config('app.name') }}</h2>
            @if($company)
                <div>
                    @if($company->tp_TIN) NIF : {{ $company->tp_TIN }}<br>@endif
                    @if($company->tp_phone_number) Tél : {{ $company->tp_phone_number }}<br>@endif
                    {{ collect([$company->tp_address_commune, $company->tp_address_quartier, $company->tp_address_avenue])->filter()->implode(', ') }}
                </div>
            @endif
        </div>
        @if($company?->tp_logo)
            <img src="{{ asset('storage/' . $company->tp_logo) }}" alt="Logo">
        @endif
    </div>

    <h1>Rapport de caisse</h1>
    <div class="periode">
        @if($dateDebut->isSameDay($dateFin))
            Journée du <strong>{{ $dateDebut->format('d/m/Y') }}</strong>
        @else
            Période du <strong>{{ $dateDebut->format('d/m/Y') }}</strong> au <strong>{{ $dateFin->format('d/m/Y') }}</strong>
        @endif
    </div>

    <div class="infos">
        <div>
            <strong>Caisse :</strong> {{ $caisse->name }}<br>
            <strong>Responsable :</strong> {{ $caisse->user->name ?? '--' }}
        </div>
        <div style="text-align: right">
            <strong>Imprimé le :</strong> {{ now()->format('d/m/Y à H:i') }}<br>
            <strong>Par :</strong> {{ auth()->user()->name }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Description</th>
                <th>Facture N°</th>
                <th>Utilisateur</th>
                <th class="num">Total (FBU)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($details as $detail)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $detail->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $detail->description ?? '--' }}</td>
                    <td>{{ $detail->type ?? '--' }}</td>
                    <td>{{ $detail->user->name ?? '--' }}</td>
                    <td class="num">{{ number_format($detail->total, 0, ',', ' ') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center">Aucune transaction sur la période sélectionnée</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="num">Total ({{ $details->count() }} transaction{{ $details->count() > 1 ? 's' : '' }}) :</th>
                <th class="num">{{ number_format($details->sum('total'), 0, ',', ' ') }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="signatures">
        <div>Le caissier</div>
        <div>Le responsable</div>
    </div>

    <script>
        window.addEventListener('load', () => window.print());
    </script>
</body>
</html>
