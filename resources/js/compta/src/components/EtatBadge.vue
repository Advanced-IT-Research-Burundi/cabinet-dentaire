<template>
  <span class="compta-badge" :class="etat || 'brouillon'">{{ label }}</span>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  etat: { type: String, default: 'brouillon' },
})

const labels = {
  brouillon: 'Brouillon',
  validee: 'Validée',
  comptabilisee: 'Comptabilisée',
  annulee: 'Annulée',
}

const label = computed(() => labels[props.etat] || props.etat)
</script>
