<template>
  <div>
    <div class="compta-page-header">
      <div>
        <h1>Plan comptable</h1>
        <p>Recherche par numéro ou intitulé (SYSCOHADA)</p>
      </div>
    </div>

    <div class="compta-filters">
      <input
        v-model="q"
        class="compta-input"
        style="min-width: 260px"
        placeholder="Ex. 521 ou Banque…"
        @input="onSearch"
      />
      <label style="display: flex; align-items: center; gap: 0.35rem; font-size: 0.85rem">
        <input v-model="mouvementOnly" type="checkbox" @change="load" />
        Comptes de mouvement uniquement
      </label>
    </div>

    <div class="compta-card">
      <div v-if="loading" class="compta-loading">Chargement…</div>
      <div v-else-if="error" class="compta-error">{{ error }}</div>
      <div v-else class="compta-table-wrap">
        <table class="compta-table">
          <thead>
            <tr>
              <th>N°</th>
              <th>Intitulé</th>
              <th>Mouvement</th>
              <th>Collectif</th>
              <th>Actif</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="c in comptes" :key="c.id">
              <td>
                <span :style="{ paddingLeft: `${indent(c)}px`, fontWeight: c.mouvement ? 500 : 600 }">
                  {{ c.numero }}
                </span>
              </td>
              <td>{{ c.intitule }}</td>
              <td>{{ c.mouvement ? 'Oui' : 'Non' }}</td>
              <td>{{ c.collectif ? 'Oui' : 'Non' }}</td>
              <td>{{ c.actif ? 'Oui' : 'Non' }}</td>
            </tr>
            <tr v-if="!comptes.length">
              <td colspan="5" class="compta-empty">Aucun compte</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-if="pagination" style="display: flex; gap: 0.5rem; justify-content: flex-end; margin-top: 0.75rem">
        <button type="button" class="compta-btn compta-btn-secondary" :disabled="page <= 1" @click="page--; load()">Précédent</button>
        <span style="align-self: center; font-size: 0.85rem; color: var(--compta-muted)">
          {{ pagination.current_page }} / {{ pagination.last_page }} ({{ pagination.total }})
        </span>
        <button type="button" class="compta-btn compta-btn-secondary" :disabled="page >= pagination.last_page" @click="page++; load()">Suivant</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { comptesApi } from '../services/api'

const comptes = ref([])
const pagination = ref(null)
const loading = ref(false)
const error = ref(null)
const q = ref('')
const mouvementOnly = ref(true)
const page = ref(1)
let debounce = null

function indent(c) {
  const len = String(c.numero || '').length
  return Math.max(0, (len - 1) * 6)
}

function onSearch() {
  clearTimeout(debounce)
  debounce = setTimeout(() => {
    page.value = 1
    load()
  }, 300)
}

async function load() {
  loading.value = true
  error.value = null
  try {
    const res = await comptesApi.list({
      page: page.value,
      per_page: 50,
      q: q.value || undefined,
      mouvement: mouvementOnly.value ? 1 : undefined,
    })
    comptes.value = Array.isArray(res.data) ? res.data : []
    pagination.value = res.pagination
  } catch (e) {
    error.value = e.message
  } finally {
    loading.value = false
  }
}

onMounted(load)
</script>
