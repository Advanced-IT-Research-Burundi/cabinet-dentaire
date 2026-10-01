<?php

namespace App\Exports;

use App\Models\ObrPointer;
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

    /**
     * Signatures électroniques OBR indexées par invoice_id
     * (une facture peut avoir plusieurs tentatives d'envoi, on garde la dernière signée)
     */
    private Collection $signatures;

    public function __construct(Collection $invoices)
    {
        $this->invoices = $invoices;

        $this->signatures = ObrPointer::whereIn('invoice_id', $invoices->pluck('id'))
            ->whereNotNull('electronic_signature')
            ->where('electronic_signature', '!=', '')
            ->orderBy('id')
            ->get()
            ->keyBy('invoice_id')
            ->map(fn ($pointer) => $pointer->electronic_signature);
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
            'signature obr',
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
        $signature = $this->signatures[$invoice->id] ?? '';

        $prestations = collect($invoice->description ?? [])
            ->pluck('item_designation')
            ->filter()
            ->values();

        if ($prestations->isEmpty()) {
            return [[$date, $patient, $numero, '', $signature]];
        }

        return $prestations
            ->map(fn ($prestation) => [$date, $patient, $numero, $prestation, $signature])
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
