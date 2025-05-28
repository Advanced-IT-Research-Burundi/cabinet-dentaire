{{-- Formulaire réutilisable pour créer/éditer une caisse --}}
<form action="{{ $action }}" method="POST">
    @csrf
    @if(isset($method))
        @method($method)
    @endif

    {{-- Type --}}
    <div class="mb-3">
        <label for="type" class="form-label">
            <i class="bi bi-tag-fill text-primary me-2"></i>Type
        </label>
        <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
            <option value="">Sélectionner un type</option>
            <option value="income" {{ old('type', $caisse->type ?? '') == 'income' ? 'selected' : '' }}>
                <i class="bi bi-arrow-up-circle-fill"></i> Revenu
            </option>
            <option value="expense" {{ old('type', $caisse->type ?? '') == 'expense' ? 'selected' : '' }}>
                <i class="bi bi-arrow-down-circle-fill"></i> Dépense
            </option>
            <option value="transfer" {{ old('type', $caisse->type ?? '') == 'transfer' ? 'selected' : '' }}>
                <i class="bi bi-arrow-left-right"></i> Transfert
            </option>
        </select>
        @error('type')
            <div class="invalid-feedback">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>{{ $message }}
            </div>
        @enderror
    </div>

    {{-- Date --}}
    <div class="mb-3">
        <label for="date" class="form-label">
            <i class="bi bi-calendar-event-fill text-info me-2"></i>Date
        </label>
        <input type="datetime-local" name="date" id="date"
               class="form-control @error('date') is-invalid @enderror"
               value="{{ old('date', isset($caisse) ? $caisse->date->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}"
               required>
        @error('date')
            <div class="invalid-feedback">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>{{ $message }}
            </div>
        @enderror
    </div>

    {{-- Montant --}}
    {{-- <div class="mb-3">
        <label for="montant" class="form-label">
            <i class="bi bi-currency-euro text-success me-2"></i>Montant
        </label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-cash-coin"></i>
            </span>
            <input type="number" name="montant" id="montant"
                   class="form-control @error('montant') is-invalid @enderror"
                   step="0.01" min="0"
                   value="0.00"
                   required deseabled>
            <span class="input-group-text">FBU</span>
        </div>
        @error('montant')
            <div class="invalid-feedback">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>{{ $message }}
            </div>
        @enderror
    </div> --}}

    {{-- Description --}}
    <div class="mb-3">
        <label for="description" class="form-label">
            <i class="bi bi-card-text text-secondary me-2"></i>Description
        </label>
        <div class="input-group">
            <span class="input-group-text">
                <i class="bi bi-chat-text"></i>
            </span>
            <textarea name="description" id="description"
                      class="form-control @error('description') is-invalid @enderror"
                      rows="3" placeholder="Ajouter une description...">{{ old('description', $caisse->description ?? '') }}</textarea>
        </div>
        @error('description')
            <div class="invalid-feedback">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>{{ $message }}
            </div>
        @enderror
    </div>

    {{-- User --}}
    <div class="mb-3">
        <label for="user_id" class="form-label">
            <i class="bi bi-person-fill text-warning me-2"></i>Utilisateur
        </label>
        <select name="user_id" id="user_id"
                class="form-control @error('user_id') is-invalid @enderror"
                required>
            <option value="">
                <i class="bi bi-person-plus"></i> Sélectionner un utilisateur
            </option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ old('user_id', $caisse->user_id ?? '') == $user->id ? 'selected' : '' }}>
                    <i class="bi bi-person-check"></i> {{ $user->name }}
                </option>
            @endforeach
        </select>
        @error('user_id')
            <div class="invalid-feedback">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>{{ $message }}
            </div>
        @enderror
    </div>

    {{-- Status --}}
    <div class="mb-3">
        <label for="status" class="form-label">
            <i class="bi bi-check-circle-fill text-primary me-2"></i>Statut
        </label>
        <select name="status" id="status"
                class="form-control @error('status') is-invalid @enderror"
                required>
            <option value="">Sélectionner un statut</option>
            <option value="pending" {{ old('status', $caisse->status ?? '') == 'pending' ? 'selected' : '' }}>
                <i class="bi bi-clock-fill text-warning"></i> En attente
            </option>
            <option value="completed" {{ old('status', $caisse->status ?? '') == 'completed' ? 'selected' : '' }}>
                <i class="bi bi-check-circle-fill text-success"></i> Complété
            </option>
            <option value="cancelled" {{ old('status', $caisse->status ?? '') == 'cancelled' ? 'selected' : '' }}>
                <i class="bi bi-x-circle-fill text-danger"></i> Annulé
            </option>
        </select>
        @error('status')
            <div class="invalid-feedback">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>{{ $message }}
            </div>
        @enderror
    </div>

    {{-- Boutons d'action --}}
    <div class="d-flex justify-content-between align-items-center">
        <a href="{{ route('caisses.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Annuler
        </a>

        <button type="submit" class="btn btn-{{ isset($caisse) ? 'warning' : 'primary' }}">
            @if(isset($caisse))
                <i class="bi bi-pencil-square me-2"></i>Modifier Caisse
            @else
                <i class="bi bi-plus-circle me-2"></i>Créer Caisse
            @endif
        </button>
    </div>
</form>

{{-- Script pour améliorer l'UX --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Changer la couleur du bouton selon le type sélectionné
    const typeSelect = document.getElementById('type');
    const montantInput = document.getElementById('montant');

    typeSelect.addEventListener('change', function() {
        const submitBtn = document.querySelector('button[type="submit"]');

        switch(this.value) {
            case 'income':
                montantInput.style.borderLeftColor = '#28a745';
                break;
            case 'expense':
                montantInput.style.borderLeftColor = '#dc3545';
                break;
            case 'transfer':
                montantInput.style.borderLeftColor = '#17a2b8';
                break;
            default:
                montantInput.style.borderLeftColor = '';
        }
    });
});
</script>
