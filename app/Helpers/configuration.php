<?php

/**
 * Configuration constants
 *
 * @category Constants
 * @package  App\Helpers
 * @author   Your Name <nijeanlionel@gmail.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     https://github.com/Advanced-IT-Research-Burundi/cabinet-dentaire
 */

const MOUVEMENT_STOCK = [
    "" => "----",
    'EN' => 'Entrée Normales',
    'ER' => 'Entrée Retour',
    'EI' => 'Entrée Inventaire',
    'EAJ' => 'Entrées Ajustement',
    'ET' => 'Entrées Transfert',
    'EAU' => 'Entrées Autres',
    'SN' => 'Sorties Normales',
    'SP' => 'Sorties Perte',
    'SV' => 'Sorties Vol',
    'SD' => 'Sorties Désuétude',
    'SC' => 'Sorties Casse',
    'SAJ' => 'Sorties Ajustement',
    'ST' => 'Sorties Transfert',
    'SAU' => 'Sorties Autres',
];

const TYPE_PAYMENT = [
    1 => 'En espèce',
    2 => 'banque',
    3 => 'à crédit',
    4 => 'autres',
];

const ROLE_USERS = [
    "" => "----",
    'Admin' => 'Admin',
    'Dentiste' => 'Dentiste',
    'Secretaire' => 'Secretaire',
    'Pharmacist' => 'Pharmacist',
];

const LOAD_DATA = 1000;

// Opérations de la caisse centrale : code => [libellé, sens]
const OPERATIONS_CAISSE_CENTRALE = [
    'VERSEMENT_BANQUE' => ['label' => 'Versement en banque', 'sens' => 'sortie'],
    'RETRAIT_BANQUE' => ['label' => 'Retrait bancaire', 'sens' => 'entree'],
    'VIREMENT_RECU' => ['label' => 'Virement reçu', 'sens' => 'entree'],
    'VIREMENT_EMIS' => ['label' => 'Virement émis', 'sens' => 'sortie'],
    'FRAIS_BANCAIRES' => ['label' => 'Frais bancaires', 'sens' => 'sortie'],
    'AUTRE_ENTREE' => ['label' => 'Autre entrée', 'sens' => 'entree'],
    'AUTRE_SORTIE' => ['label' => 'Autre sortie', 'sens' => 'sortie'],
];

// Collecte (diminution) du montant d'une caisse utilisateur vers la caisse centrale
const COLLECTE_CAISSE = 'COLLECTE_CAISSE';
