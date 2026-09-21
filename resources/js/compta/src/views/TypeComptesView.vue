<template>
  <ResourceList
    title="Types de comptes"
    subtitle="Catégories requises pour créer un compte"
    :fetcher="typeComptesApi.list"
    :columns="columns"
    :create-label="form.createLabel"
    :edit-label="form.editLabel"
    :form-schema="form"
    :create-fn="typeComptesApi.create"
    :update-fn="typeComptesApi.update"
    :delete-fn="typeComptesApi.remove"
  />
</template>

<script setup>
import ResourceList from '../components/ResourceList.vue'
import { typeComptesApi } from '../services/api'
import { resourceForms } from '../config/resourceForms'

const form = resourceForms.typeComptes
const columns = [
  { key: 'code', label: 'Code' },
  { key: 'intitule', label: 'Intitulé' },
  { key: 'actif', label: 'Actif', format: (r) => (r.actif ? 'Oui' : 'Non') },
]
</script>
