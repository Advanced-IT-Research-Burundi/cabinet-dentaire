<template>
  <ResourceList
    title="Types de budgets"
    subtitle="Catégories requises pour créer un poste budgétaire"
    :fetcher="typeBudgetsApi.list"
    :columns="columns"
    :create-label="form.createLabel"
    :edit-label="form.editLabel"
    :form-schema="form"
    :create-fn="typeBudgetsApi.create"
    :update-fn="typeBudgetsApi.update"
    :delete-fn="typeBudgetsApi.remove"
  />
</template>

<script setup>
import ResourceList from '../components/ResourceList.vue'
import { typeBudgetsApi } from '../services/api'
import { resourceForms } from '../config/resourceForms'

const form = resourceForms.typeBudgets
const columns = [
  { key: 'code', label: 'Code' },
  { key: 'intitule', label: 'Intitulé' },
  { key: 'actif', label: 'Actif', format: (r) => (r.actif ? 'Oui' : 'Non') },
]
</script>
