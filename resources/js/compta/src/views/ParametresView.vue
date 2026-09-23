<template>
  <div>
    <div class="compta-page-header">
      <div>
        <h1>Paramètre</h1>
        <p>Société, exercice, période et journaux comptables</p>
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
      v-else-if="activeTab === 'periodes'"
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

    <ResourceList
      v-else-if="activeTab === 'journaux'"
      title="Journaux"
      subtitle="Journaux de saisie"
      searchable
      :fetcher="journalsApi.list"
      :columns="journalColumns"
      :create-label="forms.journaux.createLabel"
      :edit-label="forms.journaux.editLabel"
      :form-schema="forms.journaux"
      :create-fn="journalsApi.create"
      :update-fn="journalsApi.update"
      :delete-fn="journalsApi.remove"
      @saved="context.bootstrap"
    />

    <ResourceList
      v-else
      title="Types de journaux"
      subtitle="Catégories de journaux comptables"
      searchable
      :fetcher="typeJournalsApi.list"
      :columns="typeJournalColumns"
      :create-label="forms.typeJournaux.createLabel"
      :edit-label="forms.typeJournaux.editLabel"
      :form-schema="forms.typeJournaux"
      :create-fn="typeJournalsApi.create"
      :update-fn="typeJournalsApi.update"
      :delete-fn="typeJournalsApi.remove"
      @saved="context.bootstrap"
    />
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import ResourceList from '../components/ResourceList.vue'
import { exercicesApi, journalsApi, periodesApi, societesApi, typeJournalsApi } from '../services/api'
import { resourceForms } from '../config/resourceForms'
import { useContextStore } from '../modules/context/store'

const context = useContextStore()
const activeTab = ref('societes')
const forms = resourceForms

const tabs = [
  { key: 'societes', label: 'Société', icon: 'bi bi-building' },
  { key: 'exercices', label: 'Exercice', icon: 'bi bi-calendar3' },
  { key: 'periodes', label: 'Période', icon: 'bi bi-calendar-plus' },
  { key: 'journaux', label: 'Journaux', icon: 'bi bi-bookmark' },
//   { key: 'typeJournaux', label: 'Types de journaux', icon: 'bi bi-bookmark-star' },
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

const journalColumns = [
  { key: 'code', label: 'Code' },
  { key: 'intitule', label: 'Intitulé' },
  { key: 'type_journal_id', label: 'Type', format: (r) => r.type_journal?.intitule || r.type_journal_id || '—' },
]

const typeJournalColumns = [
  { key: 'code', label: 'Code' },
  { key: 'intitule', label: 'Intitulé' },
  { key: 'actif', label: 'Actif', format: (r) => (r.actif ? 'Oui' : 'Non') },
]
</script>
