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
          path: 'type-journaux',
          name: 'compta.type-journaux',
          component: () => import('../views/TypeJournauxView.vue'),
        },
        {
          path: 'type-tiers',
          name: 'compta.type-tiers',
          component: () => import('../views/TypeTiersView.vue'),
        },
        {
          path: 'type-comptes',
          name: 'compta.type-comptes',
          component: () => import('../views/TypeComptesView.vue'),
        },
        {
          path: 'type-budgets',
          name: 'compta.type-budgets',
          component: () => import('../views/TypeBudgetsView.vue'),
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

export default router
