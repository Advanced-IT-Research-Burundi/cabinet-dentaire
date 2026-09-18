<template>
  <ResourceList
    title="Journaux"
    subtitle="Journaux de saisie"
    :fetcher="journalsApi.list"
    :columns="columns"
    :create-label="form.createLabel"
    :form-schema="form"
    :create-fn="journalsApi.create"
  />
</template>

<script setup>
import ResourceList from '../components/ResourceList.vue'
import { journalsApi } from '../services/api'
import { resourceForms } from '../config/resourceForms'

const form = resourceForms.journaux
const columns = [
  { key: 'code', label: 'Code' },
  { key: 'intitule', label: 'Intitulé', format: (r) => r.intitule || r.libelle },
  { key: 'type_journal_id', label: 'Type' },
]
</script>
