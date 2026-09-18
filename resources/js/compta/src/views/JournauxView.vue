<template>
  <ResourceList
    title="Journaux"
    subtitle="Journaux de saisie"
    :fetcher="journalsApi.list"
    :columns="columns"
  />
</template>

<script setup>
import ResourceList from '../components/ResourceList.vue'
import { journalsApi } from '../services/api'

const columns = [
  { key: 'code', label: 'Code' },
  { key: 'intitule', label: 'Intitulé', format: (r) => r.intitule || r.libelle },
  { key: 'type_journal_id', label: 'Type' },
]
</script>
