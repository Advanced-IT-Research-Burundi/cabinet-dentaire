<?php

/**
 * Fichier AppointmentController.php
 *
 * Ce fichier contient le contrôleur pour la gestion des rendez-vous.
 *
 * PHP version 8.1
 *
 * @category Controllers
 * @package  App\Http\Controllers
 * @author   BurundiTech Team <contact@budental.bi>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://budental.bi
 */

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Dentist;
use App\Models\Patient;
use App\Models\TreatmentType;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;


/**
 * Gestion des rendez-vous dans l'application.
 *
 * @category Controllers
 * @package  App\Http\Controllers
 * @author   BurundiTech Team <contact@budental.bi>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://budental.bi
 */
class AppointmentController extends Controller
{
    /**
     * Affiche la liste des rendez-vous
     *
     * @param Request $request Les paramètres de recherche
     *
     * @return View
     */
    public function index(Request $request): View
    {
        $query = Appointment::with(['patient', 'dentist', 'plannedTreatment']);

        // Recherche par patient
        if ($request->filled('patient')) {
            $patientSearch = $request->input('patient');
            $query->whereHas('patient', function ($q) use ($patientSearch) {
                $q->where('first_name', 'like', "%{$patientSearch}%")
                    ->orWhere('last_name', 'like', "%{$patientSearch}%");
            });
        }

        // Recherche par dentiste
        if ($request->filled('dentist')) {
            $dentistSearch = $request->input('dentist');
            $query->whereHas('dentist.user', function ($q) use ($dentistSearch) {
                $q->where('first_name', 'like', "%{$dentistSearch}%")
                    ->orWhere('last_name', 'like', "%{$dentistSearch}%");
            });
        }

        // Recherche par type de traitement
        if ($request->filled('treatment')) {
            $treatmentSearch = $request->input('treatment');
            $query->whereHas('plannedTreatment', function ($q) use ($treatmentSearch) {
                $q->where('name', 'like', "%{$treatmentSearch}%");
            });
        }

        $appointments = $query->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('appointments.index', compact('appointments'));
    }
    public function show(Appointment $appointment)
    {
        return view('appointments.show', compact('appointment'));
    }


    public function calendar()
    {
        return view('appointments.calendar');
    }

    public function events()
    {
        $appointments = Appointment::with(['patient', 'dentist', 'plannedTreatment'])
            ->whereHas('patient')
            ->whereHas('dentist')
            ->whereHas('plannedTreatment')
            ->get();

        $events = $appointments->map(function ($appointment) {
            return [
                'id' => $appointment->id,
                'title' => $appointment->patient->first_name . ' ' . $appointment->patient->last_name,
                'start' => $appointment->date->format('Y-m-d') . 'T' . $appointment->start_time,
                'end' => $appointment->date->format('Y-m-d') . 'T' . $appointment->end_time,
                'treatment' => $appointment->plannedTreatment->name,
                'backgroundColor' => $appointment->dentist->calendar_color ?? '#2C3E50',
                'borderColor' => $appointment->dentist->calendar_color ?? '#2C3E50',
                'extendedProps' => [
                    'description' => $appointment->dentist?->user?->first_name . ' ' . $appointment->dentist?->user?->last_name,
                    'created_by' => $appointment->creator?->name

                ]
            ];
        });

        return $events;


    }

    /**
     * Affiche le formulaire de création d'un rendez-vous
     *
     * @return View
     */
    // public function create(): View
    // {
    //     $patients = Patient::all();
    //     $dentists = Dentist::with('user')
    //        // ->where('is_active', true)
    //         ->get();
    //     $treatmentTypes = TreatmentType::orderBy('name')->get();

    //     return view(
    //         'appointments.create',
    //         compact('patients', 'dentists', 'treatmentTypes')
    //     );
    // }

    /**
     * Enregistre un nouveau rendez-vous
     *
     * @param Request $request Les données du formulaire
     *
     * @return RedirectResponse
     */

     public function create()
    {
            $appointment = new Appointment();
            $patients = Patient::orderBy('last_name')->get();
            $dentists = Dentist::with('user')->get();
            $treatmentTypes = TreatmentType::orderBy('name')->get();

            return view('appointments.create', compact('appointment', 'patients', 'dentists', 'treatmentTypes'));
        }

    public function store(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'patient_id' => 'required|exists:patients,id',
                'dentist_id' => 'required|exists:dentists,id',
                'date' => 'required|date|after_or_equal:today',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
                'reason' => 'nullable|string|max:1000',
                'notes' => 'nullable|string|max:1000',
                'status' => 'required|in:Confirme,Annule,Termine,En_attente,Reporte',
                'planned_treatment_id' => 'required|exists:treatment_types,id',
            ]);

            // Vérifier les conflits de rendez-vous pour le dentiste
            $conflict = Appointment::where('dentist_id', $validated['dentist_id'])
                ->where('date', $validated['date'])
                ->where(function ($query) use ($validated) {
                    $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                        ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                        ->orWhere(function ($query) use ($validated) {
                            $query->where('start_time', '<=', $validated['start_time'])
                                    ->where('end_time', '>=', $validated['end_time']);
                        });
                })
                ->exists();

            if ($conflict) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Un autre rendez-vous existe déjà dans ce créneau horaire pour ce dentiste.');
            }

            $validated['creator_id'] = auth()->id();
            $validated['reminder_sent'] = false;

            Appointment::create($validated);

            return redirect()
                ->route('appointments.index')
                ->with('success', 'Le rendez-vous a été créé avec succès.');

        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création du rendez-vous. Vérifiez les données saisies et veuillez réessayer.');
        }
    }


     public function edit(Appointment $appointment)
    {
        $patients = Patient::orderBy('last_name')->get();
        $dentists = Dentist::with('user')->get();
        $treatmentTypes = TreatmentType::orderBy('name')->get();

        return view('appointments.edit', compact('appointment', 'patients', 'dentists', 'treatmentTypes'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'dentist_id' => 'required|exists:dentists,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'planned_treatment_id' => 'required|exists:treatment_types,id',
            'reason' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'required|in:En_attente,Confirme,Annule,Reporte',
        ]);

        // Vérifier les conflits de rendez-vous pour le dentiste
            $conflict = Appointment::where('dentist_id', $validated['dentist_id'])
                ->where('date', $validated['date'])
                ->where(function ($query) use ($validated) {
                    $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                        ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                        ->orWhere(function ($query) use ($validated) {
                            $query->where('start_time', '<=', $validated['start_time'])
                                    ->where('end_time', '>=', $validated['end_time']);
                        });
                })
                ->exists();

            if ($conflict) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Un autre rendez-vous existe déjà dans ce créneau horaire pour ce dentiste.');
            }
        $appointment->update($validated);

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Rendez-vous mis à jour avec succès.');
    }

    /**
     * Affiche les rendez-vous du jour
     *
     * @return View
     */
    public function today(): View
    {
        $appointments = Appointment::with(['patient.user', 'dentist.user'])
            ->whereDate('date', today())
            ->orderBy('start_time')
            ->get();

        return view('appointments.today', compact('appointments'));
    }

    // Faire terminer un rendez-vous
    public function finish(Appointment $appointment): RedirectResponse
    {
        $appointment->update(['status' => 'Termine']);

        return redirect()
            ->route('appointments.today')
            ->with('success', 'Le rendez-vous a été marqué comme terminé.');
    }
    // Annuler un rendez-vous
    public function cancel(Appointment $appointment): RedirectResponse
    {
        $appointment->update(['status' => 'Annule']);

        return redirect()
            ->route('appointments.today')
            ->with('success', 'Le rendez-vous a été annulé avec succès.');
    }
    // Reporter un rendez-vous
    public function reschedule(Appointment $appointment): RedirectResponse
    {
        $appointment->update(['status' => 'Reporte']);

        return redirect()
            ->route('appointments.index')
            ->with('success', 'Le rendez-vous a été reporté avec succès.');
    }
    // Supprimer un rendez-vous
    public function destroy(Appointment $appointment): RedirectResponse
    {
        $appointment->delete();

        return redirect()
            ->route('appointments.index')
            ->with('success', 'Le rendez-vous a été supprimé avec succès.');
    }
}
