<?php

namespace App\Exports;

use App\Models\Stock;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StockExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function collection()
    {
        return Stock::with(['category', 'supplier', 'user'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nom du produit',
            'Marque',
            'Code produit',
            'Catégorie',
            'Fournisseur',
            'Unité de mesure',
            'Quantité en stock',
            'Quantité d\'alerte',
            'Prix unitaire (HTVA)',
            'Prix TTC',
            'Prix maximum',
            'Prix TVAC',
            'Taux TVA (%)',
            'Taxe OTT',
            'Taxe TSCE',
            'Prix minimum',
            'Date d\'expiration',
            'Description',
            'Emplacement',
            'Statut',
            'Valeur totale HTVA',
            'Valeur totale TTC',
            'Créé le',
            'Modifié le'
        ];
    }

    public function map($stock): array
    {
        return [
            $stock->id,
            $stock->product_name,
            $stock->marque,
            $stock->code_product,
            $stock->category->name ?? 'Non spécifiée',
            $stock->supplier->name ?? 'Non spécifié',
            $stock->unite_mesure,
            $stock->quantite,
            $stock->quantite_alert,
            number_format($stock->price, 2),
            number_format($stock->price_ttc, 2),
            number_format($stock->price_max, 2),
            number_format($stock->price_tvac, 2),
            $stock->taux_tva,
            number_format($stock->item_ott_tax, 2),
            number_format($stock->item_tsce_tax, 2),
            number_format($stock->price_min, 2),
            $stock->date_expiration ? $stock->date_expiration->format('d/m/Y') : '',
            $stock->description,
            $stock->location,
            $this->getStatusLabel($stock->status),
            number_format($stock->quantite * $stock->price, 2),
            number_format($stock->quantite * $stock->price_ttc, 2),
            $stock->created_at->format('d/m/Y H:i'),
            $stock->updated_at->format('d/m/Y H:i')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0d6efd']
                ]
            ]
        ];
    }

    private function getStatusLabel($status)
    {
        $labels = [
            'Disponible' => 'Disponible',
            'Faible_stock' => 'Stock faible',
            'En_rupture' => 'En rupture',
            'Expire' => 'Expiré'
        ];

        return $labels[$status] ?? $status;
    }
}
