<?php

namespace App\Exports;

use App\Models\MouvementStock;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MouvementStockExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $dateDebut;
    protected $dateFin;
    protected $typeMouvement;

    public function __construct($dateDebut = null, $dateFin = null, $typeMouvement = 'all')
    {
        $this->dateDebut = $dateDebut ?? now()->subMonth()->format('Y-m-d');
        $this->dateFin = $dateFin ?? now()->format('Y-m-d');
        $this->typeMouvement = $typeMouvement;
    }

    public function collection()
    {
        $query = MouvementStock::with(['stock', 'user'])
            ->whereBetween('item_movement_date', [$this->dateDebut, $this->dateFin]);

        if ($this->typeMouvement === 'entrees') {
            $query->entrees();
        } elseif ($this->typeMouvement === 'sorties') {
            $query->sorties();
        }

        return $query->orderBy('item_movement_date', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Date du mouvement',
            'Code produit',
            'Désignation',
            'Type de mouvement',
            'Quantité',
            'Unité de mesure',
            'Prix unitaire',
            'Devise',
            'Valeur totale',
            'Référence facture',
            'Description',
            'Utilisateur',
            'Envoyé OBR',
            'Date envoi OBR',
            'Créé le',
        ];
    }

    public function map($mouvement): array
    {
        return [
            $mouvement->id,
            $mouvement->item_movement_date ? date('d/m/Y', strtotime($mouvement->item_movement_date)) : '',
            $mouvement->item_code,
            $mouvement->item_designation,
            $this->getMovementTypeLabel($mouvement->item_movement_type),
            $mouvement->item_quantity,
            $mouvement->item_measurement_unit,
            number_format($mouvement->item_purchase_or_sale_price ?? 0, 2),
            $mouvement->item_purchase_or_sale_currency ?? 'FBU',
            number_format(($mouvement->item_quantity ?? 0) * ($mouvement->item_purchase_or_sale_price ?? 0), 2),
            $mouvement->item_movement_invoice_ref,
            $mouvement->item_movement_description,
            $mouvement->user->name ?? 'N/A',
            $mouvement->is_send_to_obr ? 'Oui' : 'Non',
            $mouvement->is_sent_at ? date('d/m/Y H:i', strtotime($mouvement->is_sent_at)) : '',
            $mouvement->created_at ? $mouvement->created_at->format('d/m/Y H:i') : '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '198754']
                ]
            ]
        ];
    }

    private function getMovementTypeLabel($type)
    {
        $types = MouvementStock::MOUVEMENT_TYPES;
        return $types[$type] ?? $type;
    }
}
