<template>
  <ResourceList
    title="Exercices"
    subtitle="Exercices comptables"
    :fetcher="exercicesApi.list"
    :columns="columns"
  />
</template>

<script setup>
import ResourceList from '../components/ResourceList.vue'
import { exercicesApi } from '../services/api'

const columns = [
  { key: 'code', label: 'Code' },
  { key: 'date_debut', label: 'Début', format: (r) => String(r.date_debut || '').slice(0, 10) },
  { key: 'date_fin', label: 'Fin', format: (r) => String(r.date_fin || '').slice(0, 10) },
  { key: 'cloture', label: 'Clôturé', format: (r) => (r.cloture ? 'Oui' : 'Non') },
]
</script>
