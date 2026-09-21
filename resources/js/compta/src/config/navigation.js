/**
 * Navigation par tâches métier — UX Budental (pas GAMA).
 * Icons = Bootstrap Icons (déjà chargés dans layouts.app) → pas d'attente PrimeIcons.
 */
export const navigation = [
  {
    label: 'Pilotage',
    items: [
      { label: 'Tableau de bord', icon: 'bi bi-speedometer2', to: { name: 'compta.dashboard' } },
    ],
  },
  {
    label: 'Opérations',
    items: [
      { label: 'Saisie', icon: 'bi bi-pencil-square', to: { name: 'compta.saisie' } },
      { label: 'Pièces', icon: 'bi bi-file-earmark-text', to: { name: 'compta.pieces' } },
    ],
  },
  {
    label: 'Consultation',
    items: [
      { label: 'Plan comptable', icon: 'bi bi-diagram-3', to: { name: 'compta.comptes' } },
      { label: 'Balance', icon: 'bi bi-table', to: { name: 'compta.balance' } },
      { label: 'Grand livre', icon: 'bi bi-journal-bookmark', to: { name: 'compta.grand-livre' } },
    ],
  },
  {
    label: 'Référentiels',
    items: [
      { label: 'Journaux', icon: 'bi bi-bookmark', to: { name: 'compta.journaux' } },
      { label: 'Types de journaux', icon: 'bi bi-bookmark-star', to: { name: 'compta.type-journaux' } },
      { label: 'Tiers', icon: 'bi bi-people', to: { name: 'compta.tiers' } },
      { label: 'Types de tiers', icon: 'bi bi-person-badge', to: { name: 'compta.type-tiers' } },
      { label: 'Banques', icon: 'bi bi-bank', to: { name: 'compta.banques' } },
      { label: 'Types de comptes', icon: 'bi bi-tags', to: { name: 'compta.type-comptes' } },
    ],
  },
  {
    label: 'Budget',
    items: [
      { label: 'Budgets', icon: 'bi bi-pie-chart', to: { name: 'compta.budgets' } },
      { label: 'Postes budgétaires', icon: 'bi bi-list-ul', to: { name: 'compta.postes' } },
      { label: 'Types de budgets', icon: 'bi bi-tag', to: { name: 'compta.type-budgets' } },
    ],
  },
  {
    label: 'Paramètres',
    items: [
      { label: 'Exercices', icon: 'bi bi-calendar3', to: { name: 'compta.exercices' } },
      { label: 'Périodes', icon: 'bi bi-calendar-plus', to: { name: 'compta.periodes' } },
    ],
  },
]
