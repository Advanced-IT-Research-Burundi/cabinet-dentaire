<template>
  <div>
    <div class="compta-page-header">
      <div>
        <h1>Tableau de bord</h1>
        <p>Pilotage trésorerie, budget et pièces en cours</p>
      </div>
      <button type="button" class="compta-btn compta-btn-secondary" @click="load" :disabled="loading">
        <i class="pi pi-refresh"></i> Actualiser
      </button>
    </div>

    <div v-if="loading" class="compta-loading">Chargement…</div>
    <div v-else-if="error" class="compta-error">{{ error }}</div>
    <template v-else-if="dashboard">
      <div class="compta-kpi-grid">
        <KpiCard
          label="Trésorerie"
          :value="dashboard.kpis?.tresorerie_disponible"
          currency
        />
        <KpiCard
          label="Résultat net"
          :value="dashboard.kpis?.resultat_net"
          currency
        />
        <KpiCard
          label="Pièces brouillon"
          :value="dashboard.kpis?.pieces_en_brouillon"
        />
        <KpiCard
          label="Taux d'engagement"
          :value="`${dashboard.kpis?.budget?.taux_engagement ?? 0} %`"
          :hint="`Engagé ${format(dashboard.kpis?.budget?.engage)}`"
        />
      </div>

      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem">
        <div class="compta-card">
          <h3 style="margin: 0 0 0.75rem; font-size: 0.95rem">Prévision vs exécution</h3>
          <canvas ref="chartEl" height="180"></canvas>
        </div>

        <div class="compta-card">
          <h3 style="margin: 0 0 0.75rem; font-size: 0.95rem">Alertes clôture</h3>
          <p style="margin: 0 0 0.5rem; font-size: 0.875rem">
            Non rapprochées :
            <strong>{{ dashboard.rapprochement?.ecritures_non_rapprochees ?? 0 }}</strong>
          </p>
          <p style="margin: 0; font-size: 0.875rem">
            Non lettrées :
            <strong>{{ dashboard.rapprochement?.ecritures_non_lettrees ?? 0 }}</strong>
          </p>
          <div style="margin-top: 1rem">
            <RouterLink class="compta-btn compta-btn-primary" :to="{ name: 'compta.pieces', query: { etat: 'brouillon' } }">
              Voir brouillons
            </RouterLink>
          </div>
        </div>
      </div>

      <div class="compta-card" style="margin-top: 1rem">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem">
          <h3 style="margin: 0; font-size: 0.95rem">Dernières pièces</h3>
          <RouterLink class="compta-btn compta-btn-ghost" :to="{ name: 'compta.pieces' }">Toutes</RouterLink>
        </div>
        <div class="compta-table-wrap">
          <table class="compta-table">
            <thead>
              <tr>
                <th>N°</th>
                <th>Journal</th>
                <th>Libellé</th>
                <th>Montant</th>
                <th>État</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="p in dashboard.dernieres_pieces || []" :key="p.id">
                <td>{{ p.numero_piece }}</td>
                <td>{{ p.journal }}</td>
                <td>{{ p.libelle }}</td>
                <td>{{ format(p.montant) }}</td>
                <td><EtatBadge :etat="p.etat" /></td>
                <td>{{ p.date_comptable }}</td>
              </tr>
              <tr v-if="!(dashboard.dernieres_pieces || []).length">
                <td colspan="6" class="compta-empty">Aucune pièce récente</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="compta-card" style="margin-top: 1rem" v-if="(dashboard.suivi_budgetaire || []).length">
        <h3 style="margin: 0 0 0.75rem; font-size: 0.95rem">Suivi budgétaire par département</h3>
        <div class="compta-table-wrap">
          <table class="compta-table">
            <thead>
              <tr>
                <th>Département</th>
                <th>% Engagé</th>
                <th>% Réalisé</th>
                <th>Engagé</th>
                <th>Réalisé</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="row in dashboard.suivi_budgetaire" :key="row.departement_id">
                <td>{{ row.departement }}</td>
                <td>{{ row.pct_engage }}%</td>
                <td>{{ row.pct_realise }}%</td>
                <td>{{ format(row.montant_engage) }}</td>
                <td>{{ format(row.montant_realise) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </template>
  </div>
</template>

<script setup>
import { nextTick, onBeforeUnmount, onMounted, ref, watch } from 'vue'
import { dashboardApi } from '../services/api'
import { useContextStore } from '../modules/context/store'
import KpiCard from '../components/KpiCard.vue'
import EtatBadge from '../components/EtatBadge.vue'

const context = useContextStore()
const dashboard = ref(null)
const loading = ref(false)
const error = ref(null)
const chartEl = ref(null)
let chartInstance = null

function format(n) {
  if (n == null) return '—'
  return new Intl.NumberFormat('fr-BI').format(Number(n))
}

async function load() {
  loading.value = true
  error.value = null
  try {
    const params = {}
    // societe_id optional — API picks active exercice
    dashboard.value = await dashboardApi.get(params)
    await nextTick()
    await renderChart()
  } catch (e) {
    error.value = e.message
    dashboard.value = null
  } finally {
    loading.value = false
  }
}

async function renderChart() {
  if (!chartEl.value || !dashboard.value?.prevision_execution_trimestrielle) return
  const { Chart, registerables } = await import('chart.js')
  Chart.register(...registerables)
  if (chartInstance) chartInstance.destroy()

  const rows = dashboard.value.prevision_execution_trimestrielle
  chartInstance = new Chart(chartEl.value, {
    type: 'bar',
    data: {
      labels: rows.map((r) => r.trimestre),
      datasets: [
        {
          label: 'Prévu',
          data: rows.map((r) => r.prevu),
          backgroundColor: 'rgba(30, 115, 190, 0.35)',
        },
        {
          label: 'Réalisé',
          data: rows.map((r) => r.realise),
          backgroundColor: 'rgba(30, 115, 190, 0.9)',
        },
      ],
    },
    options: {
      responsive: true,
      plugins: { legend: { position: 'bottom' } },
      scales: { y: { beginAtZero: true } },
    },
  })
}

onMounted(load)
watch(() => context.exerciceId, load)
onBeforeUnmount(() => {
  if (chartInstance) chartInstance.destroy()
})
</script>
