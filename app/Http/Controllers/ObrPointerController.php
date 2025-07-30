<?php

namespace App\Http\Controllers;

use App\Http\Requests\ObrPointerStoreRequest;
use App\Http\Requests\ObrPointerUpdateRequest;
use App\Models\ObrPointer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ObrPointerController extends Controller
{
    public function index(Request $request): Response
    {
        $obrPointers = ObrPointer::all();

        return view('obrPointer.index', [
            'obrPointers' => $obrPointers,
        ]);
    }

    public function create(Request $request): Response
    {
        return view('obrPointer.create');
    }

    public function store(ObrPointerStoreRequest $request): Response
    {
        $obrPointer = ObrPointer::create($request->validated());

        $request->session()->flash('obrPointer.id', $obrPointer->id);

        return redirect()->route('obrPointers.index');
    }

    public function show(Request $request, ObrPointer $obrPointer): Response
    {
        return view('obrPointer.show', [
            'obrPointer' => $obrPointer,
        ]);
    }

    public function edit(Request $request, ObrPointer $obrPointer): Response
    {
        return view('obrPointer.edit', [
            'obrPointer' => $obrPointer,
        ]);
    }

    public function update(ObrPointerUpdateRequest $request, ObrPointer $obrPointer): Response
    {
        $obrPointer->update($request->validated());

        $request->session()->flash('obrPointer.id', $obrPointer->id);

        return redirect()->route('obrPointers.index');
    }

    public function destroy(Request $request, ObrPointer $obrPointer): Response
    {
        $obrPointer->delete();

        return redirect()->route('obrPointers.index');
    }
}
