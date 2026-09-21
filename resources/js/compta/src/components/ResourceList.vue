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

    <div v-if="actionError" class="compta-error" style="margin-bottom: 0.75rem">{{ actionError }}</div>

    <div class="compta-card">
      <div v-if="loading" class="compta-loading">Chargement…</div>
      <div v-else-if="error" class="compta-error">{{ error }}</div>
      <div v-else class="compta-table-wrap">
        <table class="compta-table">
          <thead>
            <tr>
              <th v-for="col in columns" :key="col.key">{{ col.label }}</th>
              <th v-if="canEdit || canDelete" style="width: 1%; white-space: nowrap">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="row in rows" :key="row.id">
              <td v-for="col in columns" :key="col.key">{{ cell(row, col) }}</td>
              <td v-if="canEdit || canDelete" style="white-space: nowrap">
                <button
                  v-if="canEdit"
                  type="button"
                  class="compta-btn compta-btn-ghost"
                  style="padding: 0.25rem 0.5rem"
                  @click="openEdit(row)"
                >
                  <i class="bi bi-pencil"></i>
                </button>
                <button
                  v-if="canDelete"
                  type="button"
                  class="compta-btn compta-btn-ghost"
                  style="padding: 0.25rem 0.5rem; color: #b91c1c"
                  :disabled="deletingId === row.id"
                  @click="removeRow(row)"
                >
                  <i class="bi bi-trash"></i>
                </button>
              </td>
            </tr>
            <tr v-if="!rows.length">
              <td :colspan="columns.length + (canEdit || canDelete ? 1 : 0)" class="compta-empty">Aucune donnée</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <ResourceFormModal
      v-if="formSchema"
      :open="modalOpen"
      :title="modalTitle"
      :fields="formSchema.fields"
      :create-fn="createFn"
      :update-fn="updateFn"
      :record="editing"
      :defaults="createDefaults"
      @close="closeModal"
      @saved="onSaved"
    />
  </div>
</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue'
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
  editLabel: { type: String, default: 'Modifier' },
  formSchema: { type: Object, default: null },
  createFn: { type: Function, default: null },
  updateFn: { type: Function, default: null },
  deleteFn: { type: Function, default: null },
  createDefaults: { type: Object, default: () => ({}) },
})

const rows = ref([])
const loading = ref(false)
const error = ref(null)
const actionError = ref(null)
const q = ref('')
const modalOpen = ref(false)
const editing = ref(null)
const deletingId = ref(null)

const canEdit = computed(() => typeof props.updateFn === 'function' && !!props.formSchema)
const canDelete = computed(() => typeof props.deleteFn === 'function')

const modalTitle = computed(() => {
  if (editing.value) return props.editLabel || 'Modifier'
  return props.createLabel || 'Nouveau'
})

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
  fetch('http://127.0.0.1:7845/ingest/d75feb9c-36a3-4797-b93e-748750fb52bb',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'5fa0d4'},body:JSON.stringify({sessionId:'5fa0d4',runId:'types-crud',hypothesisId:'T3',location:'ResourceList.vue:openCreate',message:'nouveau clicked',data:{title:props.title},timestamp:Date.now()})}).catch(()=>{});
  // #endregion
  editing.value = null
  actionError.value = null
  modalOpen.value = true
}

function openEdit(row) {
  // #region agent log
  fetch('http://127.0.0.1:7845/ingest/d75feb9c-36a3-4797-b93e-748750fb52bb',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'5fa0d4'},body:JSON.stringify({sessionId:'5fa0d4',runId:'types-crud',hypothesisId:'T2',location:'ResourceList.vue:openEdit',message:'edit clicked',data:{title:props.title,id:row.id},timestamp:Date.now()})}).catch(()=>{});
  // #endregion
  editing.value = { ...row }
  actionError.value = null
  modalOpen.value = true
}

function closeModal() {
  modalOpen.value = false
  editing.value = null
}

function onSaved() {
  // #region agent log
  fetch('http://127.0.0.1:7845/ingest/d75feb9c-36a3-4797-b93e-748750fb52bb',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'5fa0d4'},body:JSON.stringify({sessionId:'5fa0d4',runId:'types-crud',hypothesisId:'T4',location:'ResourceList.vue:onSaved',message:'saved refresh',data:{title:props.title},timestamp:Date.now()})}).catch(()=>{});
  // #endregion
  closeModal()
  load()
}

async function removeRow(row) {
  if (!confirm(`Supprimer « ${row.code || row.intitule || row.id} » ?`)) return
  deletingId.value = row.id
  actionError.value = null
  try {
    // #region agent log
    fetch('http://127.0.0.1:7845/ingest/d75feb9c-36a3-4797-b93e-748750fb52bb',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'5fa0d4'},body:JSON.stringify({sessionId:'5fa0d4',runId:'types-crud',hypothesisId:'T5',location:'ResourceList.vue:removeRow',message:'delete start',data:{title:props.title,id:row.id},timestamp:Date.now()})}).catch(()=>{});
    // #endregion
    await props.deleteFn(row.id)
    await load()
  } catch (e) {
    // #region agent log
    fetch('http://127.0.0.1:7845/ingest/d75feb9c-36a3-4797-b93e-748750fb52bb',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'5fa0d4'},body:JSON.stringify({sessionId:'5fa0d4',runId:'types-crud',hypothesisId:'T5',location:'ResourceList.vue:removeRow:err',message:'delete failed',data:{title:props.title,id:row.id,error:String(e?.message||e)},timestamp:Date.now()})}).catch(()=>{});
    // #endregion
    actionError.value = e.message
  } finally {
    deletingId.value = null
  }
}

onMounted(load)
watch(() => props.exerciceId, () => { if (props.watchExercice) load() })
</script>
