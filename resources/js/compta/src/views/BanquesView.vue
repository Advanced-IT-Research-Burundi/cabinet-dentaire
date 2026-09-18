<template>
  <ResourceList
    title="Banques"
    subtitle="Comptes bancaires"
    :fetcher="banquesApi.list"
    :columns="columns"
  />
</template>

<script setup>
import ResourceList from '../components/ResourceList.vue'
import { banquesApi } from '../services/api'

const columns = [
  { key: 'code', label: 'Code', format: (r) => r.code || r.id },
  { key: 'intitule', label: 'Intitulé', format: (r) => r.intitule || r.nom || r.libelle },
  { key: 'iban', label: 'IBAN', format: (r) => r.iban || r.numero_compte || '—' },
]
</script>
