@extends('layouts.app')

@section('title', 'Synchronisation des stocks avec OBR')

@section('page-title', 'Synchronisation avec OBR')

@section('breadcrumbs')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tableau de bord</a></li>
            <li class="breadcrumb-item active" aria-current="page">Synchronisation OBR</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-arrow-repeat me-2"></i>Mouvements de stock
                    </h5>

                    <form action="{{ route('stock.syncronisation') }}" method="GET" class="d-flex w-100 w-md-auto" style="max-width: 400px;">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0 bg-light"
                                placeholder="Rechercher (Code, Désignation, Réf)..." value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit">Rechercher</button>
                        </div>
                    </form>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Code</th>
                                    <th>Désignation</th>
                                    <th>Qté</th>
                                    <th>Unité</th>
                                    <th>Prix</th>
                                    <th>Type</th>
                                    <th>Réf. Facture</th>
                                    <th>Date</th>
                                    <th>Statut OBR</th>
                                    <th>Envoyé le</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($mouvements as $mouvement)
                                    <tr>
                                        <td class="ps-4 fw-semibold text-primary">{{ $mouvement->item_code }}</td>
                                        <td>
                                            <div class="fw-medium">{{ $mouvement->item_designation }}</div>
                                            @if($mouvement->item_movement_description)
                                                <small class="text-muted">{{ Str::limit($mouvement->item_movement_description, 30) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                {{ $mouvement->item_quantity }}
                                            </span>
                                        </td>
                                        <td class="small text-muted">{{ $mouvement->item_measurement_unit }}</td>
                                        <td>
                                            <div class="fw-medium">{{ number_format($mouvement->item_purchase_or_sale_price, 0, ',', ' ') }}</div>
                                            <small class="text-muted">{{ $mouvement->item_purchase_or_sale_currency }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ str_starts_with($mouvement->item_movement_type, 'E') ? 'success' : 'danger' }} bg-opacity-10 text-{{ str_starts_with($mouvement->item_movement_type, 'E') ? 'success' : 'danger' }}">
                                                {{ $mouvement->item_movement_type }}
                                            </span>
                                        </td>
                                        <td class="font-monospace small">{{ $mouvement->item_movement_invoice_ref ?? '-' }}</td>
                                        <td class="small">{{ \Carbon\Carbon::parse($mouvement->item_movement_date)->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @if ($mouvement->is_send_to_obr == 1)
                                                <span class="badge bg-success rounded-pill"><i class="bi bi-check-circle me-1"></i>Synchronisé</span>
                                            @else
                                                <span class="badge bg-danger rounded-pill"><i class="bi bi-x-circle me-1"></i>Non Synchronisé</span>
                                            @endif
                                        </td>
                                        <td class="small text-muted">
                                            {{ $mouvement->is_sent_at ? \Carbon\Carbon::parse($mouvement->is_sent_at)->format('d/m/Y H:i') : '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-5">
                                            <div class="text-muted opacity-75">
                                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                                <p class="mb-0">Aucun mouvement trouvé pour cette recherche.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($mouvements->hasPages())
                <div class="card-footer bg-white py-3">
                    <div class="d-flex justify-content-end">
                        {{ $mouvements->withQueryString()->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection
