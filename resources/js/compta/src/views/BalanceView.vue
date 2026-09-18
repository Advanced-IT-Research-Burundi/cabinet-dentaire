<template>
  <div>
    <div class="compta-page-header">
      <div>
        <h1>Balance de vérification</h1>
        <p>Soldes par compte pour l'exercice actif</p>
      </div>
      <button type="button" class="compta-btn compta-btn-secondary" @click="load">Actualiser</button>
    </div>

    <div class="compta-card">
      <div v-if="loading" class="compta-loading">Chargement…</div>
      <div v-else-if="error" class="compta-error">{{ error }}</div>
      <div v-else class="compta-table-wrap">
        <table class="compta-table">
          <thead>
            <tr>
              <th>Compte</th>
              <th>Intitulé</th>
              <th>Débit</th>
              <th>Crédit</th>
              <th>Solde</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="row in rows" :key="row.compte_id">
              <td>{{ row.numero }}</td>
              <td>{{ row.intitule }}</td>
              <td>{{ fmt(row.debit) }}</td>
              <td>{{ fmt(row.credit) }}</td>
              <td>{{ fmt(row.solde) }}</td>
            </tr>
            <tr v-if="!rows.length">
              <td colspan="5" class="compta-empty">Aucune écriture pour cet exercice</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref, watch } from 'vue'
import { rapportsApi } from '../services/api'
import { useContextStore } from '../modules/context/store'

const context = useContextStore()
const rows = ref([])
const loading = ref(false)
const error = ref(null)

function fmt(n) {
  return new Intl.NumberFormat('fr-BI', { minimumFractionDigits: 2 }).format(Number(n) || 0)
}

async function load() {
  if (!context.exerciceId) {
    rows.value = []
    return
  }
  loading.value = true
  error.value = null
  try {
    const res = await rapportsApi.balance({ exercice_id: context.exerciceId })
    rows.value = Array.isArray(res.data) ? res.data : []
  } catch (e) {
    error.value = e.message
  } finally {
    loading.value = false
  }
}

onMounted(load)
watch(() => context.exerciceId, load)
</script>
