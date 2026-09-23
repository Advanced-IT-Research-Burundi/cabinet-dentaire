import http, { unwrap } from './http'

function resource(path) {
  return {
    async list(params = {}) {
      return unwrap(await http.get(path, { params }))
    },
    async get(id) {
      return unwrap(await http.get(`${path}/${id}`)).data
    },
    async create(payload) {
      return unwrap(await http.post(path, payload)).data
    },
    async update(id, payload) {
      return unwrap(await http.put(`${path}/${id}`, payload)).data
    },
    async remove(id) {
      return unwrap(await http.delete(`${path}/${id}`))
    },
  }
}

export const dashboardApi = {
  async get(params = {}) {
    const res = await http.get('/dashboard', { params })
    return unwrap(res).data ?? unwrap(res).raw
  },
}

export const piecesApi = resource('/piece-comptables')
export const comptesApi = resource('/comptes')
export const tiersApi = resource('/tiers')
export const journalsApi = resource('/journals')
export const banquesApi = resource('/banques')
export const budgetsApi = resource('/budgets')
export const postesApi = resource('/poste-budgetaires')
export const exercicesApi = resource('/exercices')
export const periodesApi = resource('/periodes')
export const ecrituresApi = resource('/ecritures')
export const immobilisationsApi = resource('/immobilisations')
export const amortissementsApi = resource('/amortissements')
export const typeComptesApi = resource('/type-comptes')
export const typeTiersApi = resource('/type-tiers')
export const typeJournalsApi = resource('/type-journals')
export const typeBudgetsApi = resource('/type-budgets')
export const societesApi = resource('/societes')
export const departementsApi = resource('/departements')
export const sectionsApi = resource('/section-analytiques')

export const rapportsApi = {
  async balance(params = {}) {
    return unwrap(await http.get('/rapports/balance', { params }))
  },
  async grandLivre(params = {}) {
    return unwrap(await http.get('/rapports/grand-livre', { params }))
  },
}
