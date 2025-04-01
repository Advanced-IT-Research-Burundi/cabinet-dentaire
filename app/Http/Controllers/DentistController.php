<?php

namespace App\Http\Controllers;

use App\Http\Requests\DentistStoreRequest;
use App\Http\Requests\DentistUpdateRequest;
use App\Models\Dentist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DentistController extends Controller
{
    public function index(Request $request): Response
    {
        $dentists = Dentist::all();

        return view('dentist.index', [
            'dentists' => $dentists,
        ]);
    }

    public function create(Request $request): Response
    {
        return view('dentist.create');
    }

    public function store(DentistStoreRequest $request): Response
    {
        $dentist = Dentist::create($request->validated());

        $request->session()->flash('dentist.id', $dentist->id);

        return redirect()->route('dentists.index');
    }

    public function show(Request $request, Dentist $dentist): Response
    {
        return view('dentist.show', [
            'dentist' => $dentist,
        ]);
    }

    public function edit(Request $request, Dentist $dentist): Response
    {
        return view('dentist.edit', [
            'dentist' => $dentist,
        ]);
    }

    public function update(DentistUpdateRequest $request, Dentist $dentist): Response
    {
        $dentist->update($request->validated());

        $request->session()->flash('dentist.id', $dentist->id);

        return redirect()->route('dentists.index');
    }

    public function destroy(Request $request, Dentist $dentist): Response
    {
        $dentist->delete();

        return redirect()->route('dentists.index');
    }
}
