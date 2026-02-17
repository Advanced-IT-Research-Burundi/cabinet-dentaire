<?php

namespace App\Exports;

use App\Models\Treatment;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MonthlyReportExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $month;
    protected $year;
    protected $dentistId;

    public function __construct(int $month, int $year, ?int $dentistId = null)
    {
        $this->month = $month;
        $this->year = $year;
        $this->dentistId = $dentistId;
    }

    public function view(): View
    {
        $query = Treatment::with(['patient', 'dentist.user', 'treatmentTypes'])
            ->whereYear('date', $this->year)
            ->whereMonth('date', $this->month);

        if ($this->dentistId) {
            $query->where('dentist_id', $this->dentistId);
        }

        $treatments = $query->orderBy('date', 'desc')->get();

        $revenueByDentist = $treatments->groupBy('dentist_id')
            ->map(function ($dentistTreatments) {
                 $dentist = $dentistTreatments->first()->dentist;
                 $name = $dentist ? ($dentist->user->full_name ?? 'Dentiste #' . $dentist->id) : 'Non assigné';
                 return [
                     'name' => $name,
                     'count' => $dentistTreatments->count(),
                     'total' => $dentistTreatments->sum('applied_price')
                 ];
            })->sortByDesc('total');

        return view('reports.excel', [
            'treatments' => $treatments,
            'revenueByDentist' => $revenueByDentist,
            'month' => $this->month,
            'year' => $this->year
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true, 'size' => 16]],
            // Add more styles if needed, but view styling is usually sufficient for structure
        ];
    }
}
