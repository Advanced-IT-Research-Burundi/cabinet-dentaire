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
      { label: 'Tiers', icon: 'bi bi-people', to: { name: 'compta.tiers' } },
      { label: 'Banques', icon: 'bi bi-bank', to: { name: 'compta.banques' } },
    ],
  },
  {
    label: 'Budget',
    items: [
      { label: 'Budgets', icon: 'bi bi-pie-chart', to: { name: 'compta.budgets' } },
      { label: 'Postes budgétaires', icon: 'bi bi-list-ul', to: { name: 'compta.postes' } },
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
