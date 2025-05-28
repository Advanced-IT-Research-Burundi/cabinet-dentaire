@php
    $lowStocks = \App\Models\Stock::lowStock()->with('category')->get();
    $totalNotifications = auth()->user()->notifications->where('read_at', null)->count() + $lowStocks->count();
@endphp

<li class="nav-item dropdown me-3">
    <a class="nav-link notification-bell position-relative" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-bell-fill fs-5"></i>
        @if($totalNotifications > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ $totalNotifications > 99 ? '99+' : $totalNotifications }}
                <span class="visually-hidden">notifications non lues</span>
            </span>
        @endif
    </a>

    <ul class="dropdown-menu dropdown-menu-end notification-dropdown shadow-lg border-0" aria-labelledby="notificationsDropdown" style="min-width: 380px; max-height: 500px; overflow-y: auto;">
        <!-- Header -->
        <li class="p-0">
            <div class="notification-header bg-primary text-white  d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <i class="bi bi-bell me-2"></i>
                    <span class="fw-semibold">Notifications</span>
                </div>
                @if($totalNotifications > 0)
                    <span class="badge bg-light text-primary rounded-pill">{{ $totalNotifications }}</span>
                @endif
            </div>
        </li>

        <!-- Alertes Stock Faible -->
        @if($lowStocks->count() > 0)
            <li>
                <div class="px-3 bg-warning bg-opacity-10 border-bottom">
                    <div class="d-flex align-items-center text-warning">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <span class="fw-semibold small">{{ $lowStocks->count() }} Produit(s) en stock faible</span>
                    </div>
                </div>
            </li>

            @foreach($lowStocks->take(2) as $stock)
                <li class="notification-item border-start border-warning border-3 bg-light bg-opacity-50">
                    <div class="px-3">
                        <div class="d-flex align-items-start">
                            <div class="me-3 mt-1">
                                <div class="bg-warning bg-opacity-20 rounded-circle p-2">
                                    <i class="bi bi-box-seam text-warning"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold text-dark mb-1">
                                    {{ $stock->product_name }}
                                    <span class="badge bg-secondary ms-1">{{ $stock->code_product }}</span>
                                </div>
                                <div class="small text-muted mb-1">
                                    @if($stock->category)
                                        <i class="bi bi-tag me-1"></i>{{ $stock->category->name }}
                                    @endif
                                    @if($stock->marque)
                                        • <i class="bi bi-award me-1"></i>{{ $stock->marque }}
                                    @endif
                                </div>
                                {{-- <div class="d-flex justify-content-between align-items-center">
                                    <div class="small">
                                        <span class="text-danger fw-bold">{{ $stock->quantite }} {{ $stock->unite_mesure ?? 'unités' }}</span>
                                        <span class="text-muted">/ Seuil: {{ $stock->quantite_alert }}</span>
                                    </div>
                                    <div class="small text-muted">
                                        <i class="bi bi-geo-alt me-1"></i>{{ $stock->location ?? 'N/A' }}
                                    </div>
                                </div> --}}
                                {{-- @if($stock->date_expiration && $stock->date_expiration < now()->addDays(30))
                                    <div class="small text-danger mt-1">
                                        <i class="bi bi-calendar-x me-1"></i>Expire: {{ \Carbon\Carbon::parse($stock->date_expiration)->format('d/m/Y') }}
                                    </div>
                                @endif --}}
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach

            @if($lowStocks->count() > 2)
                <li class="text-center py-2 bg-light">
                    <small class="text-muted">
                        <i class="bi bi-three-dots"></i>
                        {{ $lowStocks->count() - 2 }} autre(s) produit(s) en stock faible
                    </small>
                </li>
            @endif

            <li><hr class="dropdown-divider m-0"></li>
        @endif

        <!-- Notifications Utilisateur -->
        @if(auth()->user()->notifications->count() > 0)
            @if($lowStocks->count() > 0)
                <li>
                    <div class="px-3 py-2 bg-info bg-opacity-10">
                        <div class="d-flex align-items-center text-info">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            <span class="fw-semibold small">Autres notifications</span>
                        </div>
                    </div>
                </li>
            @endif

            @foreach(auth()->user()->notifications->take(2) as $notification)
                <li class="notification-item {{ $notification->read_at ? '' : 'unread' }}">
                    <div class="px-3 py-2">
                        <div class="d-flex align-items-start">
                            <div class="me-3 mt-1">
                                <div class="bg-primary bg-opacity-20 rounded-circle p-2">
                                    <i class="bi bi-info-circle text-primary"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold text-dark mb-1">
                                    {{ $notification->data['message'] ?? 'Nouvelle notification' }}
                                </div>
                                <div class="text-muted small">
                                    <i class="bi bi-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}
                                </div>
                            </div>
                            @if(!$notification->read_at)
                                <div class="ms-2">
                                    <span class="badge bg-primary rounded-pill">&nbsp;</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </li>
            @endforeach

            <li><hr class="dropdown-divider m-0"></li>
        @endif

        <!-- Actions -->
        @if ($lowStocks->count() > 0 )
            <li class="px-3 py-2 bg-light">
                <div class="d-flex justify-content-center gap-2">
                    <a class="btn btn-outline-primary btn-sm" href="{{ route('invoice.alert') }}">
                        <i class="bi bi-list me-1"></i>Voir tout
                    </a>
                </div>
            </li>
        @endif


        <!-- Message vide -->
        @if($totalNotifications == 0)
            <li class="notification-item text-center py-4">
                <div class="text-center">
                    <i class="bi bi-bell-slash text-muted" style="font-size: 3rem;"></i>
                    <div class="text-muted mt-2">Aucune notification</div>
                    <div class="small text-muted">Tout est à jour !</div>
                </div>
            </li>
        @endif
    </ul>
</li>

<style>
.notification-dropdown {
    border-radius: 12px !important;
}

.notification-header {
    border-radius: 12px 12px 0 0 !important;
}

.notification-item {
    transition: all 0.2s ease;
    border: none !important;
}

.notification-item:hover {
    background-color: rgba(0,123,255,0.05) !important;
    transform: translateX(2px);
}

.notification-item.unread {
    background-color: rgba(0,123,255,0.02);
    border-left: 3px solid #007bff !important;
}

.notification-bell {
    transition: all 0.2s ease;
}

.notification-bell:hover {
    transform: scale(1.1);
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.notification-bell .badge {
    animation: pulse 2s infinite;
}

.bg-opacity-5 { background-color: rgba(var(--bs-primary-rgb), 0.05) !important; }
.bg-opacity-10 { background-color: rgba(var(--bs-primary-rgb), 0.1) !important; }
.bg-opacity-20 { background-color: rgba(var(--bs-primary-rgb), 0.2) !important; }
</style>
