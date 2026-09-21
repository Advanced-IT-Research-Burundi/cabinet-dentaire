<template>
  <ResourceList
    title="Types de journaux"
    subtitle="Catégories requises pour créer un journal"
    :fetcher="typeJournalsApi.list"
    :columns="columns"
    :create-label="form.createLabel"
    :edit-label="form.editLabel"
    :form-schema="form"
    :create-fn="typeJournalsApi.create"
    :update-fn="typeJournalsApi.update"
    :delete-fn="typeJournalsApi.remove"
  />
</template>

<script setup>
import ResourceList from '../components/ResourceList.vue'
import { typeJournalsApi } from '../services/api'
import { resourceForms } from '../config/resourceForms'

const form = resourceForms.typeJournaux
const columns = [
  { key: 'code', label: 'Code' },
  { key: 'intitule', label: 'Intitulé' },
  { key: 'actif', label: 'Actif', format: (r) => (r.actif ? 'Oui' : 'Non') },
]
</script>
