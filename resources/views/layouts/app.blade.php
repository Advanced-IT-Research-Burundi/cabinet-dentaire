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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap-icons-1.13.1/bootstrap-icons.min.css') }}">

    {{-- favicon --}}
    <link rel="shortcut icon" href="{{ asset('favicon.ico')}}" type="image/x-icon">

    <!-- Custom CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sweetalert2@11.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom-select.css') }}">

    @stack('styles')
    @livewireStyles
</head>
<body>
    <div class="d-flex flex-column min-vh-100">
        <!-- Navigation principale moderne -->
        <nav class="navbar navbar-expand-lg modern-header">
            <div class="container-fluid">
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                    <img src="{{ asset('img/logo.png') }}" class="brand-logo me-3" alt="Budental Logo">
                    <span class="brand-text">Budental Services</span>
                </a>

                <button class="border-0 navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarMain">
                    <!-- Menu Gauche -->
                    <ul class="mb-2 navbar-nav me-auto mb-lg-0">
                        @can('is-admin')
                        <li class="nav-item">
                            <a class="nav-link modern-nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2 me-2"></i>Tableau de bord
                            </a>
                        </li>
                        @endcan

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle modern-nav-link {{ request()->is('patients*') ? 'active' : '' }}" href="#" id="patientsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-people-fill me-2"></i>Patients
                            </a>
                            <ul class="dropdown-menu modern-dropdown-menu" aria-labelledby="patientsDropdown">
                                <li><a class="dropdown-item modern-dropdown-item" href="{{ route('patients.index') }}">
                                    <i class="bi bi-list-ul"></i>Liste des patients
                                </a></li>
                                <li><a class="dropdown-item modern-dropdown-item" href="{{ route('patients.create') }}">
                                    <i class="bi bi-person-plus-fill"></i>Nouveau patient
                                </a></li>
                            </ul>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle modern-nav-link {{ request()->is('rendezvous*') || request()->is('appointments*') ? 'active' : '' }}" href="#" id="rendezVousDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-calendar-check me-2"></i>Rendez-vous
                            </a>
                            <ul class="dropdown-menu modern-dropdown-menu" aria-labelledby="rendezVousDropdown">
                                <li><a class="dropdown-item modern-dropdown-item" href="{{ route('rendezvous.calendar') }}">
                                    <i class="bi bi-calendar3"></i>Calendrier
                                </a></li>
                                <li><a class="dropdown-item modern-dropdown-item" href="{{ route('rendezvous.create') }}">
                                    <i class="bi bi-plus-circle"></i>Nouveau rendez-vous
                                </a></li>
                                <li><a class="dropdown-item modern-dropdown-item" href="{{ route('rendezvous.today') }}">
                                    <i class="bi bi-calendar-day"></i>Rendez-vous du jour
                                </a></li>
                                <li><a class="dropdown-item modern-dropdown-item" href="{{ route('appointments.index') }}">
                                    <i class="bi bi-calendar-day"></i>Tous les rendez-vous
                                </a></li>
                            </ul>
                        </li>

                        @canany(['is-admin', 'is-pharmacist'])

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle modern-nav-link {{ request()->is('invoices*') || request()->is('payments*') ? 'active' : '' }}" href="#" id="facturationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-receipt me-2"></i>Facturation
                            </a>
                            <ul class="dropdown-menu modern-dropdown-menu" aria-labelledby="facturationDropdown">
                                <li><a class="dropdown-item modern-dropdown-item" href="{{ route('invoices.index') }}">
                                    <i class="bi bi-list-columns"></i>Liste des factures
                                </a></li>
                                <li><a class="dropdown-item modern-dropdown-item" href="{{ route('invoices.create') }}">
                                    <i class="bi bi-file-earmark-plus"></i>Nouvelle facture
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <!-- <li><a class="dropdown-item modern-dropdown-item" href="{{ route('payments.index') }}">
                                    <i class="bi bi-cash-coin"></i>Paiements
                                </a></li>
                                <li><a class="dropdown-item modern-dropdown-item" href="">
                                    <i class="bi bi-exclamation-triangle"></i>Factures impayées
                                </a></li>
                                <li><a class="dropdown-item modern-dropdown-item" href="{{ route('invoices.index') }}">
                                    <i class="bi bi-check2"></i>Factures payées
                                </a></li> -->
                            </ul>
                        </li>
                        @endcanany

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle modern-nav-link {{ request()->is('treatments*')  ? 'active' : '' }}" href="#" id="traitementDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-clipboard2-pulse me-2"></i>Traitements
                            </a>
                            <ul class="dropdown-menu modern-dropdown-menu" aria-labelledby="traitementDropdown">
                                <li><a class="dropdown-item modern-dropdown-item" href="{{ route('treatments.create') }}">
                                    <i class="bi bi-plus-circle"></i>Nouveau traitement
                                </a></li>
                                <li><a class="dropdown-item modern-dropdown-item" href="{{ route('treatments.index') }}">
                                    <i class="bi bi-journal-medical"></i>Historique traitements
                                </a></li>
                                <li><a class="dropdown-item modern-dropdown-item" href="{{ route('settings.treatment-types.index') }}">
                                    <i class="bi bi-list-check"></i>Types de traitement
                                </a></li>
                            </ul>
                        </li>

                        @canany(['is-admin', 'is-pharmacist'])

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle modern-nav-link {{ request()->is('stocks*') ||request()->is('categories*') || request()->is('suppliers*') || request()->is('medicines*')   ? 'active' : '' }}" href="#" id="stocksDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-box-seam me-2"></i>Pharmacies
                            </a>
                            <ul class="dropdown-menu modern-dropdown-menu" aria-labelledby="stocksDropdown">
                                <li><a class="dropdown-item modern-dropdown-item" href="{{ route('stocks.index') }}">
                                    <i class="bi bi-boxes"></i>Inventaire
                                </a></li>
                                <li><a class="dropdown-item modern-dropdown-item" href="{{ route('categories.index') }}">
                                    <i class="bi bi-tag"></i>Categories
                                </a></li>
                                <li><a class="dropdown-item modern-dropdown-item" href="{{ route('invoice.alert') }}">
                                    <i class="bi bi-exclamation-circle"></i>Alertes de stock
                                </a></li>
                                <li><a class="dropdown-item modern-dropdown-item" href="{{ route('stock.utilisateur')}}">
                                    <i class="bi bi-graph-up"></i>Utilisation
                                </a></li>
                                <li><a class="dropdown-item modern-dropdown-item" href="{{ route('suppliers.index') }}">
                                    <i class="bi bi-people"></i>Fournisseurs
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                {{-- rappots de stocks --}}
                                <li>
                                    <a class="dropdown-item modern-dropdown-item" href="{{ route('stock.rapport') }}">
                                        <i class="bi bi-file-earmark-text"></i>Rapports de stocks
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endcanany
                    </ul>

                    <!-- Menu Droite -->
                    <ul class="navbar-nav align-items-center">
                        <!-- Notifications -->
                        @include('stock.notification')
                        {{-- <li class="nav-item dropdown me-3">
                            <a class="nav-link notification-bell" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-bell-fill fs-5"></i>
                                @if(auth()->user()->notifications->where('read_at', null)->count() > 0)
                                    <span class="notification-badge">
                                        {{ auth()->user()->notifications->where('read_at', null)->count() }}
                                    </span>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationsDropdown">
                                <li><div class="notification-header">
                                    <i class="bi bi-bell me-2"></i>Notifications
                                    @if(auth()->user()->notifications->where('read_at', null)->count() > 0)
                                        <span class="float-end">{{ auth()->user()->notifications->where('read_at', null)->count() }} nouvelles</span>
                                    @endif
                                </div></li>

                                @if(auth()->user()->notifications->count() > 0)
                                    @foreach(auth()->user()->notifications->take(5) as $notification)
                                        <li class="notification-item {{ $notification->read_at ? '' : 'unread' }}">
                                            <div class="d-flex align-items-start">
                                                <i class="mt-1 bi bi-info-circle me-3 text-primary"></i>
                                                <div class="flex-grow-1">
                                                    <div class="fw-semibold">{{ $notification->data['message'] ?? 'Nouvelle notification' }}</div>
                                                    <div class="mt-1 text-muted small">
                                                        <i class="bi bi-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                    <li><hr class="m-0 dropdown-divider"></li>
                                    <li class="p-2 text-center">
                                        <a class="btn btn-outline-primary btn-sm me-2" href="{{ route('notifications.index') }}">
                                            Voir toutes
                                        </a>
                                        <a href="#" class="btn btn-primary btn-sm" onclick="markAllAsRead()">
                                            Marquer comme lues
                                        </a>
                                    </li>
                                @else
                                    <li class="text-center notification-item">
                                        <i class="mb-2 bi bi-bell-slash text-muted fs-1 d-block"></i>
                                        <div class="text-muted">Aucune notification</div>
                                    </li>
                                @endif
                            </ul>
                        </li> --}}

                        <!-- User Profile -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle user-profile d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="user-avatar">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                <div class="d-none d-md-block">
                                    <div class="fw-semibold">{{ Auth::user()->full_name }} </div>
                                    <div class="opacity-75 small">{{ Auth::user()->role }}</div>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end modern-dropdown-menu" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item modern-dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-person"></i>Mon Profil
                                </a></li>
                                <li><a class="dropdown-item modern-dropdown-item" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-gear"></i>Administration
                                </a></li>

                                @if(Auth::user()->role === 'Admin')
                                    <li><hr class="dropdown-divider"></li>
                                    <li><div class="px-3 py-2 text-muted small fw-semibold">ADMINISTRATION</div></li>
                                    <li><a class="dropdown-item modern-dropdown-item" href="{{ route('parametres') }}">
                                        <i class="bi bi-shield-lock"></i>Paramètres
                                    </a></li>
                                    <li><a class="dropdown-item modern-dropdown-item" href="{{ route('users.index') }}">
                                        <i class="bi bi-people"></i>Utilisateurs
                                    </a></li>
                                    <li><a class="dropdown-item modern-dropdown-item" href="{{ route('dentists.index') }}">
                                        <i class="bi bi-hospital"></i>Dentistes
                                    </a></li>
                                    <li><a class="dropdown-item modern-dropdown-item" href="{{ route('assurances.index') }}">
                                        <i class="bi bi-shield-plus"></i>Assurances
                                    </a></li>
                                    <li><a class="dropdown-item modern-dropdown-item" href="{{ route('caisses.index') }}">
                                        <i class="bi bi-cash"></i>Caisses
                                    </a></li>
                                @endif

                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item modern-dropdown-item text-danger" type="submit">
                                            <i class="bi bi-box-arrow-right"></i>Déconnexion
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
                <div class="mb-0 row">
                    <div class="col-12">
                        <h1 class="page-title">@yield('page-title')</h1>
                        @yield('breadcrumbs')
                    </div>
                </div>

                @yield('content')
            </div>
        </main>

        <!-- Pied de page -->
        <footer class="py-0.5 mt-auto shadow-sm bg-light border-top">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="text-center col-md-6 text-md-start">
                        <p class="mb-0 text-muted">
                            &copy; {{ date('Y') }} <strong>Clinique Dentaire</strong>. Tous droits réservés.
                        </p>
                    </div>
                    <div class="text-center col-md-6 text-md-end">
                        <p class="mb-0 text-muted">
                            <i class="bi bi-code-slash me-1"></i> Version {{ config('app.version', '1.0.0') }}
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="top-0 p-3 toast-container position-fixed end-0"></div>

    <!-- Bootstrap JS -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{asset('js/sweetalert2@11.js')}}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/custom-select.js') }}"></script>

    <script>
        // Fonction améliorée pour afficher les notifications toast
        function showToast(type, message, title = null, duration = 5000) {
            let toastContainer = document.getElementById('toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.id = 'toast-container';
                toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
                toastContainer.style.zIndex = '9999';
                document.body.appendChild(toastContainer);
            }

            // Déclencher la sonnerie si le type est 'info'
            if (type === 'info') {
                playAlarmSound();
            }

            const toastId = 'toast-' + Date.now();
            const iconMap = {
                'success': 'bi-check-circle-fill',
                'warning': 'bi-exclamation-triangle-fill',
                'danger': 'bi-x-circle-fill',
                'error': 'bi-x-circle-fill',
                'info': 'bi-info-circle-fill'
            };
            const titleMap = {
                'success': 'Succès',
                'warning': 'Attention',
                'danger': 'Erreur',
                'error': 'Erreur',
                'info': 'Information'
            };

            const toast = document.createElement('div');
            toast.id = toastId;
            toast.className = `toast modern-toast toast-${type} align-items-center border-0 mb-2`;
            toast.setAttribute('role', 'alert');
            toast.style.minWidth = '300px';

            const toastTitle = title || titleMap[type];
            toast.innerHTML = `
                <div class="d-flex w-100">
                    <div class="text-white toast-body">
                        <div class="d-flex align-items-start">
                            <i class="bi ${iconMap[type]} me-3 mt-1 fs-5"></i>
                            <div class="flex-grow-1">
                                ${toastTitle ? `<div class="mb-1 fw-semibold">${toastTitle}</div>` : ''}
                                <div>${message}</div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="m-auto btn-close btn-close-white me-2" data-bs-dismiss="toast"></button>
                </div>
            `;

            toastContainer.appendChild(toast);

            const bsToast = new bootstrap.Toast(toast, {
                delay: duration,
                autohide: true
            });

            bsToast.show();

            // Animation d'entrée
            toast.style.transform = 'translateX(100%)';
            toast.style.opacity = '0';
            setTimeout(() => {
                toast.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                toast.style.transform = 'translateX(0)';
                toast.style.opacity = '1';
            }, 10);

            toast.addEventListener('hidden.bs.toast', () => {
                toast.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
                toast.style.transform = 'translateX(100%)';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            });
        }

        // Fonction pour jouer la sonnerie
        function playAlarmSound() {
            try {
                const audio = new Audio('/audios/info.mp3');
                audio.volume = 0.7; // Volume à 50% (ajustable)
                audio.play().catch(error => {
                    console.warn('Impossible de jouer la sonnerie:', error);
                    // Note: Les navigateurs modernes bloquent l'autoplay audio
                    // sans interaction utilisateur
                });
            } catch (error) {
                console.error('Erreur lors du chargement du fichier audio:', error);
            }
        }

        // Fonction pour marquer toutes les notifications comme lues
        function markAllAsRead() {
            // Afficher un loader
            const loadingToast = showToast('info', 'Marquage des notifications...', 'Chargement', 2000);

            // Simuler l'appel AJAX (à remplacer par votre route Laravel)
            fetch('/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('success', 'Toutes les notifications ont été marquées comme lues', 'Succès');
                    // Recharger la page ou mettre à jour le badge
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast('error', 'Erreur lors du marquage des notifications', 'Erreur');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'Erreur de connexion', 'Erreur');
            });
        }

        // Gestion des messages de session Laravel
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                showToast('success', "{{ session('success') }}", 'Succès');
            @endif

            @if(session('error'))
                showToast('error', "{{ session('error') }}", 'Erreur');
            @endif

            @if(session('warning'))
                showToast('warning', "{{ session('warning') }}", 'Attention');
            @endif

            @if(session('info'))
                showToast('info', "{{ session('info') }}", 'Information');
            @endif

            // Animation au scroll pour le header
            let lastScrollTop = 0;
            const header = document.querySelector('.modern-header');

            window.addEventListener('scroll', function() {
                let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

                if (scrollTop > lastScrollTop && scrollTop > 100) {
                    // Scroll vers le bas
                    header.style.transform = 'translateY(-100%)';
                } else {
                    // Scroll vers le haut
                    header.style.transform = 'translateY(0)';
                }
                lastScrollTop = scrollTop;
            });

            // Gestion des dropdowns au hover sur desktop
            if (window.innerWidth > 991) {
                const dropdowns = document.querySelectorAll('.dropdown');

                dropdowns.forEach(dropdown => {
                    let timeout;

                    dropdown.addEventListener('mouseenter', function() {
                        clearTimeout(timeout);
                        const dropdownToggle = this.querySelector('.dropdown-toggle');
                        const dropdownMenu = this.querySelector('.dropdown-menu');

                        if (dropdownToggle && dropdownMenu) {
                            dropdownMenu.classList.add('show');
                            dropdownToggle.classList.add('show');
                            dropdownToggle.setAttribute('aria-expanded', 'true');
                        }
                    });

                    dropdown.addEventListener('mouseleave', function() {
                        const dropdownToggle = this.querySelector('.dropdown-toggle');
                        const dropdownMenu = this.querySelector('.dropdown-menu');

                        timeout = setTimeout(() => {
                            if (dropdownToggle && dropdownMenu) {
                                dropdownMenu.classList.remove('show');
                                dropdownToggle.classList.remove('show');
                                dropdownToggle.setAttribute('aria-expanded', 'false');
                            }
                        }, 300);
                    });
                });
            }

            // Animation des liens de navigation
            const navLinks = document.querySelectorAll('.modern-nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    // Créer un effet ripple
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;

                    ripple.style.cssText = `
                        position: absolute;
                        width: ${size}px;
                        height: ${size}px;
                        left: ${x}px;
                        top: ${y}px;
                        background: rgba(255, 255, 255, 0.3);
                        border-radius: 50%;
                        transform: scale(0);
                        animation: ripple 0.6s linear;
                        pointer-events: none;
                    `;

                    this.style.position = 'relative';
                    this.style.overflow = 'hidden';
                    this.appendChild(ripple);

                    setTimeout(() => ripple.remove(), 600);
                });
            });

            // Notification en temps réel (WebSocket simulation)
            function simulateRealTimeNotifications() {
                const notifications = [
                    { type: 'info', message: 'Nouveau rendez-vous programmé', title: 'Rendez-vous' },
                    { type: 'warning', message: 'Stock faible détecté', title: 'Alerte Stock' },
                    { type: 'success', message: 'Paiement reçu', title: 'Paiement' },
                    { type: 'info', message: 'Nouveau patient enregistré', title: 'Patient' }
                ];

                // Simuler une notification toutes les 30 secondes (à des fins de démonstration)
                setInterval(() => {
                    if (Math.random() < 0.3) { // 30% de chance
                        const randomNotif = notifications[Math.floor(Math.random() * notifications.length)];
                        showToast(randomNotif.type, randomNotif.message, randomNotif.title);

                        // Mettre à jour le badge de notification
                        updateNotificationBadge();
                    }
                }, 30000);
            }

            function updateNotificationBadge() {
                const badge = document.querySelector('.notification-badge');
                if (badge) {
                    let currentCount = parseInt(badge.textContent) || 0;
                    badge.textContent = currentCount + 1;

                    // Animation du badge
                    badge.style.animation = 'none';
                    badge.offsetHeight; // Trigger reflow
                    badge.style.animation = 'pulse 2s infinite';
                }
            }

            // Activer les notifications en temps réel (décommenter pour la production)
            // simulateRealTimeNotifications();
        });

        // Fonction utilitaire pour les requêtes AJAX avec feedback
        function makeAjaxRequest(url, options = {}) {
            const defaultOptions = {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            };

            const finalOptions = { ...defaultOptions, ...options };

            return fetch(url, finalOptions)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .catch(error => {
                    console.error('Ajax request failed:', error);
                    showToast('error', 'Erreur de connexion au serveur', 'Erreur');
                    throw error;
                });
        }

        // Fonction pour précharger les images
        function preloadImages() {
            const images = [
                "{{ asset('img/logo.png') }}"
            ];

            images.forEach(src => {
                const img = new Image();
                img.src = src;
            });
        }

        // Styles CSS pour l'animation ripple
        const rippleStyle = document.createElement('style');
        rippleStyle.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(rippleStyle);

        // Précharger les images au chargement de la page
        window.addEventListener('load', preloadImages);
    </script>

    @livewireScripts
    @yield('scripts')
    @stack('scripts')
</body>
</html>
