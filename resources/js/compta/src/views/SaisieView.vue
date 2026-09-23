<template>
  <div>
    <div class="compta-page-header">
      <div>
        <h1>{{ pieceId ? `Pièce #${form.numero_piece || pieceId}` : 'Nouvelle saisie' }}</h1>
        <p>Équilibre débit / crédit obligatoire avant enregistrement</p>
      </div>
      <div style="display: flex; gap: 0.5rem; flex-wrap: wrap">
        <RouterLink class="compta-btn compta-btn-secondary" :to="{ name: 'compta.pieces' }">Retour</RouterLink>
        <button type="button" class="compta-btn compta-btn-primary" :disabled="!canSave || saving" @click="save('brouillon')">
          Enregistrer
        </button>
        <button
          v-if="form.etat === 'brouillon' || !pieceId"
          type="button"
          class="compta-btn compta-btn-secondary"
          :disabled="!canSave || saving"
          @click="save('validee')"
        >
          Valider
        </button>
        <button
          v-if="form.etat === 'validee'"
          type="button"
          class="compta-btn compta-btn-primary"
          :disabled="saving"
          @click="save('comptabilisee')"
        >
          Comptabiliser
        </button>
      </div>
    </div>

    <div v-if="error" class="compta-error">{{ error }}</div>
    <div v-if="success" class="compta-card" style="margin-bottom: 1rem; border-color: #86efac; color: #065f46">
      {{ success }}
    </div>

    <div class="compta-card saisie-context">
      <div class="saisie-context-status" :class="{ ready: contextReady }">
        <span>{{ contextReady ? 'Contexte prêt' : 'Sélectionnez le journal et la période' }}</span>
      </div>

      <div class="compta-form-grid">
        <div class="compta-field">
          <label>Journal</label>
          <select v-model="form.journal_id" class="compta-select" style="width: 100%">
            <option :value="null">—</option>
            <option v-for="j in context.journals" :key="j.id" :value="j.id">
              {{ j.code }} — {{ j.intitule || j.libelle }}
            </option>
          </select>
        </div>

        <div class="compta-field">
          <label>Exercice</label>
          <select v-model="form.exercice_id" class="compta-select" style="width: 100%" @change="onExerciceChange">
            <option :value="null">—</option>
            <option v-for="exercice in context.exercices" :key="exercice.id" :value="exercice.id">
              {{ exercice.code || exercice.id }} — {{ exercice.date_debut }} / {{ exercice.date_fin }}
            </option>
          </select>
        </div>

        <div class="compta-field">
          <label>Période</label>
          <select v-model="form.periode_id" class="compta-select" style="width: 100%" :disabled="!form.exercice_id">
            <option :value="null">—</option>
            <option v-for="periode in periodesForForm" :key="periode.id" :value="periode.id">
              {{ periode.libelle || periode.code || `Période #${periode.id}` }}
            </option>
          </select>
        </div>
      </div>
    </div>

    <div class="compta-card" :class="{ 'saisie-disabled': !contextReady }">
      <div class="compta-form-grid">
        <div class="compta-field">
          <label>N° pièce</label>
          <input v-model="form.numero_piece" class="compta-input" style="width: 100%" :disabled="!contextReady" />
        </div>
        <div class="compta-field">
          <label>Date pièce</label>
          <input v-model="form.date_piece" type="date" class="compta-input" style="width: 100%" :disabled="!contextReady" />
        </div>
        <div class="compta-field">
          <label>Date comptable</label>
          <input v-model="form.date_comptable" type="date" class="compta-input" style="width: 100%" :disabled="!contextReady" />
        </div>
        <div class="compta-field" style="grid-column: 1 / -1">
          <label>Libellé</label>
          <input v-model="form.libelle" class="compta-input" style="width: 100%" :disabled="!contextReady" />
        </div>
      </div>

      <div class="compta-table-wrap compta-lines-grid">
        <table class="compta-table saisie-table">
          <thead>
            <tr>
              <th style="width: 3rem">#</th>
              <th>Compte</th>
              <th>Compte auxiliaire</th>
              <th>Libellé</th>
              <th style="width: 8rem">Débit</th>
              <th style="width: 8rem">Crédit</th>
              <th style="width: 3rem"></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(line, idx) in lines" :key="idx">
              <td>{{ idx + 1 }}</td>
              <td>
                <select
                  v-model="line.compte_id"
                  class="compta-select saisie-line-input"
                  :disabled="!contextReady"
                  @change="onCompteChange(line)"
                >
                  <option :value="null">—</option>
                  <option v-for="c in context.comptes" :key="c.id" :value="c.id">
                    {{ c.numero }} — {{ c.intitule }}
                  </option>
                </select>
              </td>
              <td>
                <select
                  v-model="line.compte_auxiliaire_id"
                  class="compta-select saisie-line-input"
                  :class="{ 'saisie-invalid': auxiliaryMissing(line) }"
                  :disabled="!contextReady || auxiliaryLocked(line)"
                >
                  <option :value="null">—</option>
                  <option v-for="tier in auxiliaryOptions(line)" :key="tier.id" :value="tier.id">
                    {{ tierLabel(tier) }}
                  </option>
                </select>
                <small v-if="auxiliaryMissing(line)" class="saisie-help">Auxiliaire obligatoire</small>
              </td>
              <td>
                <input v-model="line.libelle" class="compta-input saisie-line-input" :disabled="!contextReady" />
              </td>
              <td>
                <input
                  v-model.number="line.debit"
                  class="compta-input saisie-line-input"
                  type="number"
                  min="0"
                  step="0.01"
                  :disabled="!contextReady"
                  @input="normalizeAmounts(line, 'debit')"
                />
              </td>
              <td>
                <input
                  v-model.number="line.credit"
                  class="compta-input saisie-line-input"
                  type="number"
                  min="0"
                  step="0.01"
                  :disabled="!contextReady"
                  @input="normalizeAmounts(line, 'credit')"
                />
              </td>
              <td>
                <button
                  type="button"
                  class="compta-btn compta-btn-danger"
                  style="padding: 0.25rem 0.4rem"
                  :disabled="!contextReady"
                  @click="removeLine(idx)"
                >
                  <i class="pi pi-trash"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div style="margin-top: 0.75rem">
        <button type="button" class="compta-btn compta-btn-ghost" :disabled="!contextReady" @click="addLine">
          <i class="pi pi-plus"></i> Ligne
        </button>
      </div>

      <div class="compta-balance-bar" :class="balanced && linesHaveValidAuxiliaries ? 'ok' : 'ko'">
        <span>Total débit : <strong>{{ format(totalDebit) }}</strong></span>
        <span>Total crédit : <strong>{{ format(totalCredit) }}</strong></span>
        <span>Écart : <strong>{{ format(Math.abs(totalDebit - totalCredit)) }}</strong></span>
        <span v-if="!linesHaveValidAuxiliaries" class="saisie-help">Compte auxiliaire à compléter</span>
        <span style="margin-left: auto">
          <EtatBadge :etat="form.etat" />
          {{ balanced ? 'Équilibré' : 'Non équilibré' }}
        </span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, reactive, ref, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { piecesApi } from '../services/api'
import { useContextStore } from '../modules/context/store'
import EtatBadge from '../components/EtatBadge.vue'

const route = useRoute()
const router = useRouter()
const context = useContextStore()

const pieceId = computed(() => route.params.id ? Number(route.params.id) : null)
const saving = ref(false)
const error = ref(null)
const success = ref(null)

const today = new Date().toISOString().slice(0, 10)

const form = reactive({
  journal_id: null,
  exercice_id: null,
  periode_id: null,
  numero_piece: '',
  reference: null,
  date_piece: today,
  date_comptable: today,
  libelle: '',
  total_debit: 0,
  total_credit: 0,
  etat: 'brouillon',
})

const lines = ref([emptyLine(), emptyLine()])

function emptyLine() {
  return { compte_id: null, compte_auxiliaire_id: null, libelle: '', debit: 0, credit: 0 }
}

const periodesForForm = computed(() => {
  if (!form.exercice_id) return []
  return context.periodes.filter((p) => Number(p.exercice_id) === Number(form.exercice_id))
})

const contextReady = computed(() => !!form.journal_id && !!form.exercice_id && !!form.periode_id)

function addLine() {
  lines.value.push(emptyLine())
}

function removeLine(idx) {
  if (lines.value.length <= 2) return
  lines.value.splice(idx, 1)
}

function lineHasAmount(line) {
  return Number(line.debit) > 0 || Number(line.credit) > 0
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

const totalDebit = computed(() =>
  lines.value.reduce((s, l) => s + (Number(l.debit) || 0), 0)
)
const totalCredit = computed(() =>
  lines.value.reduce((s, l) => s + (Number(l.credit) || 0), 0)
)
const balanced = computed(() => Math.abs(totalDebit.value - totalCredit.value) < 0.005 && totalDebit.value > 0)
const validLines = computed(() => lines.value.filter((l) => l.compte_id && lineHasAmount(l)))
const linesHaveValidAuxiliaries = computed(() => validLines.value.every(validAuxiliary))
const canSave = computed(() => (
  contextReady.value &&
  balanced.value &&
  !!form.numero_piece &&
  validLines.value.length >= 2 &&
  linesHaveValidAuxiliaries.value
))

function tierLabel(tier) {
  return tier.intitule || tier.nom_complet || tier.abrege || `#${tier.id}`
}

function format(n) {
  return new Intl.NumberFormat('fr-BI', { minimumFractionDigits: 2 }).format(Number(n) || 0)
}

function normalizeAmounts(line, side) {
  if (side === 'debit' && Number(line.debit) > 0) line.credit = 0
  if (side === 'credit' && Number(line.credit) > 0) line.debit = 0
}

function onCompteChange(line) {
  if (!accountRequiresAuxiliary(line)) {
    line.compte_auxiliaire_id = null
    return
  }

  const exists = auxiliaryOptions(line).some((tier) => Number(tier.id) === Number(line.compte_auxiliaire_id))
  if (!exists) line.compte_auxiliaire_id = null
}

function onExerciceChange() {
  const periode =
    periodesForForm.value.find((p) => !p.cloturee) ||
    periodesForForm.value[0]

  form.periode_id = periode?.id ?? null
}

function applyDefaultContext() {
  if (!pieceId.value && !form.exercice_id && context.exerciceId) {
    form.exercice_id = context.exerciceId
  }

  if (!pieceId.value && !form.periode_id && context.periodeId) {
    form.periode_id = context.periodeId
  }
}

async function loadPiece() {
  if (!pieceId.value) return
  error.value = null
  try {
    const p = await piecesApi.get(pieceId.value)
    Object.assign(form, {
      journal_id: p.journal_id,
      exercice_id: p.exercice_id,
      periode_id: p.periode_id,
      numero_piece: p.numero_piece,
      reference: p.reference,
      date_piece: String(p.date_piece).slice(0, 10),
      date_comptable: String(p.date_comptable).slice(0, 10),
      libelle: p.libelle || '',
      etat: p.etat,
    })
    const ecritures = Array.isArray(p.ecritures) ? p.ecritures : (p.ecritures?.data || [])
    if (ecritures.length) {
      lines.value = ecritures.map((e) => ({
        compte_id: e.compte_id,
        compte_auxiliaire_id: e.compte_auxiliaire_id || e.tiers_id || null,
        libelle: e.libelle || '',
        debit: Number(e.debit) || 0,
        credit: Number(e.credit) || 0,
      }))
    }
  } catch (e) {
    error.value = e.message
  }
}

async function save(etat) {
  saving.value = true
  error.value = null
  success.value = null
  try {
    const payload = {
      journal_id: Number(form.journal_id),
      exercice_id: Number(form.exercice_id),
      periode_id: Number(form.periode_id),
      numero_piece: form.numero_piece,
      reference: form.reference,
      date_piece: form.date_piece,
      date_comptable: form.date_comptable,
      libelle: form.libelle,
      total_debit: totalDebit.value,
      total_credit: totalCredit.value,
      etat,
      date_validation: etat === 'validee' || etat === 'comptabilisee' ? new Date().toISOString() : null,
      date_comptabilisation: etat === 'comptabilisee' ? new Date().toISOString() : null,
      ecritures: validLines.value.map((l, i) => ({
        compte_id: Number(l.compte_id),
        compte_auxiliaire_id: l.compte_auxiliaire_id ? Number(l.compte_auxiliaire_id) : null,
        date_ecriture: form.date_comptable,
        numero_ligne: i + 1,
        libelle: l.libelle || form.libelle,
        debit: Number(l.debit) || 0,
        credit: Number(l.credit) || 0,
        rapprochement: false,
      })),
    }

    let saved
    if (pieceId.value) {
      saved = await piecesApi.update(pieceId.value, payload)
    } else {
      saved = await piecesApi.create(payload)
    }
    form.etat = saved.etat || etat
    success.value = 'Pièce enregistrée.'
    if (!pieceId.value && saved.id) {
      router.replace({ name: 'compta.saisie', params: { id: saved.id } })
    }
  } catch (e) {
    error.value = e.message
  } finally {
    saving.value = false
  }
}

onMounted(() => {
  applyDefaultContext()
  loadPiece()
})

watch(pieceId, loadPiece)
watch(() => context.exerciceId, applyDefaultContext)
watch(() => context.periodeId, applyDefaultContext)
</script>

<style scoped>
.saisie-context {
  margin-bottom: 1rem;
}

.saisie-context-status {
  display: inline-flex;
  align-items: center;
  min-height: 1.75rem;
  margin-bottom: 0.75rem;
  padding: 0.25rem 0.6rem;
  border: 1px solid #fca5a5;
  color: #991b1b;
  background: #fef2f2;
  border-radius: 6px;
  font-size: 0.875rem;
  font-weight: 600;
}

.saisie-context-status.ready {
  border-color: #86efac;
  color: #166534;
  background: #f0fdf4;
}

.saisie-disabled {
  opacity: 0.72;
}

.saisie-table th,
.saisie-table td {
  vertical-align: top;
}

.saisie-line-input {
  width: 100%;
  min-width: 150px;
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
