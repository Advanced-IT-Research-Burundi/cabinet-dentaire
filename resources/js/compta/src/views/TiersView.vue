<template>
  <ResourceList
    title="Tiers"
    subtitle="Clients, fournisseurs et autres tiers"
    searchable
    :fetcher="tiersApi.list"
    :columns="columns"
  />
</template>

<script setup>
import ResourceList from '../components/ResourceList.vue'
import { tiersApi } from '../services/api'

const columns = [
  { key: 'code', label: 'Code', format: (r) => r.code || r.numero || r.id },
  { key: 'intitule', label: 'Intitulé', format: (r) => r.intitule || r.raison_sociale || r.nom },
  { key: 'type_tiers_id', label: 'Type' },
]
</script>
