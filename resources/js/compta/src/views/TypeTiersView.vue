<template>
  <ResourceList
    title="Types de tiers"
    subtitle="Catégories requises pour créer un tiers"
    :fetcher="typeTiersApi.list"
    :columns="columns"
    :create-label="form.createLabel"
    :edit-label="form.editLabel"
    :form-schema="form"
    :create-fn="typeTiersApi.create"
    :update-fn="typeTiersApi.update"
    :delete-fn="typeTiersApi.remove"
  />
</template>

<script setup>
import ResourceList from '../components/ResourceList.vue'
import { typeTiersApi } from '../services/api'
import { resourceForms } from '../config/resourceForms'

const form = resourceForms.typeTiers
const columns = [
  { key: 'code', label: 'Code' },
  { key: 'intitule', label: 'Intitulé' },
  { key: 'actif', label: 'Actif', format: (r) => (r.actif ? 'Oui' : 'Non') },
]
</script>
