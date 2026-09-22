<template>
  <ResourceList
    title="Tiers"
    subtitle="Clients, fournisseurs et autres tiers"
    searchable
    :fetcher="tiersApi.list"
    :columns="columns"
    :create-label="form.createLabel"
    :form-schema="form"
    :create-fn="tiersApi.create"
  />
</template>

<script setup>
import ResourceList from '../components/ResourceList.vue'
import { tiersApi } from '../services/api'
import { resourceForms } from '../config/resourceForms'

const form = resourceForms.tiers
const columns = [
  { key: 'code', label: 'No Compte', format: (r) => r.code || r.numero || r.id },
  { key: 'intitule', label: 'Intitulé', format: (r) => r.intitule || r.raison_sociale || r.nom },
  { key: 'contact', label: 'Contact' },
  { key: 'abrege', label: 'Abréviation' },
  { key: 'type_tiers_id', label: 'Type' },
]
</script>
