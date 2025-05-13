<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Assurance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('insurance_number', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($request->has('gender') && $request->gender != '') {
            $query->where('gender', $request->gender);
        }

        $patients = $query->orderBy('created_at', 'desc')
                         ->paginate(10)
                         ->withQueryString();

        return view('patient.index', compact('patients'));
    }

    public function create()
    {
        $assurances = Assurance::all(); // Fetch assurances
        return view('patient.create', compact('assurances'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'birth_date' => 'required|date',
            'gender' => 'required|in:M,F,Autre',
            'phone' => 'nullable|string|max:20',
            'secondary_phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:patients,email|max:100',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'insurance_number' => 'nullable|string|max:100',
            'insurance_company' => 'nullable|string|max:100',
            'medical_history' => 'nullable|string',
            'allergies' => 'nullable|string',
            'insurance_id' => 'nullable|exists:assurances,id',
        ]);

        $validated['creator_id'] = Auth::id();

        Patient::create($validated);

        return redirect()->route('patients.index')
            ->with('success', 'Patient ajouté avec succès.');
    }

    public function show(Patient $patient)
    {
        $patient->load(['appointments.dentist', 'treatments', 'invoices']);
        return view('patient.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        $assurances = Assurance::all(); // Fetch assurances
        return view('patient.edit', compact('patient', 'assurances'));
    }

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'birth_date' => 'required|date',
            'gender' => 'required|in:M,F,Autre',
            'phone' => 'nullable|string|max:20',
            'secondary_phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100|unique:patients,email,' . $patient->id,
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'insurance_number' => 'nullable|string|max:100',
            'insurance_company' => 'nullable|string|max:100',
            'medical_history' => 'nullable|string',
            'allergies' => 'nullable|string',
            'insurance_id' => 'nullable|exists:assurances,id',
        ]);

        $patient->update($validated);

        return redirect()->route('patients.index')
            ->with('success', 'Patient mis à jour avec succès.');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('patients.index')
            ->with('success', 'Patient supprimé avec succès.');
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $patients = Patient::where('first_name', 'like', "%{$query}%")
            ->orWhere('last_name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'first_name', 'last_name', 'email', 'phone']);

        return $patients;
    }
}
