<template>
  <div>
    <div class="compta-page-header">
      <div>
        <h1>{{ title }}</h1>
        <p>{{ subtitle }}</p>
      </div>
      <div style="display: flex; gap: 0.5rem; flex-wrap: wrap">
        <button
          v-if="createLabel && formSchema"
          type="button"
          class="compta-btn compta-btn-primary"
          @click="openCreate"
        >
          <i class="bi bi-plus-lg"></i> {{ createLabel }}
        </button>
        <button type="button" class="compta-btn compta-btn-secondary" @click="load" :disabled="loading">
          <i class="bi bi-arrow-clockwise"></i>
        </button>
      </div>
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

    <ResourceFormModal
      v-if="formSchema"
      :open="showCreate"
      :title="createLabel"
      :fields="formSchema.fields"
      :create-fn="createFn"
      :defaults="createDefaults"
      @close="showCreate = false"
      @created="onCreated"
    />
  </div>
</template>

<script setup>
import { onMounted, ref, watch } from 'vue'
import ResourceFormModal from './ResourceFormModal.vue'

const props = defineProps({
  title: String,
  subtitle: { type: String, default: '' },
  fetcher: { type: Function, required: true },
  columns: { type: Array, required: true },
  searchable: { type: Boolean, default: false },
  watchExercice: { type: Boolean, default: false },
  exerciceId: { type: [Number, null], default: null },
  createLabel: { type: String, default: '' },
  formSchema: { type: Object, default: null },
  createFn: { type: Function, default: null },
  createDefaults: { type: Object, default: () => ({}) },
})

const rows = ref([])
const loading = ref(false)
const error = ref(null)
const q = ref('')
const showCreate = ref(false)

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

function openCreate() {
  // #region agent log
  fetch('http://127.0.0.1:7845/ingest/d75feb9c-36a3-4797-b93e-748750fb52bb',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'5fa0d4'},body:JSON.stringify({sessionId:'5fa0d4',runId:'crud-create',hypothesisId:'H3',location:'ResourceList.vue:openCreate',message:'nouveau button clicked',data:{title:props.title,createLabel:props.createLabel,hasSchema:!!props.formSchema,hasCreateFn:typeof props.createFn==='function'},timestamp:Date.now()})}).catch(()=>{});
  // #endregion
  showCreate.value = true
}

function onCreated() {
  // #region agent log
  fetch('http://127.0.0.1:7845/ingest/d75feb9c-36a3-4797-b93e-748750fb52bb',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'5fa0d4'},body:JSON.stringify({sessionId:'5fa0d4',runId:'crud-create',hypothesisId:'H4',location:'ResourceList.vue:onCreated',message:'resource created refresh',data:{title:props.title},timestamp:Date.now()})}).catch(()=>{});
  // #endregion
  showCreate.value = false
  load()
}

onMounted(load)
watch(() => props.exerciceId, () => { if (props.watchExercice) load() })
</script>
