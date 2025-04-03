<?php

namespace App\Http\Controllers;

use App\Models\Assurance;
use Illuminate\Http\Request;

class AssuranceController extends Controller
{
    public function index()
    {
        $assurances = Assurance::paginate(10);
        return view('assurance.index', compact('assurances'));
    }

    public function create()
    {
        return view('assurance.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'coverage_percentage' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
        ]);

        Assurance::create($validated);

        return redirect()->route('assurances.index')->with('success', 'Assurance créée avec succès.');
    }

    public function edit(Assurance $assurance)
    {
        return view('assurance.edit', compact('assurance'));
    }

    public function update(Request $request, Assurance $assurance)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'coverage_percentage' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
        ]);

        $assurance->update($validated);

        return redirect()->route('assurances.index')->with('success', 'Assurance mise à jour avec succès.');
    }

    public function destroy(Assurance $assurance)
    {
        $assurance->delete();

        return redirect()->route('assurances.index')->with('success', 'Assurance supprimée avec succès.');
    }
}
