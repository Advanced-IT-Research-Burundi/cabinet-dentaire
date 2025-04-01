<?php

namespace App\Http\Controllers;

use App\Http\Requests\TreatmentStoreRequest;
use App\Http\Requests\TreatmentUpdateRequest;
use App\Models\Treatment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TreatmentController extends Controller
{
    public function index(Request $request): Response
    {
        $treatments = Treatment::all();

        return view('treatment.index', [
            'treatments' => $treatments,
        ]);
    }

    public function create(Request $request): Response
    {
        return view('treatment.create');
    }

    public function store(TreatmentStoreRequest $request): Response
    {
        $treatment = Treatment::create($request->validated());

        $request->session()->flash('treatment.id', $treatment->id);

        return redirect()->route('treatments.index');
    }

    public function show(Request $request, Treatment $treatment): Response
    {
        return view('treatment.show', [
            'treatment' => $treatment,
        ]);
    }

    public function edit(Request $request, Treatment $treatment): Response
    {
        return view('treatment.edit', [
            'treatment' => $treatment,
        ]);
    }

    public function update(TreatmentUpdateRequest $request, Treatment $treatment): Response
    {
        $treatment->update($request->validated());

        $request->session()->flash('treatment.id', $treatment->id);

        return redirect()->route('treatments.index');
    }

    public function destroy(Request $request, Treatment $treatment): Response
    {
        $treatment->delete();

        return redirect()->route('treatments.index');
    }
}
