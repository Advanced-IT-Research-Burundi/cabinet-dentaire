<template>
  <div>
    <div class="compta-page-header">
      <div>
        <h1>Saisie des écritures</h1>
        <p>Création et modification d’une écriture indépendante par ligne.</p>
      </div>
      <div style="display: flex; gap: 0.5rem; flex-wrap: wrap">
        <button type="button" class="compta-btn compta-btn-secondary" @click="openContextModal">
          Journal / période
        </button>
        <RouterLink class="compta-btn compta-btn-secondary" :to="{ name: 'compta.pieces' }">Pièces</RouterLink>
        <button type="button" class="compta-btn compta-btn-secondary" :disabled="loading" @click="load">
          <i class="bi bi-arrow-clockwise"></i>
        </button>
      </div>
    </div>

    <div v-if="error" class="compta-error">{{ error }}</div>
    <div v-if="success" class="compta-card saisie-success">{{ success }}</div>

    <div v-if="contextModalOpen" class="ecriture-context-backdrop" role="dialog" aria-modal="true">
      <div class="ecriture-context-modal">
        <div class="ecriture-context-header">
          <div>
            <h2>Choisir l’exercice, le journal et la période</h2>
            <p>La période est chargée selon l’exercice choisi.</p>
          </div>
          <button
            type="button"
            class="compta-btn compta-btn-ghost ecriture-context-close"
            title="Fermer"
            aria-label="Fermer"
            @click="contextModalOpen = false"
          >
            <i class="bi bi-x-lg"></i>
          </button>
        </div>

        <div class="ecriture-context-fields">
          <div class="compta-field">
            <label>Exercice</label>
            <select v-model="selectedExerciceId" class="compta-select" style="width: 100%" @change="onExerciceChange">
              <option :value="null">—</option>
              <option v-for="exercice in openExercices" :key="exercice.id" :value="exercice.id">
                {{ exerciceOptionLabel(exercice) }}
              </option>
            </select>
          </div>

          <div class="compta-field">
            <label>Code journal</label>
            <select v-model="selectedJournalId" class="compta-select" style="width: 100%">
              <option :value="null">—</option>
              <option v-for="journal in context.journals" :key="journal.id" :value="journal.id">
                {{ journal.code }} — {{ journal.intitule || journal.libelle }}
              </option>
            </select>
          </div>

          <div class="compta-field">
            <label>Période</label>
            <select v-model="selectedPeriodeId" class="compta-select" style="width: 100%" :disabled="!selectedOpenExercice">
              <option :value="null">—</option>
              <option v-for="periode in periodesForExercice" :key="periode.id" :value="periode.id">
                {{ periode.libelle || periode.code || `Période #${periode.id}` }}
              </option>
            </select>
          </div>
        </div>

        <div v-if="!openExercices.length" class="compta-error">
          Aucun exercice ouvert n’est disponible pour la saisie.
        </div>
        <div v-else-if="selectedExerciceId && !periodesForExercice.length" class="compta-error">
          Aucune période n’est disponible pour cet exercice.
        </div>

        <div class="ecriture-context-actions">
          <RouterLink class="compta-btn compta-btn-secondary" :to="{ name: 'compta.pieces' }">Pièces</RouterLink>
          <button type="button" class="compta-btn compta-btn-primary" :disabled="!contextReady" @click="confirmContext">
            Ouvrir la saisie
          </button>
        </div>
      </div>
    </div>

    <div v-if="contextReady" class="compta-card ecriture-card">
      <form @submit.prevent="submit">
        <div class="compta-table-wrap">
          <table class="compta-table ecriture-table">
            <colgroup>
              <col class="ecriture-col-piece" />
              <col class="ecriture-col-date" />
              <col class="ecriture-col-facture" />
              <col class="ecriture-col-compte" />
              <col class="ecriture-col-auxiliaire" />
              <col class="ecriture-col-libelle" />
              <col class="ecriture-col-montant" />
              <col class="ecriture-col-montant" />
              <col class="ecriture-col-actions" />
            </colgroup>
            <thead>
              <tr>
                <th>Pièce</th>
                <th>Date</th>
                <th>Facture</th>
                <th>Compte</th>
                <th>Auxiliaire</th>
                <th>Libellé</th>
                <th>Débit</th>
                <th>Crédit</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr class="ecriture-form-row">
                <td>
                  <select v-model="form.piece_id" class="compta-select" required aria-label="Pièce" @change="onPieceChange">
                    <option :value="null">—</option>
                    <option v-for="piece in pieces" :key="piece.id" :value="piece.id">
                      {{ pieceLabel(piece) }}
                    </option>
                  </select>
                </td>
                <td>
                  <input v-model="form.date_ecriture" class="compta-input" type="date" required aria-label="Date" />
                </td>
                <td>
                  <input v-model="form.numero_facture" class="compta-input" aria-label="Facture" />
                </td>
                <td>
                  <select v-model="form.compte_id" class="compta-select" required aria-label="Compte" @change="onCompteChange">
                    <option :value="null">—</option>
                    <option v-for="compte in context.comptes" :key="compte.id" :value="compte.id">
                      {{ compte.numero }} — {{ compte.intitule }}
                    </option>
                  </select>
                </td>
                <td>
                  <select
                    v-model="form.compte_auxiliaire_id"
                    class="compta-select"
                    :class="{ 'saisie-invalid': auxiliaryMissing(form) }"
                    :disabled="auxiliaryLocked(form)"
                    aria-label="Auxiliaire"
                  >
                    <option :value="null">—</option>
                    <option v-for="tier in auxiliaryOptions(form)" :key="tier.id" :value="tier.id">
                      {{ tierLabel(tier) }}
                    </option>
                  </select>
                  <small v-if="auxiliaryMissing(form)" class="saisie-help">Obligatoire</small>
                </td>
                <td>
                  <input v-model="form.libelle" class="compta-input" aria-label="Libellé" />
                </td>
                <td>
                  <input v-model.number="form.debit" class="compta-input" type="number" min="0" step="0.01" aria-label="Débit" @input="normalizeAmounts('debit')" />
                </td>
                <td>
                  <input v-model.number="form.credit" class="compta-input" type="number" min="0" step="0.01" aria-label="Crédit" @input="normalizeAmounts('credit')" />
                </td>
                <td class="ecriture-actions-cell">
                  <button
                    type="submit"
                    class="compta-btn compta-btn-primary ecriture-icon-btn"
                    :disabled="!canSubmit || saving"
                    :title="editingId ? 'Modifier l’écriture' : 'Enregistrer l’écriture'"
                    :aria-label="editingId ? 'Modifier l’écriture' : 'Enregistrer l’écriture'"
                  >
                    <i :class="editingId ? 'bi bi-check-lg' : 'bi bi-plus-lg'"></i>
                  </button>
                  <button
                    v-if="editingId"
                    type="button"
                    class="compta-btn compta-btn-secondary ecriture-icon-btn"
                    title="Annuler la modification"
                    aria-label="Annuler la modification"
                    @click="resetForm"
                  >
                    <i class="bi bi-x-lg"></i>
                  </button>
                </td>
              </tr>
              <tr
                v-for="ecriture in ecritures"
                :key="ecriture.id"
                :class="{ 'saisie-row-editing': editingId === ecriture.id }"
                @dblclick="edit(ecriture)"
              >
                <td>{{ pieceLabelById(ecriture.piece_id) }}</td>
                <td>{{ formatDate(ecriture.date_ecriture) }}</td>
                <td>{{ ecriture.numero_facture || '—' }}</td>
                <td>{{ compteLabel(ecriture.compte_id) }}</td>
                <td>
                  <span :class="{ 'saisie-help': auxiliaryMissing(ecriture) }">
                    {{ auxiliaryLabel(ecriture) }}
                  </span>
                </td>
                <td>{{ ecriture.libelle || '—' }}</td>
                <td>{{ format(ecriture.debit) }}</td>
                <td>{{ format(ecriture.credit) }}</td>
                <td style="white-space: nowrap">
                  <button
                    type="button"
                    class="compta-btn compta-btn-ghost ecriture-icon-btn"
                    title="Modifier"
                    aria-label="Modifier"
                    @click.stop="edit(ecriture)"
                  >
                    <i class="bi bi-pencil"></i>
                  </button>
                  <button
                    type="button"
                    class="compta-btn compta-btn-ghost ecriture-icon-btn"
                    style="color: #b91c1c"
                    title="Supprimer"
                    aria-label="Supprimer"
                    :disabled="deletingId === ecriture.id"
                    @click.stop="remove(ecriture)"
                  >
                    <i class="bi bi-trash"></i>
                  </button>
                </td>
              </tr>
              <tr v-if="!ecritures.length">
                <td colspan="9" class="compta-empty">Aucune écriture</td>
              </tr>
            </tbody>
          </table>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { computed, inject, onBeforeUnmount, onMounted, reactive, ref, watch } from 'vue'
import { useRoute } from 'vue-router'
import { ecrituresApi, piecesApi } from '../services/api'
import { useContextStore } from '../modules/context/store'

const route = useRoute()
const context = useContextStore()
const saisieContextBar = inject('saisieContextBar', null)
const today = new Date().toISOString().slice(0, 10)

const pieces = ref([])
const ecritures = ref([])
const loading = ref(false)
const saving = ref(false)
const deletingId = ref(null)
const editingId = ref(null)
const selectedPieceId = ref(route.query.piece_id ? Number(route.query.piece_id) : null)
const selectedExerciceId = ref(null)
const selectedJournalId = ref(null)
const selectedPeriodeId = ref(null)
const contextModalOpen = ref(true)
const error = ref(null)
const success = ref(null)

const form = reactive(emptyForm())

function emptyForm() {
  return {
    piece_id: selectedPieceId.value || null,
    piece_comptable_id: selectedPieceId.value || null,
    numero_facture: '',
    compte_id: null,
    compte_auxiliaire_id: null,
    date_ecriture: today,
    libelle: '',
    debit: 0,
    credit: 0,
  }
}

const canSubmit = computed(() => (
  contextReady.value &&
  !!form.piece_id &&
  !!form.compte_id &&
  !!form.date_ecriture &&
  validAuxiliary(form) &&
  (Number(form.debit) > 0 || Number(form.credit) > 0)
))

const openExercices = computed(() => context.exercices.filter((exercice) => !exercice.cloture))

const selectedOpenExercice = computed(() => (
  openExercices.value.find((exercice) => Number(exercice.id) === Number(selectedExerciceId.value)) || null
))

const periodesForExercice = computed(() => {
  if (!selectedOpenExercice.value) return []
  return context.periodes.filter((periode) => Number(periode.exercice_id) === Number(selectedOpenExercice.value.id))
})

const contextReady = computed(() => (
  !!selectedOpenExercice.value &&
  !!selectedJournalId.value &&
  periodesForExercice.value.some((periode) => Number(periode.id) === Number(selectedPeriodeId.value))
))
const selectedJournal = computed(() => context.journals.find((journal) => Number(journal.id) === Number(selectedJournalId.value)) || null)
const selectedPeriode = computed(() => context.periodes.find((periode) => Number(periode.id) === Number(selectedPeriodeId.value)) || null)
const selectedExercice = computed(() => context.exercices.find((exercice) => Number(exercice.id) === Number(selectedExerciceId.value)) || null)
const journalLabel = computed(() => {
  const journal = selectedJournal.value
  return journal ? `${journal.code} — ${journal.intitule || journal.libelle || journal.id}` : '—'
})
const periodeLabel = computed(() => {
  const periode = selectedPeriode.value
  return periode ? (periode.libelle || periode.code || `Période #${periode.id}`) : '—'
})
const exerciceLabel = computed(() => {
  const exercice = selectedExercice.value
  if (!exercice) return 'Aucun exercice ouvert'
  return `${exercice.code || exercice.id} — ${formatDate(exercice.date_debut)} / ${formatDate(exercice.date_fin)}`
})

function exerciceOptionLabel(exercice) {
  return `${exercice.code || exercice.id} — ${formatDate(exercice.date_debut)} / ${formatDate(exercice.date_fin)}`
}

function pieceLabel(piece) {
  return `${piece.numero_piece || `#${piece.id}`} — ${piece.libelle || piece.reference || piece.date_comptable || ''}`.trim()
}

function pieceLabelById(id) {
  const piece = pieces.value.find((item) => Number(item.id) === Number(id))
  return piece ? pieceLabel(piece) : `#${id}`
}

function compteLabel(id) {
  const compte = context.comptes.find((item) => Number(item.id) === Number(id))
  return compte ? `${compte.numero} — ${compte.intitule}` : `#${id}`
}

function tierLabel(tier) {
  return tier.intitule || tier.nom_complet || tier.abrege || `#${tier.id}`
}

function auxiliaryLabel(line) {
  if (auxiliaryMissing(line)) return 'Auxiliaire obligatoire'
  if (!line.compte_auxiliaire_id) return '—'
  const tier = context.tiers.find((item) => Number(item.id) === Number(line.compte_auxiliaire_id))
  return tier ? tierLabel(tier) : `#${line.compte_auxiliaire_id}`
}

function auxiliaryOptions(line) {
  if (!line.compte_id) return []
  return context.tiers.filter((tier) => Number(tier.compte_collectif_id) === Number(line.compte_id))
}

function accountRequiresAuxiliary(line) {
  return auxiliaryOptions(line).length > 0
}

function auxiliaryLocked(line) {
  return !line.compte_id || !accountRequiresAuxiliary(line)
}

function auxiliaryMissing(line) {
  return !!line.compte_id && accountRequiresAuxiliary(line) && !line.compte_auxiliaire_id
}

function validAuxiliary(line) {
  if (!line.compte_id) return true
  if (accountRequiresAuxiliary(line)) {
    return auxiliaryOptions(line).some((tier) => Number(tier.id) === Number(line.compte_auxiliaire_id))
  }
  return !line.compte_auxiliaire_id
}

function normalizeAmounts(side) {
  if (side === 'debit' && Number(form.debit) > 0) form.credit = 0
  if (side === 'credit' && Number(form.credit) > 0) form.debit = 0
}

function format(n) {
  return new Intl.NumberFormat('fr-BI', { minimumFractionDigits: 2 }).format(Number(n) || 0)
}

function formatDate(value) {
  return value ? String(value).slice(0, 10) : '—'
}

function onCompteChange() {
  if (!validAuxiliary(form)) form.compte_auxiliaire_id = null
}

function onPieceChange() {
  form.piece_comptable_id = form.piece_id || null
  const piece = pieces.value.find((item) => Number(item.id) === Number(form.piece_id))
  if (piece?.date_comptable && form.date_ecriture === today) {
    form.date_ecriture = String(piece.date_comptable).slice(0, 10)
  }
}

function payload() {
  return {
    piece_id: Number(form.piece_id),
    piece_comptable_id: form.piece_comptable_id ? Number(form.piece_comptable_id) : Number(form.piece_id),
    numero_facture: form.numero_facture || null,
    compte_id: Number(form.compte_id),
    compte_auxiliaire_id: form.compte_auxiliaire_id ? Number(form.compte_auxiliaire_id) : null,
    date_ecriture: form.date_ecriture,
    libelle: form.libelle || null,
    debit: Number(form.debit) || 0,
    credit: Number(form.credit) || 0,
  }
}

async function loadPieces() {
  if (!contextReady.value) {
    pieces.value = []
    return
  }

  const params = {
    per_page: 500,
    exercice_id: selectedExerciceId.value || undefined,
    journal_id: selectedJournalId.value || undefined,
    periode_id: selectedPeriodeId.value || undefined,
  }
  const res = await piecesApi.list(params)
  pieces.value = Array.isArray(res.data) ? res.data : []
}

async function loadEcritures() {
  if (!contextReady.value) {
    ecritures.value = []
    return
  }

  loading.value = true
  error.value = null
  try {
    const params = {
      per_page: 500,
      exercice_id: selectedExerciceId.value || undefined,
      journal_id: selectedJournalId.value || undefined,
      periode_id: selectedPeriodeId.value || undefined,
    }
    const res = await ecrituresApi.list(params)
    ecritures.value = Array.isArray(res.data) ? res.data : []
  } catch (e) {
    error.value = e.message
  } finally {
    loading.value = false
  }
}

async function load() {
  if (!contextReady.value) {
    contextModalOpen.value = true
    return
  }

  loading.value = true
  error.value = null
  try {
    await loadPieces()
    const selectedPieceExists = pieces.value.some((piece) => Number(piece.id) === Number(selectedPieceId.value))
    if (selectedPieceId.value && !selectedPieceExists) {
      selectedPieceId.value = null
      form.piece_id = null
      form.piece_comptable_id = null
    }
    if (selectedPieceId.value && !form.piece_id) {
      form.piece_id = selectedPieceId.value
      form.piece_comptable_id = selectedPieceId.value
      onPieceChange()
    }
    await loadEcritures()
  } catch (e) {
    error.value = e.message
  } finally {
    loading.value = false
  }
}

async function submit() {
  if (!canSubmit.value) return
  saving.value = true
  error.value = null
  success.value = null
  try {
    if (editingId.value) {
      await ecrituresApi.update(editingId.value, payload())
      success.value = 'Écriture modifiée.'
    } else {
      await ecrituresApi.create(payload())
      success.value = 'Écriture enregistrée.'
    }
    const keepPiece = form.piece_id
    resetForm()
    form.piece_id = keepPiece
    form.piece_comptable_id = keepPiece
    await loadEcritures()
  } catch (e) {
    error.value = e.message
  } finally {
    saving.value = false
  }
}

function edit(ecriture) {
  editingId.value = ecriture.id
  Object.assign(form, {
    piece_id: ecriture.piece_id,
    piece_comptable_id: ecriture.piece_comptable_id || ecriture.piece_id,
    numero_facture: ecriture.numero_facture || '',
    compte_id: ecriture.compte_id,
    compte_auxiliaire_id: ecriture.compte_auxiliaire_id || null,
    date_ecriture: formatDate(ecriture.date_ecriture),
    libelle: ecriture.libelle || '',
    debit: Number(ecriture.debit) || 0,
    credit: Number(ecriture.credit) || 0,
  })
}

function resetForm() {
  Object.assign(form, emptyForm())
  editingId.value = null
}

function firstPeriodeForExercice(exerciceId) {
  return context.periodes.find((periode) => Number(periode.exercice_id) === Number(exerciceId) && !periode.cloturee)
    || context.periodes.find((periode) => Number(periode.exercice_id) === Number(exerciceId))
    || null
}

function applyDefaultContext() {
  const defaultExercice = openExercices.value.find((exercice) => Number(exercice.id) === Number(context.exerciceId))
    || openExercices.value[0]

  if (!defaultExercice) {
    selectedExerciceId.value = null
    selectedPeriodeId.value = null
    return
  }

  if (!selectedOpenExercice.value) {
    selectedExerciceId.value = defaultExercice.id
  }

  const periodStillValid = periodesForExercice.value.some((periode) => Number(periode.id) === Number(selectedPeriodeId.value))
  if (!periodStillValid) {
    selectedPeriodeId.value = firstPeriodeForExercice(selectedExerciceId.value)?.id ?? null
  }
}

function onExerciceChange() {
  selectedPieceId.value = null
  selectedPeriodeId.value = firstPeriodeForExercice(selectedExerciceId.value)?.id ?? null
  resetForm()
  syncTopContext()
}

function openContextModal() {
  applyDefaultContext()
  contextModalOpen.value = true
}

async function confirmContext() {
  if (!contextReady.value) return
  context.setExercice(selectedExerciceId.value)
  context.setPeriode(selectedPeriodeId.value)
  contextModalOpen.value = false
  selectedPieceId.value = route.query.piece_id ? Number(route.query.piece_id) : null
  resetForm()
  syncTopContext()
  await load()
}

function syncTopContext() {
  saisieContextBar?.set({
    ready: contextReady.value,
    journal: journalLabel.value,
    exercice: exerciceLabel.value,
    periode: periodeLabel.value,
  })
}

async function remove(ecriture) {
  if (!window.confirm('Supprimer cette écriture ?')) return
  deletingId.value = ecriture.id
  error.value = null
  try {
    await ecrituresApi.remove(ecriture.id)
    await loadEcritures()
  } catch (e) {
    error.value = e.message
  } finally {
    deletingId.value = null
  }
}

onMounted(() => {
  applyDefaultContext()
  contextModalOpen.value = true
  syncTopContext()
})
watch(() => context.exerciceId, (id) => {
  if (id && !selectedExerciceId.value) selectedExerciceId.value = id
  applyDefaultContext()
  syncTopContext()
})
watch(() => context.periodeId, (id) => {
  if (id && !selectedPeriodeId.value) selectedPeriodeId.value = id
  applyDefaultContext()
  syncTopContext()
})
watch([selectedExerciceId, selectedJournalId, selectedPeriodeId], syncTopContext)

onBeforeUnmount(() => {
  saisieContextBar?.clear()
})
</script>

<style scoped>
.saisie-success {
  margin-bottom: 1rem;
  border-color: #86efac;
  color: #065f46;
}

.ecriture-context-backdrop {
  position: fixed;
  inset: 0;
  z-index: 60;
  display: grid;
  place-items: center;
  padding: 1rem;
  background: rgb(15 23 42 / 0.45);
}

.ecriture-context-modal {
  width: min(780px, 100%);
  display: grid;
  gap: 1rem;
  padding: 1.25rem;
  border-radius: 8px;
  border: 1px solid var(--compta-border);
  background: #ffffff;
  box-shadow: 0 24px 60px rgb(15 23 42 / 0.25);
}

.ecriture-context-modal h2 {
  margin: 0 0 0.25rem;
  font-size: 1.15rem;
  line-height: 1.3;
}

.ecriture-context-modal p {
  margin: 0;
  color: #64748b;
  font-size: 0.9rem;
}

.ecriture-context-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 1rem;
}

.ecriture-context-close {
  width: 2.25rem;
  min-width: 2.25rem;
  height: 2.25rem;
  display: inline-grid;
  place-items: center;
  padding: 0;
}
.ecriture-context-fields {
  display: grid;
  grid-template-columns: repeat(3, minmax(160px, 1fr));
  gap: 0.75rem;
}

.ecriture-context-actions {
  display: flex;
  justify-content: flex-end;
  gap: 0.5rem;
}

.ecriture-card {
  --ecriture-col-piece: 150px;
  --ecriture-col-date: 132px;
  --ecriture-col-facture: 132px;
  --ecriture-col-compte: 190px;
  --ecriture-col-auxiliaire: 170px;
  --ecriture-col-libelle: 220px;
  --ecriture-col-montant: 100px;
  --ecriture-col-actions: 88px;
  --ecriture-total-width: 1282px;
  overflow-x: auto;
}

.ecriture-icon-btn {
  width: 2.4rem;
  min-width: 2.4rem;
  height: 2.4rem;
  display: inline-grid;
  place-items: center;
  padding: 0;
}

.ecriture-table {
  table-layout: fixed;
  min-width: var(--ecriture-total-width);
}

.ecriture-table th,
.ecriture-table td {
  overflow-wrap: anywhere;
}

.ecriture-form-row td {
  background: #ffffff;
  vertical-align: top;
}

.ecriture-form-row .compta-input,
.ecriture-form-row .compta-select {
  width: 100%;
  min-width: 0;
}

.ecriture-actions-cell {
  white-space: nowrap;
}

.ecriture-actions-cell .ecriture-icon-btn + .ecriture-icon-btn {
  margin-left: 0.35rem;
}

.ecriture-col-piece {
  width: var(--ecriture-col-piece);
}

.ecriture-col-date {
  width: var(--ecriture-col-date);
}

.ecriture-col-facture {
  width: var(--ecriture-col-facture);
}

.ecriture-col-compte {
  width: var(--ecriture-col-compte);
}

.ecriture-col-auxiliaire {
  width: var(--ecriture-col-auxiliaire);
}

.ecriture-col-libelle {
  width: var(--ecriture-col-libelle);
}

.ecriture-col-montant {
  width: var(--ecriture-col-montant);
}

.ecriture-col-actions {
  width: var(--ecriture-col-actions);
}

@media (max-width: 720px) {
  .ecriture-context-fields {
    grid-template-columns: 1fr;
  }

  .ecriture-context-actions {
    flex-direction: column;
  }
}

.saisie-row-editing td {
  background: #eff6ff;
}

.saisie-invalid {
  border-color: #dc2626 !important;
  background: #fef2f2;
}

.saisie-help {
  display: inline-block;
  margin-top: 0.25rem;
  color: #b91c1c;
  font-size: 0.78rem;
  font-weight: 600;
}
</style>
