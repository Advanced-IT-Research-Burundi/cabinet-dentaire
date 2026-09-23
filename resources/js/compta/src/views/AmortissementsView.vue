<template>
  <ResourceList
    title="Amortissements"
    subtitle="Dotations et valeurs nettes comptables"
    searchable
    :fetcher="amortissementsApi.list"
    :columns="columns"
    :create-label="form.createLabel"
    :edit-label="form.editLabel"
    :form-schema="form"
    :create-fn="amortissementsApi.create"
    :update-fn="amortissementsApi.update"
    :delete-fn="amortissementsApi.remove"
  />
</template>

<script setup>
import ResourceList from '../components/ResourceList.vue'
import { amortissementsApi } from '../services/api'
import { resourceForms } from '../config/resourceForms'

const form = resourceForms.amortissements
const columns = [
  { key: 'immobilisation_id', label: 'Immobilisation', format: (r) => r.immobilisation ? `${r.immobilisation.code} — ${r.immobilisation.libelle}` : r.immobilisation_id || '—' },
  { key: 'exercice_id', label: 'Exercice', format: (r) => r.exercice?.code || r.exercice_id || '—' },
  { key: 'periode_id', label: 'Période', format: (r) => r.periode?.libelle || r.periode_id || '—' },
  { key: 'type', label: 'Type', format: (r) => r.type === 'degressif' ? 'Dégressif' : 'Linéaire' },
  { key: 'date', label: 'Date', format: (r) => formatDate(r.date) },
  { key: 'montant', label: 'Montant', format: (r) => formatMoney(r.montant) },
  { key: 'cumul', label: 'Cumul', format: (r) => formatMoney(r.cumul) },
  { key: 'vnc', label: 'VNC', format: (r) => formatMoney(r.vnc) },
]

function formatDate(value) {
  return value ? String(value).slice(0, 10) : '—'
}

function formatMoney(value) {
  return new Intl.NumberFormat('fr-BI', { minimumFractionDigits: 2 }).format(Number(value) || 0)
}
</script>
