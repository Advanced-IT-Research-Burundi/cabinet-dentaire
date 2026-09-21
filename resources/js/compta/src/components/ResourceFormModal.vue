<template>
  <div v-if="open" class="compta-modal-backdrop" @click.self="emit('close')">
    <div class="compta-modal" role="dialog" aria-modal="true">
      <div class="compta-modal-header">
        <h2>{{ title }}</h2>
        <button type="button" class="compta-btn compta-btn-ghost" @click="emit('close')">
          <i class="bi bi-x-lg"></i>
        </button>
      </div>

      <form class="compta-modal-body" @submit.prevent="submit">
        <div v-if="formError" class="compta-error" style="padding: 0.5rem 0">{{ formError }}</div>

        <div class="compta-form-grid">
          <div v-for="field in fields" :key="field.key" class="compta-field">
            <label>
              {{ field.label }}
              <span v-if="field.required" style="color: #b91c1c">*</span>
            </label>

            <select
              v-if="field.type === 'lookup'"
              v-model="form[field.key]"
              class="compta-select"
              style="width: 100%"
              :required="field.required"
            >
              <option :value="null">—</option>
              <option v-for="opt in lookups[field.lookup] || []" :key="opt.id" :value="opt.id">
                {{ optionLabel(opt) }}
              </option>
            </select>

            <label v-else-if="field.type === 'boolean'" class="compta-check">
              <input v-model="form[field.key]" type="checkbox" />
              Oui
            </label>

            <input
              v-else
              v-model="form[field.key]"
              class="compta-input"
              style="width: 100%"
              :type="field.type === 'number' ? 'number' : field.type === 'date' ? 'date' : field.type === 'email' ? 'email' : 'text'"
              :step="field.type === 'number' ? '0.01' : undefined"
              :required="field.required"
            />
          </div>
        </div>

        <div class="compta-modal-footer">
          <button type="button" class="compta-btn compta-btn-secondary" @click="emit('close')">Annuler</button>
          <button type="submit" class="compta-btn compta-btn-primary" :disabled="saving">
            {{ saving ? 'Enregistrement…' : 'Enregistrer' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, reactive, ref, watch } from 'vue'
import * as api from '../services/api'

const props = defineProps({
  open: { type: Boolean, default: false },
  title: { type: String, required: true },
  fields: { type: Array, required: true },
  createFn: { type: Function, default: null },
  updateFn: { type: Function, default: null },
  record: { type: Object, default: null },
  defaults: { type: Object, default: () => ({}) },
})

const emit = defineEmits(['close', 'created', 'updated', 'saved'])

const form = reactive({})
const lookups = reactive({})
const saving = ref(false)
const formError = ref(null)

const isEdit = computed(() => !!props.record?.id)

function optionLabel(opt) {
  return (
    opt.code
      ? `${opt.code} — ${opt.intitule || opt.libelle || opt.raison_sociale || ''}`
      : opt.numero
        ? `${opt.numero} — ${opt.intitule || ''}`
        : opt.intitule || opt.libelle || opt.raison_sociale || `#${opt.id}`
  )
}

function resetForm() {
  props.fields.forEach((f) => {
    if (props.record && props.record[f.key] !== undefined && props.record[f.key] !== null) {
      form[f.key] = props.record[f.key]
    } else if (props.defaults[f.key] !== undefined) {
      form[f.key] = props.defaults[f.key]
    } else if (f.default !== undefined) {
      form[f.key] = f.default
    } else if (f.type === 'boolean') {
      form[f.key] = false
    } else if (f.type === 'number') {
      form[f.key] = 0
    } else if (f.type === 'lookup') {
      form[f.key] = null
    } else {
      form[f.key] = ''
    }
  })
  formError.value = null
}

async function loadLookups() {
  const needed = [...new Set(props.fields.filter((f) => f.type === 'lookup').map((f) => f.lookup))]
  const map = {
    typeJournals: api.typeJournalsApi,
    typeTiers: api.typeTiersApi,
    typeComptes: api.typeComptesApi,
    typeBudgets: api.typeBudgetsApi,
    comptes: api.comptesApi,
    exercices: api.exercicesApi,
    societes: api.societesApi,
    departements: api.departementsApi,
    sections: api.sectionsApi,
    postes: api.postesApi,
  }
  await Promise.all(
    needed.map(async (key) => {
      const client = map[key]
      if (!client) return
      try {
        const res = await client.list({ per_page: 500 })
        lookups[key] = Array.isArray(res.data) ? res.data : []
      } catch {
        lookups[key] = []
      }
    })
  )
}

function buildPayload() {
  const payload = {}
  props.fields.forEach((f) => {
    let val = form[f.key]
    if (f.type === 'lookup' && (val === '' || val === null)) {
      val = null
    } else if (f.type === 'lookup' && val != null) {
      val = Number(val)
    } else if (f.type === 'number') {
      val = Number(val) || 0
    } else if (f.type === 'boolean') {
      val = !!val
    }
    payload[f.key] = val
  })
  return payload
}

async function submit() {
  saving.value = true
  formError.value = null
  try {
    const payload = buildPayload()

    let result
    if (isEdit.value) {
      if (typeof props.updateFn !== 'function') throw new Error('Mise à jour non disponible')
      result = await props.updateFn(props.record.id, payload)
      emit('updated', result)
    } else {
      if (typeof props.createFn !== 'function') throw new Error('Création non disponible')
      result = await props.createFn(payload)
      emit('created', result)
    }
    emit('saved', result)
    emit('close')
  } catch (e) {
    formError.value = e.message
  } finally {
    saving.value = false
  }
}

watch(
  () => props.open,
  async (v) => {
    if (v) {
      resetForm()
      await loadLookups()
    }
  }
)

onMounted(() => {
  if (props.open) {
    resetForm()
    loadLookups()
  }
})
</script>

<style scoped>
.compta-modal-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(15, 23, 42, 0.45);
  z-index: 1050;
  display: flex;
  align-items: flex-start;
  justify-content: center;
  padding: 2rem 1rem;
  overflow-y: auto;
}
.compta-modal {
  background: #fff;
  border-radius: 12px;
  width: min(720px, 100%);
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
}
.compta-modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 1.25rem;
  border-bottom: 1px solid var(--compta-border, #e5e7eb);
}
.compta-modal-header h2 {
  margin: 0;
  font-size: 1.1rem;
}
.compta-modal-body {
  padding: 1.25rem;
}
.compta-modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 0.5rem;
  margin-top: 1rem;
  padding-top: 1rem;
  border-top: 1px solid var(--compta-border, #e5e7eb);
}
.compta-check {
  display: flex;
  align-items: center;
  gap: 0.4rem;
  font-size: 0.875rem;
  margin-top: 0.35rem;
}
</style>
