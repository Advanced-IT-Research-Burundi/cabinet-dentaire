<template>
  <div class="compta-kpi">
    <div class="kpi-label">{{ label }}</div>
    <div class="kpi-value">{{ display }}</div>
    <div v-if="hint" style="font-size: 0.75rem; color: var(--compta-muted); margin-top: 0.25rem">
      {{ hint }}
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  label: String,
  value: [Number, String],
  hint: String,
  currency: { type: Boolean, default: false },
})

const display = computed(() => {
  if (props.value == null || props.value === '') return '—'
  if (props.currency) {
    return new Intl.NumberFormat('fr-BI', {
      style: 'decimal',
      minimumFractionDigits: 0,
      maximumFractionDigits: 0,
    }).format(Number(props.value))
  }
  return props.value
})
</script>
