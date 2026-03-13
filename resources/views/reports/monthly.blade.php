@extends('layouts.app')

@section('title', 'Rapport Mensuel')

@section('page-title', 'Rapport Mensuel')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item active" aria-current="page">Rapport Mensuel</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('reports.monthly') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="month" class="form-label">Mois</label>
                        <select name="month" id="month" class="form-select">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="year" class="form-label">Année</label>
                        <select name="year" id="year" class="form-select">
                            @foreach(range(\Carbon\Carbon::now()->year, \Carbon\Carbon::now()->year - 5) as $y)
                                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="dentist_id" class="form-label">Dentiste</label>
                        <select name="dentist_id" id="dentist_id" class="form-select">
                            <option value="">Tous les dentistes</option>
                            @foreach($dentists as $dentist)
                                <option value="{{ $dentist->id }}" {{ $dentist->id == $dentistId ? 'selected' : '' }}>
                                    {{ $dentist->user->full_name ?? 'Dentiste #' . $dentist->id }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-filter"></i> Filtrer
                        </button>
                        <a href="{{ route('reports.monthly.export.pdf', ['month' => $month, 'year' => $year, 'dentist_id' => $dentistId]) }}" class="btn btn-danger" target="_blank">
                            <i class="bi bi-file-pdf"></i> PDF
                        </a>
                        <a href="{{ route('reports.monthly.export.excel', ['month' => $month, 'year' => $year, 'dentist_id' => $dentistId]) }}" class="btn btn-success" target="_blank">
                            <i class="bi bi-file-excel"></i> Excel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card text-white bg-primary h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Revenu Total</h6>
                        <h2 class="mt-2 mb-0">{{ number_format($totalRevenue, 0, ',', ' ') }} FBU</h2>
                    </div>
                    <i class="bi bi-cash-stack fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card text-white bg-info h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Nombre de Traitements</h6>
                        <h2 class="mt-2 mb-0">{{ $totalTreatments }}</h2>
                    </div>
                    <i class="bi bi-clipboard-pulse fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-5 mb-4">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0">Revenue par Dentiste</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Dentiste</th>
                                <th class="text-center">Actes</th>
                                <th class="text-end">Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($revenueByDentist as $stat)
                            <tr>
                                <td>{{ $stat['name'] }}</td>
                                <td class="text-center">{{ $stat['count'] }}</td>
                                <td class="text-end fw-bold">{{ number_format($stat['total'], 0, ',', ' ') }} FBU</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center py-3">Aucune donnée disponible</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-7 mb-4">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0">Historique des Traitements</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Patient</th>
                                <th>Dentiste</th>
                                <th class="text-end">Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($treatments as $treatment)
                            <tr>
                                <td>{{ $treatment?->date?->format('d/m/Y') }}</td>
                                <td>
                                    <div class="fw-bold">{{ $treatment?->patient?->full_name }}</div>
                                    <small class="text-muted">{{ $treatment?->treatmentTypes?->pluck('name')->join(', ') }}</small>
                                </td>
                                <td>{{ $treatment?->dentist ? ($treatment?->dentist?->user?->full_name ?? 'Dentiste #' . $treatment?->dentist?->id) : 'Non assigné' }}</td>
                                <td class="text-end">{{ number_format($treatment?->applied_price, 0, ',', ' ') }} FBU</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-3">Aucun traitement trouvé pour cette période</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
