<template>
  <div class="compta-app">
    <aside class="compta-sidebar" :class="{ 'is-collapsed': collapsed, 'is-open': mobileOpen }">
      <div class="compta-sidebar-header">
        <div v-show="!collapsed || mobileOpen" class="compta-sidebar-company">
          <h2>{{ societeLabel }}</h2>
          <small v-if="context.societeActive?.nif">NIF {{ context.societeActive.nif }}</small>
        </div>
        <button
          type="button"
          class="compta-btn compta-btn-ghost"
          style="padding: 0.25rem 0.4rem; color: #fff; border-color: rgba(255,255,255,0.35)"
          @click="collapsed = !collapsed"
          :title="collapsed ? 'Étendre' : 'Réduire'"
        >
          <i :class="collapsed ? 'bi bi-chevron-right' : 'bi bi-chevron-left'"></i>
        </button>
      </div>
      <nav class="compta-nav">
        <div v-for="group in navigation" :key="group.label" class="compta-nav-group">
          <div v-show="!collapsed" class="compta-nav-group-label">{{ group.label }}</div>
          <RouterLink
            v-for="item in group.items"
            :key="item.label"
            :to="item.to"
            class="compta-nav-link"
            @click="mobileOpen = false"
          >
            <i :class="item.icon"></i>
            <span v-show="!collapsed || mobileOpen">{{ item.label }}</span>
          </RouterLink>
        </div>
      </nav>
    </aside>

    <div class="compta-main">
      <div class="compta-context-bar">
        <button
          type="button"
          class="compta-btn compta-btn-secondary compta-mobile-toggle"
          @click="mobileOpen = !mobileOpen"
        >
          <i class="bi bi-list"></i> Menu
        </button>

        <div v-if="isSaisieRoute && saisieContextBar.ready" class="compta-saisie-context-top">
          <span><strong>Journal</strong> {{ saisieContextBar.journal }}</span>
          <span><strong>Exercice</strong> {{ saisieContextBar.exercice }}</span>
          <span><strong>Période</strong> {{ saisieContextBar.periode }}</span>
        </div>

        <div v-else class="compta-saisie-context-top">
          <span><strong>Exercice</strong> {{ exerciceLabel }}</span>
          <span><strong>Période</strong> {{ periodeLabel }}</span>
        </div>

        <div v-if="context.error" class="compta-error" style="padding: 0; margin-left: auto">
          {{ context.error }}
        </div>
      </div>

      <div class="compta-page">
        <RouterView />
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, provide, reactive, ref } from 'vue'
import { useRoute } from 'vue-router'
import { navigation } from '../config/navigation'
import { useContextStore } from '../modules/context/store'

const context = useContextStore()
const route = useRoute()
const collapsed = ref(false)
const mobileOpen = ref(false)
const saisieContextBar = reactive({
  ready: false,
  journal: '',
  exercice: '',
  periode: '',
})
const societeLabel = computed(() => (
  context.societeActive?.raison_sociale ||
  context.societeActive?.intitule ||
  'Aucune entreprise'
))
const isSaisieRoute = computed(() => route.name === 'compta.saisie')
const exerciceLabel = computed(() => {
  const exercice = context.exerciceActif
  if (!exercice) return '—'
  const code = exercice.code || exercice.libelle || `#${exercice.id}`
  return `${code} — ${formatDate(exercice.date_debut)} / ${formatDate(exercice.date_fin)}`
})
const periodeLabel = computed(() => {
  const periode = context.periodeActive
  if (!periode) return '—'
  return periode.code || periode.libelle || periode.intitule || `#${periode.id}`
})

function formatDate(value) {
  return value ? String(value).slice(0, 10) : '—'
}

provide('saisieContextBar', {
  set(payload) {
    saisieContextBar.ready = !!payload?.ready
    saisieContextBar.journal = payload?.journal || ''
    saisieContextBar.exercice = payload?.exercice || ''
    saisieContextBar.periode = payload?.periode || ''
  },
  clear() {
    saisieContextBar.ready = false
    saisieContextBar.journal = ''
    saisieContextBar.exercice = ''
    saisieContextBar.periode = ''
  },
})

onMounted(() => {
  context.bootstrap()
})
</script>

<style scoped>
.compta-sidebar-company {
  min-width: 0;
}

.compta-sidebar-company h2 {
  margin: 0;
  font-size: 0.98rem;
  line-height: 1.15;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.compta-sidebar-company small {
  display: block;
  color: rgba(255, 255, 255, 0.72);
  font-size: 0.72rem;
  margin-top: 0.1rem;
}

.compta-saisie-context-top {
  display: flex;
  align-items: center;
  gap: 0.85rem;
  flex-wrap: wrap;
}

.compta-saisie-context-top span {
  display: inline-flex;
  gap: 0.35rem;
  align-items: baseline;
  color: #334155;
  font-size: 0.875rem;
  white-space: nowrap;
}

.compta-saisie-context-top strong {
  color: #64748b;
  font-size: 0.75rem;
  text-transform: uppercase;
}
</style>
