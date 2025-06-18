@extends('layouts.app')
@section('content')
    <div>
        <div class="container py-5">
            <div class="row">
                <div class="col-lg-4">
                    <!-- Profile Card -->
                    <div class="card profile-card mb-4">
                        <div class="card-body text-center p-4">
                            <div class="mb-4 position-relative">
                                {{-- @dump($user?->photo_url !== null) --}}
                                <img src="{{ ($user?->photo_url !== null) ? Storage::url($user?->photo_url) : asset('img/avatar.jpg') }}" alt="Photo de profil" class="rounded-circle profile-img mb-3">
                                <button class="btn btn-sm btn-light position-absolute bottom-0" data-bs-toggle="modal" data-bs-target="#updatePhotoModal">
                                    <i class="bi bi-camera-fill"></i>
                                </button>
                            </div>
                            <h5 class="mb-1">{{ $user->name }}</h5>
                            <p class="text-muted mb-3">{{ $user->role }}</p>
                            <span class="badge rounded-pill status-badge-{{ strtolower($user->statut) }}">{{ $user->statut }}</span>

                            <div class="mt-3 d-flex justify-content-center">
                                <a href="mailto:{{ $user->email }}" class="btn btn-outline-primary me-2" title="Envoyer un email">
                                    <i class="bi bi-envelope-fill"></i>
                                </a>
                                @if($user->phone)
                                    <a href="tel:{{ $user->phone }}" class="btn btn-outline-primary me-2" title="Appeler">
                                        <i class="bi bi-telephone-fill"></i>
                                    </a>
                                @else
                                    <a href="#" class="btn btn-outline-primary me-2 disabled" title="Téléphone non renseigné">
                                        <i class="bi bi-telephone-fill"></i>
                                    </a>
                                @endif
                                {{-- <a href="" class="btn btn-outline-primary" title="Prendre rendez-vous">
                                    <i class="bi bi-calendar-check"></i>
                                </a> --}}
                            </div>

                            {{-- <div class="mt-3">
                                <p class="mb-0 last-connection">
                                    <i class="bi bi-clock-history"></i> Dernière connexion:
                                    {{ $user->derniere_connexion ? date('d/m/Y H:i', strtotime($user->derniere_connexion)) : 'Jamais' }}
                                </p>
                            </div> --}}
                        </div>
                    </div>

                    <!-- Contact Information Card -->
                    <div class="card profile-card mb-4">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-3">
                                <i class="bi bi-person-lines-fill me-2"></i>Informations de contact
                            </h5>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="bi bi-envelope me-2 text-primary"></i>
                                    <strong>Email:</strong> {{ $user->email }}
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-envelope-plus me-2 text-primary"></i>
                                    <strong>Email secondaire:</strong> {{ $user->secondary_email ?? 'Non renseigné' }}
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-telephone me-2 text-primary"></i>
                                    <strong>Téléphone:</strong> {{ $user->phone ?? 'Non renseigné' }}
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-phone me-2 text-primary"></i>
                                    <strong>Téléphone secondaire:</strong> {{ $user->secondary_phone ?? 'Non renseigné' }}
                                </li>
                                <li>
                                    <i class="bi bi-geo-alt me-2 text-primary"></i>
                                    <strong>Adresse:</strong><br>
                                    {{ $user->adresse ?? 'Non renseignée' }}<br>
                                    {{ $user->code_postal ?? '' }} {{ $user->ville ?? '' }}<br>
                                    {{ $user->pays ?? '' }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <!-- Tab Navigation -->
                    <div class="card profile-card mb-4">
                        <div class="card-body p-4">
                            <ul class="nav nav-pills mb-4" id="profileTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="profile-tab" data-bs-toggle="pill" data-bs-target="#profile-info" type="button" role="tab">
                                        <i class="bi bi-person-fill tab-icon"></i>Informations personnelles
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="security-tab" data-bs-toggle="pill" data-bs-target="#security" type="button" role="tab">
                                        <i class="bi bi-shield-lock-fill tab-icon"></i>Sécurité
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="address-tab" data-bs-toggle="pill" data-bs-target="#address" type="button" role="tab">
                                        <i class="bi bi-geo-alt-fill tab-icon"></i>Adresse
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content" id="profileTabsContent">
                                <!-- Profile Information Tab -->
                                <div class="tab-pane fade show active" id="profile-info" role="tabpanel">
                                    <h5 class="card-title mb-4">Informations personnelles</h5>
                                    <form method="POST" action="{{ route('profile.update') }}" id="profile-form">
                                        @csrf
                                        @method('patch')

                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label for="name" class="form-label">Nom complet</label>
                                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                                @error('name')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <label for="first_name" class="form-label">Prénom <span style="color: red">*</span></label>
                                                <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}">
                                                @error('first_name')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-4">
                                                <label for="last_name" class="form-label">Nom <span style="color: red">*</span></label>
                                                <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}">
                                                @error('last_name')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                                @error('email')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror

                                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                                                    <div class="mt-2">
                                                        <span class="badge bg-warning text-dark">Email non vérifié</span>
                                                        <button form="send-verification" class="btn btn-sm btn-link">
                                                            Renvoyer l'email de vérification
                                                        </button>
                                                    </div>

                                                    @if (session('status') === 'verification-link-sent')
                                                        <div class="alert alert-success mt-2">
                                                            Un nouveau lien de vérification a été envoyé à votre adresse email.
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <label for="secondary_email" class="form-label">Email secondaire</label>
                                                <input type="email" class="form-control" id="secondary_email" name="secondary_email" value="{{ old('secondary_email', $user->secondary_email) }}">
                                                @error('secondary_email')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="phone" class="form-label">Téléphone</label>
                                                <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                                @error('phone')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label for="secondary_phone" class="form-label">Téléphone secondaire</label>
                                                <input type="tel" class="form-control" id="secondary_phone" name="secondary_phone" value="{{ old('secondary_phone', $user->secondary_phone) }}">
                                                @error('secondary_phone')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-save me-2"></i>Enregistrer
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Security Tab -->
                                <div class="tab-pane fade" id="security" role="tabpanel">
                                    <h5 class="card-title mb-4">Sécurité</h5>
                                    <form method="POST" action="{{ route('password.update') }}">
                                        @csrf
                                        @method('put')

                                        <div class="mb-3">
                                            <label for="current_password" class="form-label">Mot de passe actuel</label>
                                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                                            @error('current_password')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="password" class="form-label">Nouveau mot de passe</label>
                                            <input type="password" class="form-control" id="password" name="password" required>
                                            @error('password')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                            @error('password_confirmation')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-shield-lock me-2"></i>Mettre à jour le mot de passe
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Address Tab -->
                                <div class="tab-pane fade" id="address" role="tabpanel">
                                    <h5 class="card-title mb-4">Adresse</h5>
                                    <form method="POST" action="{{ route('profile.update.address') }}">
                                        @csrf
                                        @method('patch')

                                        <div class="mb-3">
                                            <label for="adresse" class="form-label">Adresse</label>
                                            <input type="text" class="form-control" id="adresse" name="adresse" value="{{ old('adresse', $user->adresse) }}">
                                            @error('adresse')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="ville" class="form-label">Ville</label>
                                                <input type="text" class="form-control" id="ville" name="ville" value="{{ old('ville', $user->ville) }}">
                                                @error('ville')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6">
                                                <label for="code_postal" class="form-label">Code postal</label>
                                                <input type="text" class="form-control" id="code_postal" name="code_postal" value="{{ old('code_postal', $user->code_postal) }}">
                                                @error('code_postal')
                                                    <div class="text-danger mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="pays" class="form-label">Pays</label>
                                            <input type="text" class="form-control" id="pays" name="pays" value="{{ old('pays', $user->pays) }}">
                                            @error('pays')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-geo me-2"></i>Mettre à jour l'adresse
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Success and Error Alert -->
                    @if (session('status') === 'profile-updated')
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>Vos informations ont été mises à jour avec succès.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @if (session('status') === 'password-updated')
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>Votre mot de passe a été mis à jour avec succès.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @if (session('status') === 'address-updated')
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>Votre adresse a été mise à jour avec succès.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Update Photo Modal -->
        <div class="modal fade" id="updatePhotoModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Mettre à jour la photo de profil</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('profile.update.photo') }}" enctype="multipart/form-data">
                            @csrf
                            @method('patch')

                            <div class="mb-3">
                                <label for="photo" class="form-label">Sélectionner une photo</label>
                                <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-upload me-2"></i>Télécharger la photo
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Send Verification Form -->
        <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="d-none">
            @csrf
        </form>
    </div>
@endsection
@push('scripts')
    <script>
        // Pour afficher les messages flash et les fermer automatiquement après 5 secondes
        document.addEventListener('DOMContentLoaded', function() {
            // Afficher les alertes pendant 5 secondes
            setTimeout(function() {
                var alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    var bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
@endpush
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush
