<template>
  <ResourceList
    title="Postes budgétaires"
    subtitle="Structure budgétaire"
    :fetcher="postesApi.list"
    :columns="columns"
    :create-label="form.createLabel"
    :form-schema="form"
    :create-fn="postesApi.create"
  />
</template>

<script setup>
import ResourceList from '../components/ResourceList.vue'
import { postesApi } from '../services/api'
import { resourceForms } from '../config/resourceForms'

const form = resourceForms.postes
const columns = [
  { key: 'code', label: 'Code', format: (r) => r.code || r.id },
  { key: 'intitule', label: 'Intitulé', format: (r) => r.intitule || r.libelle },
  { key: 'compte_id', label: 'Compte' },
]
</script>
