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
use App\Models\TreatementTreatmentType;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\TreatmentStoreRequest;
use App\Http\Requests\TreatmentUpdateRequest;
use App\Models\Dentist;
use Carbon\Carbon;

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
            'dentist.user',
            'treatmentType',
            'appointment',
            'user'
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
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Filter for non-admins
        if (!auth()->user()->isAdmin()) {
            $query->where(function($q) {
                // If user is a dentist, show treatments where they are the dentist
                if (auth()->user()->isDentiste()) {
                     $q->where('dentist_id', auth()->user()->dentist?->id);
                }
                // OR show treatments created by the user
                $q->orWhere('user_id', auth()->user()->id);
            });
        }

        // Pagination
        $perPage = $request->input('per_page', 10);
        $treatments = $query->paginate($perPage)->withQueryString();

        // Données pour les filtres
        $patients = Patient::orderBy('last_name')->take(LOAD_DATA)->get();
        $dentists = Dentist::orderBy('id')->take(LOAD_DATA)->get();
        $treatmentTypes = TreatmentType::orderBy('name')->take(LOAD_DATA)->get();
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
        $patients = Patient::orderBy('last_name')->take(LOAD_DATA)->get();
        $dentists = Dentist::orderBy('id')->take(LOAD_DATA)->get();
        $treatmentTypes = TreatmentType::orderBy('name')->take(LOAD_DATA)->get();
        $appointments = Appointment::with(['patient', 'dentist.user', 'plannedTreatments'])
                        ->whereDoesntHave('treatment')
                        ->where('date', '>=', Carbon::now()->subDays(30))
                        ->orderBy('date', 'asc')
                        ->get();

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
        try {
            \DB::beginTransaction();
            $treatmentsData = json_decode($request->treatments_data, true);

            if (empty($treatmentsData)) {
                return back()->withErrors(['treatments_data' => 'Veuillez ajouter au moins un traitement.'])->withInput();
            }


            foreach ($treatmentsData as $treatmentItem) {               
                  $treatment = Treatment::create([
                'patient_id' => $request->patient_id,
                'dentist_id' => $request->dentist_id,
                'appointment_id' => $request->appointment_id,
                'date' => $request->date,
                'description' => $request->description,
                'medical_notes' => $request->medical_notes,
                'applied_price' =>$treatmentItem['quantity'] * $treatmentItem['price'] ,
                'status' => $request->status,
                'user_id' => auth()->user()->id,
            ]);
                TreatementTreatmentType::create([
                    'treatment_id' => $treatment->id,
                    'treatment_type_id' => $treatmentItem['id'],
                    'quantity' => $treatmentItem['quantity'] ?? 1,
                    'price' => $treatmentItem['price'] ?? 0,
                ]);
            }

            \DB::commit();
            return redirect()->route('treatments.index')
                ->with('success', 'Traitement créé avec succès.');
        } catch (\Throwable $th) {
            \DB::rollBack();
            return back()->withErrors(['error' => 'Une erreur est survenue lors de la création du traitement. Veuillez réessayer.'])->withInput();
        }
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
        try {
            \DB::beginTransaction();

            $treatment->update($request->validated());

            // Mettre à jour les types de traitement avec quantité et prix
            if ($request->has('treatments_data') && $request->treatments_data) {
                $treatmentsData = json_decode($request->treatments_data, true);

                // Supprimer les anciens enregistrements
                TreatementTreatmentType::where('treatment_id', $treatment->id)->delete();

                // Ajouter les nouveaux
                foreach ($treatmentsData as $treatmentItem) {
                    TreatementTreatmentType::create([
                        'treatment_id' => $treatment->id,
                        'treatment_type_id' => $treatmentItem['id'],
                        'quantity' => $treatmentItem['quantity'] ?? 1,
                        'price' => $treatmentItem['price'] ?? 0,
                    ]);
                }
            }

            \DB::commit();
            return redirect()->route('treatments.index')
                ->with('success', 'Traitement mis à jour avec succès.');
        } catch (\Throwable $th) {
            \DB::rollBack();
            return back()->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour du traitement.'])->withInput();
        }
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
