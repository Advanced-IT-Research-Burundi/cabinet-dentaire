<template>
  <div>
    <div class="compta-page-header">
      <div>
        <h1>Paramètre</h1>
        <p>Société, exercice et période comptable</p>
      </div>
    </div>

    <div class="compta-tabmenu" role="tablist" aria-label="Paramètres comptables">
      <button
        v-for="tab in tabs"
        :key="tab.key"
        type="button"
        class="compta-tab"
        :class="{ active: activeTab === tab.key }"
        role="tab"
        :aria-selected="activeTab === tab.key"
        @click="activeTab = tab.key"
      >
        <i :class="tab.icon"></i>
        <span>{{ tab.label }}</span>
      </button>
    </div>

    <ResourceList
      v-if="activeTab === 'societes'"
      title="Société"
      subtitle="Entreprise courante et informations fiscales"
      :fetcher="societesApi.list"
      :columns="societeColumns"
      :create-label="forms.societes.createLabel"
      :edit-label="forms.societes.editLabel"
      :form-schema="forms.societes"
      :create-fn="societesApi.create"
      :update-fn="societesApi.update"
      @saved="context.bootstrap"
    />

    <ResourceList
      v-else-if="activeTab === 'exercices'"
      title="Exercice"
      subtitle="Exercices comptables par société"
      :fetcher="exercicesApi.list"
      :columns="exerciceColumns"
      :create-label="forms.exercices.createLabel"
      :edit-label="forms.exercices.editLabel"
      :form-schema="forms.exercices"
      :create-fn="exercicesApi.create"
      :update-fn="exercicesApi.update"
      @saved="context.bootstrap"
    />

    <ResourceList
      v-else
      title="Période"
      subtitle="Périodes de l'exercice actif"
      :fetcher="periodesApi.list"
      :columns="periodeColumns"
      :create-label="forms.periodes.createLabel"
      :edit-label="forms.periodes.editLabel"
      :form-schema="forms.periodes"
      :create-fn="periodesApi.create"
      :update-fn="periodesApi.update"
      :create-defaults="periodeDefaults"
      @saved="context.bootstrap"
    />
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import ResourceList from '../components/ResourceList.vue'
import { exercicesApi, periodesApi, societesApi } from '../services/api'
import { resourceForms } from '../config/resourceForms'
import { useContextStore } from '../modules/context/store'

const context = useContextStore()
const activeTab = ref('societes')
const forms = resourceForms

const tabs = [
  { key: 'societes', label: 'Société', icon: 'bi bi-building' },
  { key: 'exercices', label: 'Exercice', icon: 'bi bi-calendar3' },
  { key: 'periodes', label: 'Période', icon: 'bi bi-calendar-plus' },
]

const periodeDefaults = computed(() => ({
  exercice_id: context.exerciceId || null,
}))

const societeColumns = [
  { key: 'raison_sociale', label: 'Raison sociale' },
  { key: 'nif', label: 'NIF' },
  { key: 'devise', label: 'Devise' },
  { key: 'actif', label: 'Actif', format: (r) => (r.actif ? 'Oui' : 'Non') },
]

const exerciceColumns = [
  { key: 'code', label: 'Code' },
  { key: 'societe.raison_sociale', label: 'Société', format: (r) => r.societe?.raison_sociale || r.societe_id || '—' },
  { key: 'date_debut', label: 'Début', format: (r) => String(r.date_debut || '').slice(0, 10) },
  { key: 'date_fin', label: 'Fin', format: (r) => String(r.date_fin || '').slice(0, 10) },
  { key: 'cloture', label: 'Clôturé', format: (r) => (r.cloture ? 'Oui' : 'Non') },
]

const periodeColumns = [
  { key: 'code', label: 'Code', format: (r) => r.code || r.libelle || r.intitule || r.id },
  { key: 'exercice_id', label: 'Exercice' },
  { key: 'date_debut', label: 'Début', format: (r) => String(r.date_debut || '').slice(0, 10) },
  { key: 'date_fin', label: 'Fin', format: (r) => String(r.date_fin || '').slice(0, 10) },
  { key: 'cloturee', label: 'Clôturée', format: (r) => (r.cloturee ? 'Oui' : 'Non') },
]
</script>
