@extends('layouts.app')

@section('title', 'Modifier le Fournisseur')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Modifier le Fournisseur</h1>
        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Retour à la liste</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                            <!-- Nom du fournisseur -->
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">
                                    <i class="bi bi-building me-2 text-primary"></i>
                                    <strong>Nom du fournisseur</strong>
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-building"></i>
                                    </span>
                                    <input type="text"
                                           name="name"
                                           id="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', $supplier->name) }}"
                                           placeholder="Entrez le nom du fournisseur"
                                           required>
                                </div>
                                @error('name')
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope me-2 text-success"></i>
                             <strong>Email</strong>
                             </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-envelope"></i>
                                    </span>
                                    <input type="email"
                                           name="email"
                                           id="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{  old('email', $supplier->email) }}"
                                           placeholder="exemple@email.com">
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Téléphone -->
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">
                                    <i class="bi bi-telephone me-2 text-info"></i>
                                    <strong>Téléphone</strong>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-telephone"></i>
                                    </span>
                                    <input type="tel"
                                           name="phone"
                                           id="phone"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           value="{{ old('phone', $supplier->phone)  }}"
                                           placeholder="+257 XX XX XX XX">
                                </div>
                                @error('phone')
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Adresse -->
                            <div class="col-md-6 mb-3">
                                <label for="address" class="form-label">
                                    <i class="bi bi-geo-alt me-2 text-warning"></i>
                                    <strong>Adresse</strong>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-geo-alt"></i>
                                    </span>
                                    <textarea name="address"
                                              id="address"
                                              class="form-control @error('address') is-invalid @enderror"
                                              rows="3"
                                              placeholder="Adresse complète du fournisseur">{{ old('address', $supplier->address) }}</textarea>
                                </div>
                                @error('address')
                                    <div class="invalid-feedback d-block">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                <div class="mt-4 text-end">
                    <div class="">
                            <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-2"></i>
                                Annuler
                            </a>
                    <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>
                                Mettre à jour
                    </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
