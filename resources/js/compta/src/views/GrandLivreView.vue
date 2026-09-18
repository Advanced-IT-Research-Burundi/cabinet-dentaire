<template>
  <div>
    <div class="compta-page-header">
      <div>
        <h1>Grand livre</h1>
        <p>Mouvements par compte</p>
      </div>
    </div>

    <div class="compta-filters">
      <select v-model="compteId" class="compta-select" style="min-width: 260px" @change="load">
        <option :value="null">Sélectionner un compte</option>
        <option v-for="c in context.comptes" :key="c.id" :value="c.id">
          {{ c.numero }} — {{ c.intitule }}
        </option>
      </select>
      <button type="button" class="compta-btn compta-btn-secondary" @click="load">Charger</button>
    </div>

    <div class="compta-card">
      <div v-if="loading" class="compta-loading">Chargement…</div>
      <div v-else-if="error" class="compta-error">{{ error }}</div>
      <div v-else class="compta-table-wrap">
        <table class="compta-table">
          <thead>
            <tr>
              <th>Date</th>
              <th>Pièce</th>
              <th>Libellé</th>
              <th>Débit</th>
              <th>Crédit</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="row in rows" :key="row.id">
              <td>{{ String(row.date_ecriture || '').slice(0, 10) }}</td>
              <td>{{ row.piece_id || row.piece_comptable_id }}</td>
              <td>{{ row.libelle }}</td>
              <td>{{ fmt(row.debit) }}</td>
              <td>{{ fmt(row.credit) }}</td>
            </tr>
            <tr v-if="!rows.length">
              <td colspan="5" class="compta-empty">Aucun mouvement</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { rapportsApi } from '../services/api'
import { useContextStore } from '../modules/context/store'

const context = useContextStore()
const compteId = ref(null)
const rows = ref([])
const loading = ref(false)
const error = ref(null)

function fmt(n) {
  return new Intl.NumberFormat('fr-BI', { minimumFractionDigits: 2 }).format(Number(n) || 0)
}

async function load() {
  if (!compteId.value || !context.exerciceId) {
    rows.value = []
    return
  }
  loading.value = true
  error.value = null
  try {
    const res = await rapportsApi.grandLivre({
      exercice_id: context.exerciceId,
      compte_id: compteId.value,
    })
    rows.value = Array.isArray(res.data) ? res.data : []
  } catch (e) {
    error.value = e.message
  } finally {
    loading.value = false
  }
}
</script>
