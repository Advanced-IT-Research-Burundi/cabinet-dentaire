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
              <th v-if="hasActions" style="width: 1%; white-space: nowrap">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="row in rows" :key="row.id">
              <td v-for="col in columns" :key="col.key">{{ cell(row, col) }}</td>
              <td v-if="hasActions" style="white-space: nowrap">
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
                  v-for="action in visibleRowActions(row)"
                  :key="action.key || action.label"
                  type="button"
                  class="compta-btn compta-btn-ghost"
                  :title="action.label"
                  :aria-label="action.label"
                  :style="action.style || 'padding: 0.25rem 0.5rem'"
                  :disabled="runningActionKey === actionKey(action, row) || action.disabled?.(row)"
                  @click="runRowAction(action, row)"
                >
                  <i v-if="action.icon" :class="action.icon"></i>
                  <span v-if="!action.icon">{{ action.label }}</span>
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
              <td :colspan="columns.length + (hasActions ? 1 : 0)" class="compta-empty">Aucune donnée</td>
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
  rowActions: { type: Array, default: () => [] },
  createDefaults: { type: Object, default: () => ({}) },
})
const emit = defineEmits(['saved'])

const rows = ref([])
const loading = ref(false)
const error = ref(null)
const actionError = ref(null)
const q = ref('')
const modalOpen = ref(false)
const editing = ref(null)
const deletingId = ref(null)
const runningActionKey = ref(null)

const canEdit = computed(() => typeof props.updateFn === 'function' && !!props.formSchema)
const canDelete = computed(() => typeof props.deleteFn === 'function')
const canRunRowActions = computed(() => props.rowActions.length > 0)
const hasActions = computed(() => canEdit.value || canDelete.value || canRunRowActions.value)

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
  editing.value = null
  actionError.value = null
  modalOpen.value = true
}

function openEdit(row) {
  editing.value = { ...row }
  actionError.value = null
  modalOpen.value = true
}

function closeModal() {
  modalOpen.value = false
  editing.value = null
}

function onSaved() {
  closeModal()
  load()
  emit('saved')
}

function visibleRowActions(row) {
  return props.rowActions.filter((action) => !action.visible || action.visible(row))
}

function actionKey(action, row) {
  return `${action.key || action.label}:${row.id}`
}

async function runRowAction(action, row) {
  if (typeof action.handler !== 'function') return
  const message = typeof action.confirm === 'function' ? action.confirm(row) : action.confirm
  if (message && !confirm(message)) return

  const key = actionKey(action, row)
  runningActionKey.value = key
  actionError.value = null
  try {
    await action.handler(row)
    await load()
    emit('saved')
  } catch (e) {
    actionError.value = e.message
  } finally {
    runningActionKey.value = null
  }
}

async function removeRow(row) {
  if (!confirm(`Supprimer « ${row.code || row.intitule || row.id} » ?`)) return
  deletingId.value = row.id
  actionError.value = null
  try {
    await props.deleteFn(row.id)
    await load()
  } catch (e) {
    actionError.value = e.message
  } finally {
    deletingId.value = null
  }
}

onMounted(load)
watch(() => props.exerciceId, () => { if (props.watchExercice) load() })
</script>
