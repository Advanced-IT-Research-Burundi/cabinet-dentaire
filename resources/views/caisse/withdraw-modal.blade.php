{{-- Bouton de retrait à ajouter dans la colonne Actions du tableau --}}
<button type="button"
        class="btn btn-sm btn-outline-warning"
        data-bs-toggle="modal"
        data-bs-target="#withdrawModal{{ $caisse->id }}"
        data-bs-toggle="tooltip"
        title="Retirer de l'argent"
        {{ $caisse->montant <= 0 ? 'disabled' : '' }}>
    <i class="bi bi-cash-stack"></i>
</button>

{{-- Modal de retrait d'argent --}}
<div class="modal fade" id="withdrawModal{{ $caisse->id }}" tabindex="-1" aria-labelledby="withdrawModalLabel{{ $caisse->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="withdrawModalLabel{{ $caisse->id }}">
                    <i class="bi bi-cash-stack me-2"></i>Retirer de l'argent
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" action="{{ route('caisses.withdraw', $caisse->id) }}" id="withdrawForm{{ $caisse->id }}">
                @csrf
                @method('PATCH')

                <div class="modal-body">
                    {{-- Informations de la caisse --}}
                    <div class="alert alert-info border-0 mb-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-info-circle-fill me-3 fs-4"></i>
                            <div>
                                <strong>Caisse sélectionnée</strong><br>
                                <small class="text-muted">
                                    {{ $caisse->user->name }} -
                                    @if($caisse->type == 'income')
                                        <span class="text-success">Revenu</span>
                                    @elseif($caisse->type == 'expense')
                                        <span class="text-danger">Dépense</span>
                                    @else
                                        <span class="text-warning">Transfert</span>
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>

                    {{-- Solde actuel --}}
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card bg-light border-0">
                                <div class="card-body text-center py-3">
                                    <div class="text-muted small mb-1">Solde actuel</div>
                                    <div class="h4 mb-0 text-success fw-bold">
                                        {{ number_format($caisse->montant, 0, ',', ' ') }} <small class="text-muted">FBU</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Montant à retirer --}}
                    <div class="mb-3">
                        <label for="montant_retrait{{ $caisse->id }}" class="form-label">
                            <i class="bi bi-currency-dollar text-warning me-2"></i>Montant à retirer
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-warning text-dark">
                                <i class="bi bi-cash"></i>
                            </span>
                            <input type="number"
                                   name="montant_retrait"
                                   id="montant_retrait{{ $caisse->id }}"
                                   class="form-control @error('montant_retrait') is-invalid @enderror"
                                   step="0.01"
                                   min="0.01"
                                   max="{{ $caisse->montant }}"
                                   placeholder="0.00"
                                   required>
                            <span class="input-group-text">FBU</span>
                        </div>
                        <div class="form-text">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            Montant maximum : {{ number_format($caisse->montant, 0, ',', ' ') }} FBU
                        </div>
                        @error('montant_retrait')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Motif du retrait --}}
                    <div class="mb-3">
                        <label for="motif_retrait{{ $caisse->id }}" class="form-label">
                            <i class="bi bi-card-text text-info me-2"></i>Motif du retrait
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-chat-text"></i>
                            </span>
                            <textarea name="motif_retrait"
                                      id="motif_retrait{{ $caisse->id }}"
                                      class="form-control @error('motif_retrait') is-invalid @enderror"
                                      rows="3"
                                      placeholder="Indiquez le motif de ce retrait..."
                                      required></textarea>
                        </div>
                        @error('motif_retrait')
                            <div class="invalid-feedback">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Prévisualisation du nouveau solde --}}
                    <div class="alert alert-warning border-0" style="display: none;" id="previewSolde{{ $caisse->id }}">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-calculator me-3"></i>
                            <div>
                                <strong>Nouveau solde après retrait :</strong><br>
                                <span class="h6 mb-0" id="nouveauSolde{{ $caisse->id }}">0 FBU</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-warning" id="btnConfirmWithdraw{{ $caisse->id }}" disabled>
                        <i class="bi bi-cash-stack me-2"></i>Confirmer le retrait
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script pour la gestion du modal --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalId = '{{ $caisse->id }}';
    const montantInput = document.getElementById(`montant_retrait${modalId}`);
    const motifInput = document.getElementById(`motif_retrait${modalId}`);
    const previewDiv = document.getElementById(`previewSolde${modalId}`);
    const nouveauSoldeSpan = document.getElementById(`nouveauSolde${modalId}`);
    const btnConfirm = document.getElementById(`btnConfirmWithdraw${modalId}`);
    const soldeActuel = {{ $caisse->montant }};

    // Fonction pour mettre à jour la prévisualisation
    function updatePreview() {
        const montantRetrait = parseFloat(montantInput.value) || 0;
        const motif = motifInput.value.trim();

        if (montantRetrait > 0 && montantRetrait <= soldeActuel) {
            const nouveauSolde = soldeActuel - montantRetrait;
            nouveauSoldeSpan.textContent = nouveauSolde.toLocaleString('fr-FR') + ' FBU';
            previewDiv.style.display = 'block';

            // Activer/désactiver le bouton selon la validité
            btnConfirm.disabled = !(montantRetrait > 0 && motif.length > 0);
        } else {
            previewDiv.style.display = 'none';
            btnConfirm.disabled = true;
        }
    }

    // Écouteurs d'événements
    montantInput.addEventListener('input', updatePreview);
    motifInput.addEventListener('input', updatePreview);

    // Validation du formulaire
    document.getElementById(`withdrawForm${modalId}`).addEventListener('submit', function(e) {
        const montantRetrait = parseFloat(montantInput.value) || 0;
        const motif = motifInput.value.trim();

        if (montantRetrait <= 0) {
            e.preventDefault();
            alert('Le montant doit être supérieur à 0');
            return;
        }

        if (montantRetrait > soldeActuel) {
            e.preventDefault();
            alert(`Le montant ne peut pas dépasser ${soldeActuel.toLocaleString('fr-FR')} FBU`);
            return;
        }

        if (motif.length === 0) {
            e.preventDefault();
            alert('Le motif du retrait est obligatoire');
            return;
        }

        // Confirmation finale
        if (!confirm(`Confirmer le retrait de ${montantRetrait.toLocaleString('fr-FR')} FBU ?`)) {
            e.preventDefault();
        }
    });

    // Réinitialiser le formulaire à la fermeture du modal
    document.getElementById(`withdrawModal${modalId}`).addEventListener('hidden.bs.modal', function() {
        montantInput.value = '';
        motifInput.value = '';
        previewDiv.style.display = 'none';
        btnConfirm.disabled = true;
    });
});
</script>
