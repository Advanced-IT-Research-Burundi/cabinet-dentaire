<template>
  <div>
    <div class="compta-page-header">
      <div>
        <h1>{{ title }}</h1>
        <p>{{ subtitle }}</p>
      </div>
      <button type="button" class="compta-btn compta-btn-secondary" @click="load" :disabled="loading">
        <i class="pi pi-refresh"></i>
      </button>
    </div>

    <div class="compta-filters" v-if="searchable">
      <input v-model="q" class="compta-input" placeholder="Rechercher…" @keyup.enter="load" />
      <button type="button" class="compta-btn compta-btn-secondary" @click="load">Filtrer</button>
    </div>

    <div class="compta-card">
      <div v-if="loading" class="compta-loading">Chargement…</div>
      <div v-else-if="error" class="compta-error">{{ error }}</div>
      <div v-else class="compta-table-wrap">
        <table class="compta-table">
          <thead>
            <tr>
              <th v-for="col in columns" :key="col.key">{{ col.label }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="row in rows" :key="row.id">
              <td v-for="col in columns" :key="col.key">{{ cell(row, col) }}</td>
            </tr>
            <tr v-if="!rows.length">
              <td :colspan="columns.length" class="compta-empty">Aucune donnée</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref, watch } from 'vue'

const props = defineProps({
  title: String,
  subtitle: { type: String, default: '' },
  fetcher: { type: Function, required: true },
  columns: { type: Array, required: true },
  searchable: { type: Boolean, default: false },
  watchExercice: { type: Boolean, default: false },
  exerciceId: { type: [Number, null], default: null },
})

const rows = ref([])
const loading = ref(false)
const error = ref(null)
const q = ref('')

function cell(row, col) {
  if (col.format) return col.format(row)
  const parts = col.key.split('.')
  let v = row
  for (const p of parts) v = v?.[p]
  return v ?? '—'
}

async function load() {
  loading.value = true
  error.value = null
  try {
    const res = await props.fetcher({ q: q.value || undefined })
    rows.value = Array.isArray(res?.data) ? res.data : Array.isArray(res) ? res : []
  } catch (e) {
    error.value = e.message
  } finally {
    loading.value = false
  }
}

onMounted(load)
watch(() => props.exerciceId, () => { if (props.watchExercice) load() })
</script>
