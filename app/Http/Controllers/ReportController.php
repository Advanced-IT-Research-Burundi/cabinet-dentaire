<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Treatment;
use App\Models\Dentist;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\MonthlyReportExport;

class ReportController extends Controller
{
    public function monthly(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);
        $dentistId = $request->input('dentist_id');

        $dentists = Dentist::with('user')->get();

        $query = Treatment::with(['patient', 'dentist.user', 'treatmentTypes'])
            ->whereYear('date', $year)
            ->whereMonth('date', $month);

        if ($dentistId) {
            $query->where('dentist_id', $dentistId);
        }

        $treatments = $query->orderBy('date', 'desc')->get();

        $totalRevenue = $treatments->sum('applied_price');
        $totalTreatments = $treatments->count();

        $revenueByDentist = $treatments->groupBy('dentist_id')
            ->map(function ($dentistTreatments) {
                $dentist = $dentistTreatments->first()->dentist;
                $dentistName = $dentist ? ($dentist->user->full_name ?? 'Dentiste #' . $dentist->id) : 'Non assigné';
                
                return [
                    'name' => $dentistName,
                    'count' => $dentistTreatments->count(),
                    'total' => $dentistTreatments->sum('applied_price')
                ];
            })
            ->sortByDesc('total');

        return view('reports.monthly', compact(
            'treatments', 
            'totalRevenue', 
            'totalTreatments', 
            'revenueByDentist', 
            'month', 
            'year',
            'dentists',
            'dentistId'
        ));
    }

    public function exportMonthlyExcel(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);
        $dentistId = $request->input('dentist_id');
        
        return Excel::download(new MonthlyReportExport($month, $year, $dentistId), 'rapport_mensuel_'.$month.'_'.$year.'.xlsx');
    }

    public function exportMonthlyPdf(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);
        $dentistId = $request->input('dentist_id');

        $query = Treatment::with(['patient', 'dentist.user', 'treatmentTypes'])
            ->whereYear('date', $year)
            ->whereMonth('date', $month);

        if ($dentistId) {
            $query->where('dentist_id', $dentistId);
        }

        $treatments = $query->orderBy('date', 'desc')->get();

        $totalRevenue = $treatments->sum('applied_price');
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

        $pdf = Pdf::loadView('reports.pdf', compact('treatments', 'month', 'year', 'totalRevenue', 'revenueByDentist'));
        
        return $pdf->download('rapport_mensuel_'.$month.'_'.$year.'.pdf');
    }
}
