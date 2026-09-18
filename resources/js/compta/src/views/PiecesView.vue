<template>
  <div>
    <div class="compta-page-header">
      <div>
        <h1>Pièces comptables</h1>
        <p>Liste filtrable par état, journal et exercice</p>
      </div>
      <RouterLink class="compta-btn compta-btn-primary" :to="{ name: 'compta.saisie' }">
        <i class="pi pi-plus"></i> Nouvelle saisie
      </RouterLink>
    </div>

    <div class="compta-filters">
      <button
        v-for="chip in etatChips"
        :key="chip.value ?? 'all'"
        type="button"
        class="compta-chip"
        :class="{ active: etat === chip.value }"
        @click="etat = chip.value; load()"
      >
        {{ chip.label }}
      </button>
      <input
        v-model="q"
        class="compta-input"
        placeholder="Rechercher…"
        @keyup.enter="load"
      />
      <button type="button" class="compta-btn compta-btn-secondary" @click="load">Filtrer</button>
    </div>

    <div class="compta-card">
      <div v-if="loading" class="compta-loading">Chargement…</div>
      <div v-else-if="error" class="compta-error">{{ error }}</div>
      <div v-else class="compta-table-wrap">
        <table class="compta-table">
          <thead>
            <tr>
              <th>N°</th>
              <th>Journal</th>
              <th>Libellé</th>
              <th>Débit</th>
              <th>Crédit</th>
              <th>État</th>
              <th>Date</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="p in pieces" :key="p.id">
              <td>{{ p.numero_piece }}</td>
              <td>{{ p.journal?.code || p.journal_id }}</td>
              <td>{{ p.libelle }}</td>
              <td>{{ format(p.total_debit) }}</td>
              <td>{{ format(p.total_credit) }}</td>
              <td><EtatBadge :etat="p.etat" /></td>
              <td>{{ formatDate(p.date_comptable) }}</td>
              <td>
                <RouterLink
                  class="compta-btn compta-btn-ghost"
                  style="padding: 0.25rem 0.5rem"
                  :to="{ name: 'compta.saisie', params: { id: p.id } }"
                >
                  Ouvrir
                </RouterLink>
              </td>
            </tr>
            <tr v-if="!pieces.length">
              <td colspan="8" class="compta-empty">Aucune pièce</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-if="pagination" style="display: flex; gap: 0.5rem; justify-content: flex-end; margin-top: 0.75rem">
        <button
          type="button"
          class="compta-btn compta-btn-secondary"
          :disabled="pagination.current_page <= 1"
          @click="page--; load()"
        >
          Précédent
        </button>
        <span style="align-self: center; font-size: 0.85rem; color: var(--compta-muted)">
          Page {{ pagination.current_page }} / {{ pagination.last_page }}
        </span>
        <button
          type="button"
          class="compta-btn compta-btn-secondary"
          :disabled="pagination.current_page >= pagination.last_page"
          @click="page++; load()"
        >
          Suivant
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref, watch } from 'vue'
import { useRoute } from 'vue-router'
import { piecesApi } from '../services/api'
import { useContextStore } from '../modules/context/store'
import EtatBadge from '../components/EtatBadge.vue'

const route = useRoute()
const context = useContextStore()
const pieces = ref([])
const pagination = ref(null)
const loading = ref(false)
const error = ref(null)
const etat = ref(route.query.etat || null)
const q = ref('')
const page = ref(1)

const etatChips = [
  { label: 'Toutes', value: null },
  { label: 'Brouillon', value: 'brouillon' },
  { label: 'Validée', value: 'validee' },
  { label: 'Comptabilisée', value: 'comptabilisee' },
  { label: 'Annulée', value: 'annulee' },
]

function format(n) {
  if (n == null) return '—'
  return new Intl.NumberFormat('fr-BI').format(Number(n))
}

function formatDate(d) {
  if (!d) return '—'
  return String(d).slice(0, 10)
}

async function load() {
  loading.value = true
  error.value = null
  try {
    const params = {
      page: page.value,
      per_page: 20,
      q: q.value || undefined,
      etat: etat.value || undefined,
      exercice_id: context.exerciceId || undefined,
    }
    const res = await piecesApi.list(params)
    pieces.value = Array.isArray(res.data) ? res.data : []
    pagination.value = res.pagination
  } catch (e) {
    error.value = e.message
  } finally {
    loading.value = false
  }
}

onMounted(load)
watch(() => context.exerciceId, () => { page.value = 1; load() })
</script>
