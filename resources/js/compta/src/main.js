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
}
