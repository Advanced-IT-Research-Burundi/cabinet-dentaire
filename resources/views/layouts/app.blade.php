<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Budental Services') }} - @yield('title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap-icons-1.13.1/bootstrap-icons.min.css') }}">

    <!-- Custom CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sweetalert2@11.css') }}">

    @stack('styles')
    @livewireStyles
</head>
<body>
    <div class="d-flex flex-column min-vh-100">
        <!-- Navigation principale -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container-fluid">
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                    {{-- <i class="bi bi-hospital me-2 fs-3"></i>
                     --}}
                     <img src="{{ asset('img/logo.png') }}" width="50px" height="50px" alt="">
                    <span class="fw-bold">Budental Services</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarMain">
                    <!-- Menu Gauche -->
                    <ul class="mb-2 navbar-nav me-auto mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2 me-1"></i> Tableau de bord
                            </a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="patientsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-people-fill me-1"></i> Patients
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="patientsDropdown">
                                <li><a class="dropdown-item" href="{{ route('patients.index') }}"><i class="bi bi-list-ul me-1"></i> Liste des patients</a></li>
                                <li><a class="dropdown-item" href="{{ route('patients.create') }}"><i class="bi bi-person-plus-fill me-1"></i> Nouveau patient</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('patients.search') }}"><i class="bi bi-search me-1"></i> Recherche avancée</a></li>
                            </ul>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="rendezVousDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-calendar-check me-1"></i> Rendez-vous
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="rendezVousDropdown">
                                <li><a class="dropdown-item" href="{{ route('rendezvous.calendar') }}"><i class="bi bi-calendar3 me-1"></i> Calendrier</a></li>
                                <li><a class="dropdown-item" href="{{ route('rendezvous.create') }}"><i class="bi bi-plus-circle me-1"></i> Nouveau rendez-vous</a></li>
                                <li><a class="dropdown-item" href="{{ route('rendezvous.today') }}"><i class="bi bi-calendar-day me-1"></i> Rendez-vous du jour</a></li>
                            </ul>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="facturationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-receipt me-1"></i> Facturation
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="facturationDropdown">
                                <li><a class="dropdown-item" href="{{ route('orders.index') }}"><i class="bi bi-list-columns me-1"></i> Liste des factures</a></li>
                                <li><a class="dropdown-item" href="{{ route('invoices.create') }}"><i class="bi bi-file-earmark-plus me-1"></i> Nouvelle facture</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('payments.index') }}"><i class="bi bi-cash-coin me-1"></i> Paiements</a></li>
                                <li><a class="dropdown-item" href=""><i class="bi bi-exclamation-triangle me-1"></i> Factures impayées</a></li>
                                <li><a class="dropdown-item" href="{{ route('invoices.index') }}"><i class="bi bi-check2 me-1"></i> Factures payées</a></li>
                            </ul>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="traitementDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-clipboard2-pulse me-1"></i> Traitements
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="traitementDropdown">
                                <li><a class="dropdown-item" href="{{ route('treatments.create') }}"><i class="bi bi-plus-circle me-1"></i> Nouveau traitement</a></li>
                                <li><a class="dropdown-item" href="{{ route('treatments.index') }}"><i class="bi bi-journal-medical me-1"></i> Historique traitements</a></li>
                                <li><a class="dropdown-item" href="{{ route('settings.treatment-types.index') }}"><i class="bi bi-list-check me-1"></i> Types de traitement</a></li>
                            </ul>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="stocksDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-box-seam me-1"></i> Pharmacies
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="stocksDropdown">
                                <li><a class="dropdown-item" href="{{ route('stocks.index') }}"><i class="bi bi-boxes me-1"></i> Inventaire</a></li>
                                <li><a class="dropdown-item" href="{{ route('categories.index') }}"><i class="bi bi-tag me-1"></i> Categories</a></li>
                                <li><a class="dropdown-item" href=""><i class="bi bi-exclamation-circle me-1"></i> Alertes de stock</a></li>
                                <li><a class="dropdown-item" href=""><i class="bi bi-graph-up me-1"></i> Utilisation</a></li>
                                <li><a class="dropdown-item" href="{{ route('suppliers.index') }}"><i class="bi bi-people me-1"></i> Fournisseurs</a></li>
                            </ul>
                        </li>
                    </ul>

                    <!-- Menu Droite -->
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-bell-fill fs-5 position-relative">
                                    <span class="top-0 position-absolute start-100 translate-middle badge rounded-pill bg-danger">
                                        10
                                    </span>
                                </i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationsDropdown">
                                <li><h6 class="dropdown-header">Notifications</h6></li>
                                @if(auth()->user()->notifications->count() > 0)
                                    @foreach(auth()->user()->notifications->take(5) as $notification)
                                        <li>
                                            <a class="dropdown-item {{ $notification->read_at ? '' : 'fw-bold' }}" href="#">
                                                <i class="bi bi-info-circle me-2"></i>
                                                {{ $notification->data['message'] }}
                                                <div class="text-muted small">{{ $notification->created_at->diffForHumans() }}</div>
                                            </a>
                                        </li>
                                    @endforeach
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="text-center dropdown-item" href="{{ route('notifications.index') }}">Voir toutes</a></li>
                                @else
                                    <li><a class="text-center dropdown-item" href="#">Aucune notification</a></li>
                                    <li>
                                        <a href="" class="dropdown-item">Marquer toutes comme lues</a>
                                    </li>
                                @endif
                            </ul>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle me-1 fs-5"></i>
                                <span>{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-person me-1"></i> Profil</a></li>
                                <li><a class="dropdown-item" href="{{ route('settings') }}"><i class="bi bi-gear me-1"></i> Paramètres</a></li>
                                @if(Auth::user()->role === 'Admin')
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('users.index') }}"><i class="bi bi-shield-lock me-1"></i> Administration</a></li>
                                    <li><a class="dropdown-item" href="{{ route('users.index') }}"><i class="bi bi-people me-1"></i> Utilisateurs</a></li>
                                    <li><a class="dropdown-item" href="{{ route('dentists.index') }}"><i class="bi bi-hospital me-1"></i> Gestion des dentistes</a></li>
                                    <li><a class="dropdown-item" href="{{ route('assurances.index') }}"><i class="bi bi-shield-plus me-1"></i> Gestion des assurances</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item" type="submit">
                                            <i class="bi bi-box-arrow-right me-1"></i> Déconnexion
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Contenu principal -->
        <main class="py-4 flex-grow-1">
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="mb-4 row">
                    <div class="col-12">
                        <h1 class="page-title">@yield('page-title')</h1>
                        @yield('breadcrumbs')
                    </div>
                </div>

                @yield('content')
            </div>
        </main>

        <!-- Pied de page -->
        <footer class="py-3 mt-auto bg-light border-top">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-0">&copy; {{ date('Y') }} Clinique Dentaire. Tous droits réservés.</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="mb-0">Version {{ config('app.version', '1.0.0') }}</p>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    <!-- jQuery -->
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{asset('js/sweetalert2@11.js')}}"></script>
    <!-- Custom JS -->
    <script src="{{ asset('js/app.js') }}"></script>
    @livewireScripts
    @yield('scripts')
    @stack('scripts')
</body>
</html>
