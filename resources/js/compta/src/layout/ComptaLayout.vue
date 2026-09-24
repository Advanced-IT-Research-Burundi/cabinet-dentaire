<template>
  <div class="compta-app">
    <aside class="compta-sidebar" :class="{ 'is-collapsed': collapsed, 'is-open': mobileOpen }">
      <div class="compta-sidebar-header">
        <h2 v-show="!collapsed || mobileOpen">Comptabilité</h2>
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

        <div class="compta-current-company">
          <span class="label">Entreprise</span>
          <strong>{{ societeLabel }}</strong>
          <small v-if="context.societeActive?.nif">NIF {{ context.societeActive.nif }}</small>
        </div>

        <div v-if="isSaisieRoute && saisieContextBar.ready" class="compta-saisie-context-top">
          <span><strong>Journal</strong> {{ saisieContextBar.journal }}</span>
          <span><strong>Exercice</strong> {{ saisieContextBar.exercice }}</span>
          <span><strong>Période</strong> {{ saisieContextBar.periode }}</span>
        </div>

        <template v-else-if="!isSaisieRoute">
          <div>
            <span class="label">Exercice</span>
            <select
              class="compta-select"
              :value="context.exerciceId || ''"
              @change="context.setExercice($event.target.value)"
            >
              <option value="">—</option>
              <option v-for="ex in context.exercices" :key="ex.id" :value="ex.id">
                {{ ex.code || ex.libelle || `Exercice #${ex.id}` }}
              </option>
            </select>
          </div>

          <div>
            <span class="label">Période</span>
            <select
              class="compta-select"
              :value="context.periodeId || ''"
              @change="context.setPeriode($event.target.value)"
            >
              <option value="">—</option>
              <option v-for="pe in context.periodesForExercice" :key="pe.id" :value="pe.id">
                {{ pe.code || pe.libelle || pe.intitule || `Période #${pe.id}` }}
              </option>
            </select>
          </div>
        </template>

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
