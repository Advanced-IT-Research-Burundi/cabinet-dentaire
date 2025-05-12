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
