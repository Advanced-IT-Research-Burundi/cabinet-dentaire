import { createRouter, createWebHistory } from 'vue-router'
import ComptaLayout from '../layout/ComptaLayout.vue'
import DashboardView from '../views/DashboardView.vue'
import SaisieView from '../views/SaisieView.vue'
import PiecesView from '../views/PiecesView.vue'
import ComptesView from '../views/ComptesView.vue'

const router = createRouter({
  history: createWebHistory('/compta'),
  routes: [
    {
      path: '/',
      component: ComptaLayout,
      children: [
        {
          path: '',
          name: 'compta.dashboard',
          component: DashboardView,
        },
        {
          path: 'saisie/:id?',
          name: 'compta.saisie',
          component: SaisieView,
        },
        {
          path: 'pieces',
          name: 'compta.pieces',
          component: PiecesView,
        },
        {
          path: 'comptes',
          name: 'compta.comptes',
          component: ComptesView,
        },
        {
          path: 'journaux',
          name: 'compta.journaux',
          component: () => import('../views/JournauxView.vue'),
        },
        {
          path: 'tiers',
          name: 'compta.tiers',
          component: () => import('../views/TiersView.vue'),
        },
        {
          path: 'banques',
          name: 'compta.banques',
          component: () => import('../views/BanquesView.vue'),
        },
        {
          path: 'budgets',
          name: 'compta.budgets',
          component: () => import('../views/BudgetsView.vue'),
        },
        {
          path: 'postes',
          name: 'compta.postes',
          component: () => import('../views/PostesView.vue'),
        },
        {
          path: 'exercices',
          name: 'compta.exercices',
          component: () => import('../views/ExercicesView.vue'),
        },
        {
          path: 'periodes',
          name: 'compta.periodes',
          component: () => import('../views/PeriodesView.vue'),
        },
        {
          path: 'balance',
          name: 'compta.balance',
          component: () => import('../views/BalanceView.vue'),
        },
        {
          path: 'grand-livre',
          name: 'compta.grand-livre',
          component: () => import('../views/GrandLivreView.vue'),
        },
      ],
    },
  ],
})

// #region agent log
router.beforeEach((to, from, next) => {
  to.meta.__navT0 = performance.now()
  fetch('http://127.0.0.1:7845/ingest/d75feb9c-36a3-4797-b93e-748750fb52bb',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'5fa0d4'},body:JSON.stringify({sessionId:'5fa0d4',runId:'post-fix',hypothesisId:'E',location:'router/index.js:beforeEach',message:'nav_start',data:{from:from.fullPath,to:to.fullPath,name:to.name},timestamp:Date.now()})}).catch(()=>{});
  next()
})
router.afterEach((to) => {
  fetch('http://127.0.0.1:7845/ingest/d75feb9c-36a3-4797-b93e-748750fb52bb',{method:'POST',headers:{'Content-Type':'application/json','X-Debug-Session-Id':'5fa0d4'},body:JSON.stringify({sessionId:'5fa0d4',runId:'post-fix',hypothesisId:'E',location:'router/index.js:afterEach',message:'nav_done',data:{to:to.fullPath,name:to.name,ms:Math.round(performance.now()-(to.meta.__navT0||0))},timestamp:Date.now()})}).catch(()=>{});
})
// #endregion

export default router
