<template>
  <ResourceList
    title="Périodes"
    subtitle="Périodes d'exercice"
    :fetcher="periodesApi.list"
    :columns="columns"
    :create-label="form.createLabel"
    :form-schema="form"
    :create-fn="periodesApi.create"
    :create-defaults="defaults"
  />
</template>

<script setup>
import { computed } from 'vue'
import ResourceList from '../components/ResourceList.vue'
import { periodesApi } from '../services/api'
import { resourceForms } from '../config/resourceForms'
import { useContextStore } from '../modules/context/store'

const context = useContextStore()
const form = resourceForms.periodes
const defaults = computed(() => ({
  exercice_id: context.exerciceId || null,
}))

const columns = [
  { key: 'code', label: 'Code', format: (r) => r.code || r.libelle || r.intitule || r.id },
  { key: 'exercice_id', label: 'Exercice' },
  { key: 'date_debut', label: 'Début', format: (r) => String(r.date_debut || '').slice(0, 10) },
  { key: 'date_fin', label: 'Fin', format: (r) => String(r.date_fin || '').slice(0, 10) },
  { key: 'cloturee', label: 'Clôturée', format: (r) => (r.cloturee ? 'Oui' : 'Non') },
]
</script>
