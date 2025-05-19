<?php

/**
 * Treatment Controller
 *
 * Handles CRUD operations for dental treatments.
 *
 * @version  GIT: 1.0.0
 * @category Controllers
 * @package  CabinetDentaire
 * @author   Advanced IT Research Team <contact@advanced-it-research.bi>
 * @license  MIT License
 * @link     https://github.com/Advanced-IT-Research-Burundi/cabinet-dentaire
 */

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Patient;
use App\Models\Treatment;
use App\Models\Appointment;
use App\Models\TreatmentType;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\TreatmentStoreRequest;
use App\Http\Requests\TreatmentUpdateRequest;
use App\Models\Dentist;

/**
 * TreatmentController Class
 *
 * @category Controllers
 * @package  CabinetDentaire
 * @author   Advanced IT Research Team <contact@advanced-it-research.bi>
 * @license  MIT License
 * @link     https://github.com/Advanced-IT-Research-Burundi/cabinet-dentaire
 */
class TreatmentController extends Controller
{
    /**
     * Display a listing of treatments.
     *
     * @param Request $request The request instance
     *
     * @return View Returns the treatments index view
     */
    public function index(Request $request): View
    {
        $query = Treatment::query()->with([
            'patient',
            'dentist',
            'treatmentType',
            'appointment'
        ]);

        // Filtres
        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        if ($request->filled('dentist_id')) {
            $query->where('dentist_id', $request->dentist_id);
        }

        if ($request->filled('treatment_type_id')) {
            $query->where('treatment_type_id', $request->treatment_type_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_start') && $request->filled('date_end')) {
            $query->whereBetween('date', [$request->date_start, $request->date_end]);
        } elseif ($request->filled('date_start')) {
            $query->where('date', '>=', $request->date_start);
        } elseif ($request->filled('date_end')) {
            $query->where('date', '<=', $request->date_end);
        }

        // Tri
        $sortBy = $request->input('sort_by', 'date');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->input('per_page', 10);
        $treatments = $query->paginate($perPage)->withQueryString();

        // Données pour les filtres
        $patients = Patient::orderBy('last_name')->get();
        $dentists = Dentist::orderBy('id')->get();
        $treatmentTypes = TreatmentType::orderBy('name')->get();
        $statuses = [
            'Planifie' => 'Planifié',
            'En_cours' => 'En cours',
            'Termine' => 'Terminé',
            'Annule' => 'Annulé'
        ];

        return view('treatment.index', compact(
            'treatments',
            'patients',
            'dentists',
            'treatmentTypes',
            'statuses',
            'request'
        ));
    }

    /**
     * Show the form for creating a new treatment.
     *
     * @return View Returns the treatment creation form
     */
    public function create(): View
    {
        $patients = Patient::all();
        $dentists = Dentist::with('user')->get();
        $treatmentTypes = TreatmentType::all();
        $appointments = Appointment::with('patient')->get();

        return view('treatment.create', compact(
            'patients',
            'dentists',
            'treatmentTypes',
            'appointments'
        ));
    }

    /**
     * Store a newly created treatment.
     *
     * @param TreatmentStoreRequest $request Validated request data
     *
     * @return RedirectResponse Returns a redirect response
     */
    public function store(TreatmentStoreRequest $request): RedirectResponse
    {
        Treatment::create($request->validated());
        return redirect()->route('treatments.index')
            ->with('success', 'Treatment created successfully.');
    }

    /**
     * Display the specified treatment.
     *
     * @param Treatment $treatment The treatment model instance
     *
     * @return View Returns the treatment details view
     */
    public function show(Treatment $treatment): View
    {
        return view('treatment.show', compact('treatment'));
    }

    /**
     * Show the form for editing a treatment.
     *
     * @param Treatment $treatment The treatment model instance
     *
     * @return View Returns the treatment edit form
     */
    public function edit(Treatment $treatment): View
    {
        $patients = Patient::all();
        $dentists = Dentist::with('user')->get();
        $treatmentTypes = TreatmentType::all();
        $appointments = Appointment::all();

        return view('treatment.edit', compact(
            'treatment',
            'patients',
            'dentists',
            'treatmentTypes',
            'appointments'
        ));
    }

    /**
     * Update the specified treatment.
     *
     * @param TreatmentUpdateRequest $request   Validated request data
     * @param Treatment             $treatment The treatment model instance
     *
     * @return RedirectResponse Returns a redirect response
     */
    public function update(
        TreatmentUpdateRequest $request,
        Treatment $treatment
    ): RedirectResponse {
        $treatment->update($request->validated());
        return redirect()->route('treatments.index')
            ->with('success', 'Treatment updated successfully.');
    }

    /**
     * Remove the specified treatment.
     *
     * @param Treatment $treatment The treatment model instance
     *
     * @return RedirectResponse Returns a redirect response
     */
    public function destroy(Treatment $treatment): RedirectResponse
    {
        $treatment->delete();
        return redirect()->route('treatments.index')
            ->with('success', 'Treatment deleted successfully.');
    }
}
