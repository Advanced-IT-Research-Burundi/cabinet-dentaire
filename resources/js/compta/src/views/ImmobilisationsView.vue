<template>
  <ResourceList
    title="Immobilisations"
    subtitle="Suivi des actifs immobilisés"
    searchable
    :fetcher="immobilisationsApi.list"
    :columns="columns"
    :create-label="form.createLabel"
    :edit-label="form.editLabel"
    :form-schema="form"
    :create-fn="immobilisationsApi.create"
    :update-fn="immobilisationsApi.update"
    :delete-fn="immobilisationsApi.remove"
  />
</template>

<script setup>
import ResourceList from '../components/ResourceList.vue'
import { immobilisationsApi } from '../services/api'
import { resourceForms } from '../config/resourceForms'

const form = resourceForms.immobilisations
const columns = [
  { key: 'code', label: 'Code' },
  { key: 'libelle', label: 'Libellé' },
  { key: 'societe_id', label: 'Société', format: (r) => r.societe?.raison_sociale || r.societe_id || '—' },
  { key: 'compte_id', label: 'Compte', format: (r) => r.compte ? `${r.compte.numero} — ${r.compte.intitule}` : r.compte_id || '—' },
  { key: 'date_acquisition', label: 'Acquisition', format: (r) => formatDate(r.date_acquisition) },
  { key: 'valeur_origine', label: 'Valeur', format: (r) => formatMoney(r.valeur_origine) },
  { key: 'duree_annees', label: 'Durée', format: (r) => `${r.duree_annees || 0} an(s)` },
  { key: 'statut', label: 'Statut', format: (r) => labelStatut(r.statut) },
]

function formatDate(value) {
  return value ? String(value).slice(0, 10) : '—'
}

function formatMoney(value) {
  return new Intl.NumberFormat('fr-BI', { minimumFractionDigits: 2 }).format(Number(value) || 0)
}

function labelStatut(value) {
  return {
    en_attente: 'En attente',
    en_service: 'En service',
    sortie: 'Sortie',
  }[value] || value || '—'
}
</script>
