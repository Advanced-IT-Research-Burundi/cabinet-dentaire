<template>
  <ResourceList
    title="Périodes"
    subtitle="Périodes d'exercice"
    :fetcher="periodesApi.list"
    :columns="columns"
  />
</template>

<script setup>
import ResourceList from '../components/ResourceList.vue'
import { periodesApi } from '../services/api'

const columns = [
  { key: 'code', label: 'Code', format: (r) => r.code || r.intitule || r.id },
  { key: 'exercice_id', label: 'Exercice' },
  { key: 'date_debut', label: 'Début', format: (r) => String(r.date_debut || '').slice(0, 10) },
  { key: 'date_fin', label: 'Fin', format: (r) => String(r.date_fin || '').slice(0, 10) },
  { key: 'cloturee', label: 'Clôturée', format: (r) => (r.cloturee ? 'Oui' : 'Non') },
]
</script>
