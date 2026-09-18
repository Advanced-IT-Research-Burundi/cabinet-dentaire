<template>
  <div>
    <div class="compta-page-header">
      <div>
        <h1>Budgets</h1>
        <p>Suivi prévu / engagé / réalisé</p>
      </div>
      <div style="display: flex; gap: 0.5rem; flex-wrap: wrap">
        <button type="button" class="compta-btn compta-btn-primary" @click="showCreate = true">
          <i class="bi bi-plus-lg"></i> {{ form.createLabel }}
        </button>
        <button type="button" class="compta-btn compta-btn-secondary" @click="load">Actualiser</button>
      </div>
    </div>

    <div class="compta-card">
      <div v-if="loading" class="compta-loading">Chargement…</div>
      <div v-else-if="error" class="compta-error">{{ error }}</div>
      <div v-else class="compta-table-wrap">
        <table class="compta-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Code</th>
              <th>Intitulé</th>
              <th>Département</th>
              <th>Poste</th>
              <th>Prévu</th>
              <th>Révisé</th>
              <th>Engagé</th>
              <th>Réalisé</th>
              <th>% Réalisé</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="b in budgets" :key="b.id">
              <td>{{ b.id }}</td>
              <td>{{ b.code || '—' }}</td>
              <td>{{ b.intitule || '—' }}</td>
              <td>{{ b.departement_id }}</td>
              <td>{{ b.poste_budgetaire_id }}</td>
              <td>{{ fmt(b.montant_prevu) }}</td>
              <td>{{ fmt(b.montant_revise) }}</td>
              <td>{{ fmt(b.montant_engage) }}</td>
              <td>{{ fmt(b.montant_realise) }}</td>
              <td>
                <div style="background: #eef2f7; border-radius: 999px; height: 8px; width: 80px; overflow: hidden">
                  <div
                    :style="{
                      width: `${Math.min(pct(b), 100)}%`,
                      height: '100%',
                      background: 'linear-gradient(135deg, #1e73be, #6ca8ec)',
                    }"
                  ></div>
                </div>
                <small>{{ pct(b) }}%</small>
              </td>
            </tr>
            <tr v-if="!budgets.length">
              <td colspan="10" class="compta-empty">Aucun budget</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <ResourceFormModal
      :open="showCreate"
      :title="form.createLabel"
      :fields="form.fields"
      :create-fn="budgetsApi.create"
      :defaults="createDefaults"
      @close="showCreate = false"
      @created="onCreated"
    />
  </div>
</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue'
import { budgetsApi } from '../services/api'
import { resourceForms } from '../config/resourceForms'
import { useContextStore } from '../modules/context/store'
import ResourceFormModal from '../components/ResourceFormModal.vue'

const form = resourceForms.budgets
const context = useContextStore()
const budgets = ref([])
const loading = ref(false)
const error = ref(null)
const showCreate = ref(false)

const createDefaults = computed(() => ({
  exercice_id: context.exerciceId || null,
}))

function fmt(n) {
  return new Intl.NumberFormat('fr-BI').format(Number(n) || 0)
}

function pct(b) {
  const base = Number(b.montant_revise) || Number(b.montant_prevu) || 0
  if (!base) return 0
  return Math.round((Number(b.montant_realise) / base) * 1000) / 10
}

async function load() {
  loading.value = true
  error.value = null
  try {
    const res = await budgetsApi.list({
      exercice_id: context.exerciceId || undefined,
    })
    budgets.value = Array.isArray(res.data) ? res.data : []
  } catch (e) {
    error.value = e.message
  } finally {
    loading.value = false
  }
}

function onCreated() {
  // #region agent log
  fetch('http://127.0.0.1:7845/ingest/d75feb9c-36a3-4797-b93e-748750fb52bb',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'5fa0d4'},body:JSON.stringify({sessionId:'5fa0d4',runId:'crud-create',hypothesisId:'H2',location:'BudgetsView.vue:onCreated',message:'budget created refresh',data:{},timestamp:Date.now()})}).catch(()=>{});
  // #endregion
  showCreate.value = false
  load()
}

onMounted(load)
watch(() => context.exerciceId, load)
</script>
