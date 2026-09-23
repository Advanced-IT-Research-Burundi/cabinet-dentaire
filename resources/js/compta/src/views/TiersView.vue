<template>
  <ResourceList
    title="Tiers"
    subtitle="Clients, fournisseurs et autres tiers"
    searchable
    :fetcher="tiersApi.list"
    :columns="columns"
    :create-label="form.createLabel"
    :edit-label="form.editLabel"
    :form-schema="form"
    :create-fn="tiersApi.create"
    :update-fn="tiersApi.update"
    :delete-fn="tiersApi.remove"
  />
</template>

<script setup>
import ResourceList from '../components/ResourceList.vue'
import { tiersApi } from '../services/api'
import { resourceForms } from '../config/resourceForms'

const form = resourceForms.tiers
const columns = [
  { key: 'intitule', label: 'Intitulé', format: (r) => r.intitule || r.raison_sociale || r.nom },
  { key: 'nom_complet', label: 'Nom complet', format: (r) => r.nom_complet || '—' },
  { key: 'contact', label: 'Contact', format: (r) => r.contact || '—' },
  { key: 'abrege', label: 'Abréviation', format: (r) => r.abrege || '—' },
  { key: 'type_tiers_id', label: 'Type', format: (r) => r.type_tiers?.intitule || r.type_tiers_id || '—' },
]
</script>
