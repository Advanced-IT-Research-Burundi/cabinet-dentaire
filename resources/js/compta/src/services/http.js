import axios from 'axios'
import { API_PREFIX } from '../config/env'

const http = axios.create({
  baseURL: API_PREFIX,
  headers: {
    Accept: 'application/json',
    'X-Requested-With': 'XMLHttpRequest',
  },
})

const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
if (csrf) {
  http.defaults.headers.common['X-CSRF-TOKEN'] = csrf
}

http.interceptors.response.use(
  undefined,
  (error) => {
    const message =
      error.response?.data?.message ||
      error.message ||
      'Erreur réseau'
    return Promise.reject(new Error(message))
  }
)

export default http

export function unwrap(response) {
  const body = response.data
  if (body && typeof body === 'object' && 'data' in body) {
    return {
      data: body.data,
      pagination: body.pagination ?? null,
      message: body.message,
      raw: body,
    }
  }
  return { data: body, pagination: null, message: null, raw: body }
}
