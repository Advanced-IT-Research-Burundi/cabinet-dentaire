<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientStoreRequest;
use App\Http\Requests\PatientUpdateRequest;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PatientController extends Controller
{
    public function index(Request $request): Response
    {
        $patients = Patient::all();

        return view('patient.index', [
            'patients' => $patients,
        ]);
    }

    public function create(Request $request): Response
    {
        return view('patient.create');
    }

    public function store(PatientStoreRequest $request): Response
    {
        $patient = Patient::create($request->validated());

        $request->session()->flash('patient.id', $patient->id);

        return redirect()->route('patients.index');
    }

    public function show(Request $request, Patient $patient): Response
    {
        return view('patient.show', [
            'patient' => $patient,
        ]);
    }

    public function edit(Request $request, Patient $patient): Response
    {
        return view('patient.edit', [
            'patient' => $patient,
        ]);
    }

    public function update(PatientUpdateRequest $request, Patient $patient): Response
    {
        $patient->update($request->validated());

        $request->session()->flash('patient.id', $patient->id);

        return redirect()->route('patients.index');
    }

    public function destroy(Request $request, Patient $patient): Response
    {
        $patient->delete();

        return redirect()->route('patients.index');
    }
}
