<?php

namespace App\Imports;

use App\Models\Stock;
use App\Models\Category;
use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class StockImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    use SkipsErrors, SkipsFailures;

    private $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function model(array $row)
    {
        // Gérer la catégorie
        $category = null;
        if (!empty($row['categorie'])) {
            $category = Category::firstOrCreate(
                ['name' => trim($row['categorie'])],
                ['description' => 'Catégorie créée automatiquement lors de l\'import']
            );
        }

        // Gérer le fournisseur
        $supplier = null;
        if (!empty($row['fournisseur'])) {
            $supplier = Supplier::firstOrCreate(
                ['name' => trim($row['fournisseur'])],
                [
                    'email' => null,
                    'phone' => null,
                    'address' => null
                ]
            );
        }

        // Conversion des prix
        $price = $this->convertPrice($row['prix_unitaire_htva'] ?? 0);
        $price_ttc = $this->convertPrice($row['prix_ttc'] ?? $price);
        $price_max = $this->convertPrice($row['prix_maximum'] ?? 0);
        $price_tvac = $this->convertPrice($row['prix_tvac'] ?? 0);
        $price_min = $this->convertPrice($row['prix_minimum'] ?? 0);

        // Conversion des quantités
        $quantite = (float) ($row['quantite_en_stock'] ?? 0);
        $quantite_alert = (float) ($row['quantite_dalerte'] ?? 0);

        // Conversion des taxes
        $taux_tva = (float) ($row['taux_tva'] ?? 0);
        $item_ott_tax = $this->convertPrice($row['taxe_ott'] ?? 0);
        $item_tsce_tax = $this->convertPrice($row['taxe_tsce'] ?? 0);

        // Conversion de la date d'expiration
        $date_expiration = null;
        if (!empty($row['date_dexpiration'])) {
            try {
                $date_expiration = Carbon::createFromFormat('d/m/Y', $row['date_dexpiration']);
            } catch (\Exception $e) {
                $date_expiration = null;
            }
        }

        // Déterminer le statut
        $status = $this->determineStatus($quantite, $quantite_alert, $date_expiration);

        return new Stock([
            'product_name' => $row['nom_du_produit'] ?? '',
            'marque' => $row['marque'] ?? null,
            'code_product' => $row['code_produit'] ?? null,
            'unite_mesure' => $row['unite_de_mesure'] ?? null,
            'quantite' => $quantite,
            'quantite_alert' => $quantite_alert,
            'price' => $price,
            'price_ttc' => $price_ttc,
            'price_max' => $price_max,
            'price_tvac' => $price_tvac,
            'taux_tva' => $taux_tva,
            'item_ott_tax' => $item_ott_tax,
            'item_tsce_tax' => $item_tsce_tax,
            'price_min' => $price_min,
            'date_expiration' => $date_expiration,
            'description' => $row['description'] ?? null,
            'location' => $row['emplacement'] ?? null,
            'category_id' => $category ? $category->id : null,
            'supplier_id' => $supplier ? $supplier->id : null,
            'user_id' => $this->userId,
            'status' => $status,
        ]);

    }

    public function rules(): array
    {
        return [
            '*.nom_du_produit' => 'required|string|max:255',
            '*.quantite_en_stock' => 'numeric|min:0',
            '*.prix_unitaire_htva' => 'numeric|min:0',
        ];
    }

    private function convertPrice($value)
    {
        if (is_string($value)) {
            // Supprimer les espaces et remplacer les virgules par des points
            $value = str_replace([' ', ','], ['', '.'], $value);
            // Supprimer les caractères non numériques sauf le point
            $value = preg_replace('/[^0-9.]/', '', $value);
        }

        return (float) $value;
    }

    private function determineStatus($quantite, $quantite_alert, $date_expiration)
    {
        // Vérifier si le produit est expiré
        if ($date_expiration && $date_expiration->isPast()) {
            return 'Expire';
        }

        // Vérifier le stock
        if ($quantite <= 0) {
            return 'En_rupture';
        } elseif ($quantite_alert > 0 && $quantite <= $quantite_alert) {
            return 'Faible_stock';
        } else {
            return 'Disponible';
        }
    }
}
