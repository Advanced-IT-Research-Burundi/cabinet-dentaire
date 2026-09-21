/**
 * Schémas de création alignés sur les StoreRequest de budental_compta_api.
 */
export const resourceForms = {
  journaux: {
    createLabel: 'Nouveau journal',
    endpoint: 'journals',
    fields: [
      { key: 'code', label: 'Code', type: 'text', required: true },
      { key: 'intitule', label: 'Intitulé', type: 'text', required: true },
      { key: 'type_journal_id', label: 'Type de journal', type: 'lookup', lookup: 'typeJournals', required: true },
      { key: 'compte_id', label: 'Compte', type: 'lookup', lookup: 'comptes', required: true },
      { key: 'compte_contrepartie_id', label: 'Compte contrepartie', type: 'lookup', lookup: 'comptes', required: false },
      { key: 'numerotation_automatique', label: 'Numérotation auto', type: 'boolean', default: true },
      { key: 'actif', label: 'Actif', type: 'boolean', default: true },
    ],
  },
  tiers: {
    createLabel: 'Nouveau tiers',
    endpoint: 'tiers',
    fields: [
      { key: 'code', label: 'Code', type: 'text', required: true },
      { key: 'intitule', label: 'Intitulé', type: 'text', required: true },
      { key: 'type_tiers_id', label: 'Type de tiers', type: 'lookup', lookup: 'typeTiers', required: true },
      { key: 'adresse', label: 'Adresse', type: 'text' },
      { key: 'telephone', label: 'Téléphone', type: 'text' },
      { key: 'email', label: 'Email', type: 'email' },
      { key: 'nif', label: 'NIF', type: 'text' },
      { key: 'actif', label: 'Actif', type: 'boolean', default: true },
    ],
  },
  banques: {
    createLabel: 'Nouvelle banque',
    endpoint: 'banques',
    fields: [
      { key: 'code', label: 'Code', type: 'text', required: true },
      { key: 'intitule', label: 'Intitulé', type: 'text', required: true },
      { key: 'numero_compte', label: 'N° compte', type: 'text' },
      { key: 'iban', label: 'IBAN', type: 'text' },
      { key: 'swift', label: 'SWIFT', type: 'text' },
      { key: 'actif', label: 'Actif', type: 'boolean', default: true },
    ],
  },
  comptes: {
    createLabel: 'Nouveau compte',
    endpoint: 'comptes',
    fields: [
      { key: 'numero', label: 'Numéro', type: 'text', required: true },
      { key: 'intitule', label: 'Intitulé', type: 'text', required: true },
      { key: 'type_compte_id', label: 'Type de compte', type: 'lookup', lookup: 'typeComptes', required: true },
      { key: 'compte_parent_id', label: 'Compte parent', type: 'lookup', lookup: 'comptes', required: false },
      { key: 'mouvement', label: 'Mouvement', type: 'boolean', default: true },
      { key: 'collectif', label: 'Collectif', type: 'boolean', default: false },
      { key: 'actif', label: 'Actif', type: 'boolean', default: true },
    ],
  },
  budgets: {
    createLabel: 'Nouveau budget',
    endpoint: 'budgets',
    fields: [
      { key: 'code', label: 'Code', type: 'text', required: true },
      { key: 'intitule', label: 'Intitulé', type: 'text', required: true },
      { key: 'exercice_id', label: 'Exercice', type: 'lookup', lookup: 'exercices', required: true },
      { key: 'departement_id', label: 'Département', type: 'lookup', lookup: 'departements', required: true },
      { key: 'section_id', label: 'Section', type: 'lookup', lookup: 'sections', required: true },
      { key: 'section_analytique_id', label: 'Section analytique', type: 'lookup', lookup: 'sections', required: true },
      { key: 'poste_budgetaire_id', label: 'Poste budgétaire', type: 'lookup', lookup: 'postes', required: true },
      { key: 'montant_prevu', label: 'Montant prévu', type: 'number', default: 0, required: true },
      { key: 'montant_revise', label: 'Montant révisé', type: 'number', default: 0, required: true },
      { key: 'montant_engage', label: 'Montant engagé', type: 'number', default: 0, required: true },
      { key: 'montant_realise', label: 'Montant réalisé', type: 'number', default: 0, required: true },
      { key: 'actif', label: 'Actif', type: 'boolean', default: true },
    ],
  },
  postes: {
    createLabel: 'Nouveau poste budgétaire',
    endpoint: 'poste-budgetaires',
    fields: [
      { key: 'code', label: 'Code', type: 'text', required: true },
      { key: 'intitule', label: 'Intitulé', type: 'text', required: true },
      { key: 'type_budget_id', label: 'Type de budget', type: 'lookup', lookup: 'typeBudgets', required: true },
      { key: 'compte_id', label: 'Compte', type: 'lookup', lookup: 'comptes', required: true },
      { key: 'actif', label: 'Actif', type: 'boolean', default: true },
    ],
  },
  exercices: {
    createLabel: 'Nouvel exercice',
    endpoint: 'exercices',
    fields: [
      { key: 'societe_id', label: 'Société', type: 'lookup', lookup: 'societes', required: true },
      { key: 'code', label: 'Code', type: 'text', required: true },
      { key: 'date_debut', label: 'Date début', type: 'date', required: true },
      { key: 'date_fin', label: 'Date fin', type: 'date', required: true },
      { key: 'cloture', label: 'Clôturé', type: 'boolean', default: false },
      { key: 'commentaire_cloture', label: 'Commentaire clôture', type: 'text' },
    ],
  },
  periodes: {
    createLabel: 'Nouvelle période',
    endpoint: 'periodes',
    fields: [
      { key: 'exercice_id', label: 'Exercice', type: 'lookup', lookup: 'exercices', required: true },
      { key: 'libelle', label: 'Libellé', type: 'text', required: true },
      { key: 'date_debut', label: 'Date début', type: 'date', required: true },
      { key: 'date_fin', label: 'Date fin', type: 'date', required: true },
      { key: 'cloturee', label: 'Clôturée', type: 'boolean', default: false },
    ],
  },
  typeJournaux: {
    createLabel: 'Nouveau type de journal',
    editLabel: 'Modifier le type de journal',
    endpoint: 'type-journals',
    fields: [
      { key: 'code', label: 'Code', type: 'text', required: true },
      { key: 'intitule', label: 'Intitulé', type: 'text', required: true },
      { key: 'actif', label: 'Actif', type: 'boolean', default: true },
    ],
  },
  typeTiers: {
    createLabel: 'Nouveau type de tiers',
    editLabel: 'Modifier le type de tiers',
    endpoint: 'type-tiers',
    fields: [
      { key: 'code', label: 'Code', type: 'text', required: true },
      { key: 'intitule', label: 'Intitulé', type: 'text', required: true },
      { key: 'actif', label: 'Actif', type: 'boolean', default: true },
    ],
  },
  typeComptes: {
    createLabel: 'Nouveau type de compte',
    editLabel: 'Modifier le type de compte',
    endpoint: 'type-comptes',
    fields: [
      { key: 'code', label: 'Code', type: 'text', required: true },
      { key: 'intitule', label: 'Intitulé', type: 'text', required: true },
      { key: 'actif', label: 'Actif', type: 'boolean', default: true },
    ],
  },
  typeBudgets: {
    createLabel: 'Nouveau type de budget',
    editLabel: 'Modifier le type de budget',
    endpoint: 'type-budgets',
    fields: [
      { key: 'code', label: 'Code', type: 'text', required: true },
      { key: 'intitule', label: 'Intitulé', type: 'text', required: true },
      { key: 'actif', label: 'Actif', type: 'boolean', default: true },
    ],
  },
}
