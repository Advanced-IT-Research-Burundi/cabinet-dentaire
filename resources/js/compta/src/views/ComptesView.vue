<template>
  <div>
    <div class="compta-page-header">
      <div>
        <h1>Plan comptable</h1>
        <p>Recherche par numéro ou intitulé (SYSCOHADA)</p>
      </div>
      <button type="button" class="compta-btn compta-btn-primary" @click="showCreate = true">
        <i class="bi bi-plus-lg"></i> {{ form.createLabel }}
      </button>
    </div>

    <div class="compta-filters">
      <input
        v-model="q"
        class="compta-input"
        style="min-width: 260px"
        placeholder="Ex. 521 ou Banque…"
        @input="onSearch"
      />
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
            </tr>
          </thead>
          <tbody>
            <tr v-for="c in comptes" :key="c.id">
              <td>{{ c.numero }}</td>
              <td>{{ c.intitule }}</td>
            </tr>
            <tr v-if="!comptes.length">
              <td colspan="2" class="compta-empty">Aucun compte</td>
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

    <ResourceFormModal
      :open="showCreate"
      :title="form.createLabel"
      :fields="form.fields"
      :create-fn="comptesApi.create"
      @close="showCreate = false"
      @created="onCreated"
    />
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { comptesApi } from '../services/api'
import { resourceForms } from '../config/resourceForms'
import ResourceFormModal from '../components/ResourceFormModal.vue'

const form = resourceForms.comptes
const comptes = ref([])
const pagination = ref(null)
const loading = ref(false)
const error = ref(null)
const q = ref('')
const page = ref(1)
const showCreate = ref(false)
let debounce = null

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
    })
    comptes.value = Array.isArray(res.data) ? res.data : []
    pagination.value = res.pagination
  } catch (e) {
    error.value = e.message
  } finally {
    loading.value = false
  }
}

function onCreated() {
  showCreate.value = false
  load()
}

onMounted(load)
</script>
