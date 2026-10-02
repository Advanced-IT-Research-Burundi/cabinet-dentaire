<?php

namespace App\Exports;

use App\Http\Controllers\SendInvoiceToOBR;
use App\Models\Treatment;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InvoicesObrExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    private Collection $invoices;

    public function __construct(Collection $invoices)
    {
        $this->invoices = $invoices;
    }

    public function collection()
    {
        return $this->invoices;
    }

    public function headings(): array
    {
        return [
            'Date',
            'Nom du patient',
            'Numero facture',
            'Prestation',
            'Montant',
            'Dentiste',
            'Signature électronique',
        ];
    }

    /**
     * Une ligne Excel par prestation de la facture
     */
    public function map($invoice): array
    {
        $date = $invoice->created_at->format('d/m/Y');
        $patient = $invoice->client['customer_name'] ?? '';
        $numero = $invoice->invoice_number ?: $invoice->id;
        // Signature électronique : TIN/OBR_USERNAME/YmdHis/numero facture
        $signature = $invoice->invoice_identifier
            ?: SendInvoiceToOBR::getInvoiceSignature($numero, $invoice->created_at);

        // Dentiste par traitement (lignes de type Treatment)
        $dentistsByTreatment = $invoice->treatments
            ->mapWithKeys(fn ($treatment) => [$treatment->id => $treatment->dentist?->user?->full_name ?? '']);

        $prestations = collect($invoice->description ?? [])
            ->filter(fn ($item) => !empty($item['item_designation']))
            ->values();

        if ($prestations->isEmpty()) {
            return [[$date, $patient, $numero, '', $invoice->total_amount, $invoice->dentist_names, $signature]];
        }

        return $prestations
            ->map(function ($item) use ($date, $patient, $numero, $signature, $dentistsByTreatment) {
                $dentiste = ($item['item_model'] ?? null) === Treatment::class
                    ? ($dentistsByTreatment[$item['item_product_detail_id'] ?? 0] ?? '')
                    : '';

                return [
                    $date,
                    $patient,
                    $numero,
                    $item['item_designation'],
                    $item['item_total_amount'] ?? 0,
                    $dentiste,
                    $signature,
                ];
            })
            ->all();
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
}
