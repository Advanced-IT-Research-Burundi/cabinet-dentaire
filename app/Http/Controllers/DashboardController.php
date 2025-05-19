<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Invoice;
use App\Models\Treatment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $firstDayOfMonth = Carbon::now()->startOfMonth();

        return view('dashboard', [
            'rdvToday' => Appointment::whereDate('date', $today)->count(),
            'newPatients' => Patient::where('created_at', '>=', $firstDayOfMonth)->count(),
            'unpaidInvoices' => Invoice::whereIn('status', ['Emise', 'Partiellement_payee', 'En_retard'])->count(),
            'revenue' => Invoice::where('created_at', '>=', $firstDayOfMonth)
                              ->where('status', 'Payee')
                              ->sum('total_amount'),
            'appointments' => Appointment::with(['patient', 'dentist.user'])
                                      ->whereDate('date', $today)
                                      ->orderBy('start_time')
                                      ->get(),
            'todayAppointments' => Appointment::with(['patient', 'dentist.user'])
                                           ->whereDate('date', $today)
                                           ->orderBy('start_time')
                                           ->get(),
        ]);
    }

    public function revenue()
    {
        $firstDayOfMonth = Carbon::now()->startOfMonth();
        $revenue = Invoice::where('created_at', '>=', $firstDayOfMonth)
                         ->where('status', 'Payee')
                         ->sum('total_amount');

        return response()->json(['revenue' => $revenue]);
    }

    public function todayAppointments()
    {
        $appointments = Appointment::with(['patient', 'dentist.user', 'planned_treatment'])
                                 ->whereDate('date', Carbon::today())
                                 ->orderBy('start_time')
                                 ->get()
                                 ->map(function ($appointment) {
                                     return [
                                         'id' => $appointment->id,
                                         'start_time' => $appointment->start_time,
                                         'end_time' => $appointment->end_time,
                                         'patient' => [
                                             'name' => $appointment->patient->first_name . ' ' . $appointment->patient->last_name,
                                             'phone' => $appointment->patient->phone
                                         ],
                                         'dentist' => [
                                             'name' => $appointment->dentist->user->first_name . ' ' . $appointment->dentist->user->last_name
                                         ],
                                         'reason' => $appointment->reason,
                                         'status' => $appointment->status,
                                         'treatment' => $appointment->planned_treatment ? $appointment->planned_treatment->name : null
                                     ];
                                 });

        return response()->json(['appointments' => $appointments]);
    }

    public function newPatients()
    {
        $firstDayOfMonth = Carbon::now()->startOfMonth();
        $newPatients = Patient::where('created_at', '>=', $firstDayOfMonth)
                            ->select('id', 'first_name', 'last_name', 'phone', 'email', 'created_at')
                            ->get();

        return response()->json(['newPatients' => $newPatients]);
    }

    public function unpaidInvoices()
    {
        $unpaidInvoices = Invoice::whereIn('status', ['Emise', 'Partiellement_payee', 'En_retard'])
                                ->with(['patient:id,first_name,last_name'])
                                ->select('id', 'invoice_number', 'patient_id', 'total_amount', 'due_date', 'status')
                                ->get()
                                ->map(function ($invoice) {
                                    return [
                                        'id' => $invoice->id,
                                        'invoice_number' => $invoice->invoice_number,
                                        'patient_name' => $invoice->patient->first_name . ' ' . $invoice->patient->last_name,
                                        'amount' => $invoice->total_amount,
                                        'due_date' => $invoice->due_date,
                                        'status' => $invoice->status
                                    ];
                                });

        return response()->json(['unpaidInvoices' => $unpaidInvoices]);
    }

    public function monthlyInvoices()
    {
        $firstDayOfMonth = Carbon::now()->startOfMonth();
        $monthlyInvoices = Invoice::where('created_at', '>=', $firstDayOfMonth)
                                 ->with(['patient:id,first_name,last_name'])
                                 ->select('id', 'invoice_number', 'patient_id', 'total_amount', 'status', 'created_at')
                                 ->get()
                                 ->map(function ($invoice) {
                                     return [
                                         'id' => $invoice->id,
                                         'invoice_number' => $invoice->invoice_number,
                                         'patient_name' => $invoice->patient->first_name . ' ' . $invoice->patient->last_name,
                                         'amount' => $invoice->total_amount,
                                         'status' => $invoice->status,
                                         'date' => $invoice->created_at->format('Y-m-d')
                                     ];
                                 });

        return response()->json(['monthlyInvoices' => $monthlyInvoices]);
    }
    public function parametrage()
    {
        return view('admin.parametrage');
    }
}
