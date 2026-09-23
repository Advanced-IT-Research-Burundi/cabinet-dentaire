import { defineStore } from 'pinia'
import { comptesApi, exercicesApi, journalsApi, periodesApi, societesApi, tiersApi } from '../../services/api'

export const useContextStore = defineStore('comptaContext', {
  state: () => ({
    exercices: [],
    societes: [],
    periodes: [],
    journals: [],
    comptes: [],
    tiers: [],
    exerciceId: null,
    periodeId: null,
    loading: false,
    error: null,
  }),

  getters: {
    exerciceActif(state) {
      return state.exercices.find((e) => e.id === state.exerciceId) || null
    },
    societeActive(state) {
      const exercice = state.exercices.find((e) => e.id === state.exerciceId)
      if (exercice?.societe) return exercice.societe
      if (exercice?.societe_id) {
        return state.societes.find((s) => s.id === exercice.societe_id) || null
      }
      return state.societes.find((s) => s.actif) || state.societes[0] || null
    },
    periodeActive(state) {
      return state.periodes.find((p) => p.id === state.periodeId) || null
    },
    periodesForExercice(state) {
      if (!state.exerciceId) return state.periodes
      return state.periodes.filter((p) => p.exercice_id === state.exerciceId)
    },
  },

  actions: {
    async bootstrap() {
      this.loading = true
      this.error = null
      try {
        const [so, ex, pe, jo, co, ti] = await Promise.all([
          societesApi.list(),
          exercicesApi.list(),
          periodesApi.list(),
          journalsApi.list(),
          comptesApi.list({ per_page: 500, mouvement: 1 }),
          tiersApi.list({ per_page: 500 }),
        ])
        this.societes = Array.isArray(so.data) ? so.data : []
        this.exercices = Array.isArray(ex.data) ? ex.data : []
        this.periodes = Array.isArray(pe.data) ? pe.data : []
        this.journals = Array.isArray(jo.data) ? jo.data : []
        this.comptes = Array.isArray(co.data) ? co.data : []
        this.tiers = Array.isArray(ti.data) ? ti.data : []

        const ouvert = this.exercices.find((e) => !e.cloture) || this.exercices[0]
        if (ouvert) {
          this.exerciceId = ouvert.id
          const periode =
            this.periodes.find(
              (p) => p.exercice_id === ouvert.id && !p.cloturee
            ) || this.periodes.find((p) => p.exercice_id === ouvert.id)
          this.periodeId = periode?.id ?? null
        }
      } catch (e) {
        this.error = e.message
      } finally {
        this.loading = false
      }
    },

    setExercice(id) {
      this.exerciceId = id ? Number(id) : null
      const periode =
        this.periodes.find(
          (p) => p.exercice_id === this.exerciceId && !p.cloturee
        ) || this.periodes.find((p) => p.exercice_id === this.exerciceId)
      this.periodeId = periode?.id ?? null
    },

    setPeriode(id) {
      this.periodeId = id ? Number(id) : null
    },
  },
})
