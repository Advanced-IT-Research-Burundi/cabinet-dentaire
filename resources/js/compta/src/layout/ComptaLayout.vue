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
import { onMounted, ref } from 'vue'
import { navigation } from '../config/navigation'
import { useContextStore } from '../modules/context/store'

const context = useContextStore()
const collapsed = ref(false)
const mobileOpen = ref(false)

onMounted(() => {
  context.bootstrap()
})
</script>
