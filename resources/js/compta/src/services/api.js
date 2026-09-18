import http, { unwrap } from './http'

export const dashboardApi = {
  async get(params = {}) {
    const res = await http.get('/dashboard', { params })
    return unwrap(res).data ?? unwrap(res).raw
  },
}

export const piecesApi = {
  async list(params = {}) {
    return unwrap(await http.get('/piece-comptables', { params }))
  },
  async get(id) {
    return unwrap(await http.get(`/piece-comptables/${id}`)).data
  },
  async create(payload) {
    return unwrap(await http.post('/piece-comptables', payload)).data
  },
  async update(id, payload) {
    return unwrap(await http.put(`/piece-comptables/${id}`, payload)).data
  },
}

export const comptesApi = {
  async list(params = {}) {
    return unwrap(await http.get('/comptes', { params }))
  },
}

export const tiersApi = {
  async list(params = {}) {
    return unwrap(await http.get('/tiers', { params }))
  },
}

export const journalsApi = {
  async list(params = {}) {
    return unwrap(await http.get('/journals', { params }))
  },
}

export const banquesApi = {
  async list(params = {}) {
    return unwrap(await http.get('/banques', { params }))
  },
}

export const budgetsApi = {
  async list(params = {}) {
    return unwrap(await http.get('/budgets', { params }))
  },
}

export const postesApi = {
  async list(params = {}) {
    return unwrap(await http.get('/poste-budgetaires', { params }))
  },
}

export const exercicesApi = {
  async list(params = {}) {
    return unwrap(await http.get('/exercices', { params }))
  },
}

export const periodesApi = {
  async list(params = {}) {
    return unwrap(await http.get('/periodes', { params }))
  },
}

export const ecrituresApi = {
  async list(params = {}) {
    return unwrap(await http.get('/ecritures', { params }))
  },
}

export const rapportsApi = {
  async balance(params = {}) {
    return unwrap(await http.get('/rapports/balance', { params }))
  },
  async grandLivre(params = {}) {
    return unwrap(await http.get('/rapports/grand-livre', { params }))
  },
}
