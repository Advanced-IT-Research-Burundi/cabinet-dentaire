import { createApp } from 'vue'
import { createPinia } from 'pinia'
import PrimeVue from 'primevue/config'
import { definePreset } from '@primevue/themes'
import Aura from '@primevue/themes/aura'
import ToastService from 'primevue/toastservice'
import ConfirmationService from 'primevue/confirmationservice'

import 'primeicons/primeicons.css'
import './assets/theme.css'

import App from './App.vue'
import router from './router'

const BudentalPreset = definePreset(Aura, {
  semantic: {
    primary: {
      50: '#eef5fb',
      100: '#d5e7f6',
      200: '#abcff0',
      300: '#6ca8ec',
      400: '#3d8fd9',
      500: '#1e73be',
      600: '#195fa0',
      700: '#154d82',
      800: '#123f6b',
      900: '#0f3459',
      950: '#0a223b',
    },
  },
})

const el = document.getElementById('compta-app')

if (el) {
  // #region agent log
  const __tMount = performance.now()
  fetch('http://127.0.0.1:7845/ingest/d75feb9c-36a3-4797-b93e-748750fb52bb',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'5fa0d4'},body:JSON.stringify({sessionId:'5fa0d4',runId:'post-fix',hypothesisId:'D',location:'main.js:mount',message:'compta_mount_start',data:{hasPrimeIconsCss:!!document.querySelector('link[href*="primeicons"], style')},timestamp:Date.now()})}).catch(()=>{});
  // #endregion
  const app = createApp(App)
  app.use(createPinia())
  app.use(router)
  app.use(PrimeVue, {
    theme: {
      preset: BudentalPreset,
      options: {
        darkModeSelector: false,
        cssLayer: false,
      },
    },
  })
  app.use(ToastService)
  app.use(ConfirmationService)
  app.mount(el)
  // #region agent log
  fetch('http://127.0.0.1:7845/ingest/d75feb9c-36a3-4797-b93e-748750fb52bb',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'5fa0d4'},body:JSON.stringify({sessionId:'5fa0d4',runId:'post-fix',hypothesisId:'D',location:'main.js:mounted',message:'compta_mount_done',data:{ms:Math.round(performance.now()-__tMount),iconFontReady:document.fonts?document.fonts.check('1em primeicons'):null},timestamp:Date.now()})}).catch(()=>{});
  document.fonts?.ready?.then(()=>{
    fetch('http://127.0.0.1:7845/ingest/d75feb9c-36a3-4797-b93e-748750fb52bb',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'5fa0d4'},body:JSON.stringify({sessionId:'5fa0d4',runId:'post-fix',hypothesisId:'D',location:'main.js:fonts',message:'fonts_ready',data:{primeicons:document.fonts.check('1em primeicons')},timestamp:Date.now()})}).catch(()=>{});
  })
  // #endregion
}
