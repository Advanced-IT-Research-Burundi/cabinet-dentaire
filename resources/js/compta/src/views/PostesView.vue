<template>
  <ResourceList
    title="Postes budgétaires"
    subtitle="Structure budgétaire"
    :fetcher="postesApi.list"
    :columns="columns"
  />
</template>

<script setup>
import ResourceList from '../components/ResourceList.vue'
import { postesApi } from '../services/api'

const columns = [
  { key: 'code', label: 'Code', format: (r) => r.code || r.id },
  { key: 'intitule', label: 'Intitulé', format: (r) => r.intitule || r.libelle },
  { key: 'compte_id', label: 'Compte' },
]
</script>
