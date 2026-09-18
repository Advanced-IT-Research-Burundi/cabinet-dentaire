import axios from 'axios'
import { API_PREFIX } from '../config/env'

const http = axios.create({
  baseURL: API_PREFIX,
  headers: {
    Accept: 'application/json',
    'X-Requested-With': 'XMLHttpRequest',
  },
  withCredentials: true,
})

const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
if (csrf) {
  http.defaults.headers.common['X-CSRF-TOKEN'] = csrf
}

http.interceptors.request.use((config) => {
  // #region agent log
  config.__dbgT0 = performance.now()
  fetch('http://127.0.0.1:7845/ingest/d75feb9c-36a3-4797-b93e-748750fb52bb',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'5fa0d4'},body:JSON.stringify({sessionId:'5fa0d4',runId:'post-fix',hypothesisId:'E',location:'http.js:request',message:'api_request',data:{method:config.method,url:config.url,baseURL:config.baseURL},timestamp:Date.now()})}).catch(()=>{});
  // #endregion
  return config
})

http.interceptors.response.use(
  (response) => {
    // #region agent log
    fetch('http://127.0.0.1:7845/ingest/d75feb9c-36a3-4797-b93e-748750fb52bb',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'5fa0d4'},body:JSON.stringify({sessionId:'5fa0d4',runId:'post-fix',hypothesisId:'A',location:'http.js:response',message:'api_response_ok',data:{url:response.config?.url,status:response.status,ms:Math.round(performance.now()-(response.config?.__dbgT0||0))},timestamp:Date.now()})}).catch(()=>{});
    // #endregion
    return response
  },
  (error) => {
    // #region agent log
    fetch('http://127.0.0.1:7845/ingest/d75feb9c-36a3-4797-b93e-748750fb52bb',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'5fa0d4'},body:JSON.stringify({sessionId:'5fa0d4',runId:'post-fix',hypothesisId:'A',location:'http.js:error',message:'api_response_error',data:{url:error.config?.url,status:error.response?.status,bodyMessage:error.response?.data?.message,error:error.message,ms:Math.round(performance.now()-(error.config?.__dbgT0||0))},timestamp:Date.now()})}).catch(()=>{});
    // #endregion
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
