<?php

namespace App\Http\Controllers;

use App\Http\Requests\ObrRequestBodyStoreRequest;
use App\Http\Requests\ObrRequestBodyUpdateRequest;
use App\Models\ObrRequestBody;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ObrRequestBodyController extends Controller
{
    public function index(Request $request): Response
    {
        $obrRequestBodies = ObrRequestBody::all();

        return view('obrRequestBody.index', [
            'obrRequestBodies' => $obrRequestBodies,
        ]);
    }

    public function create(Request $request): Response
    {
        return view('obrRequestBody.create');
    }

    public function store(ObrRequestBodyStoreRequest $request): Response
    {
        $obrRequestBody = ObrRequestBody::create($request->validated());

        $request->session()->flash('obrRequestBody.id', $obrRequestBody->id);

        return redirect()->route('obrRequestBodies.index');
    }

    public function show(Request $request, ObrRequestBody $obrRequestBody): Response
    {
        return view('obrRequestBody.show', [
            'obrRequestBody' => $obrRequestBody,
        ]);
    }

    public function edit(Request $request, ObrRequestBody $obrRequestBody): Response
    {
        return view('obrRequestBody.edit', [
            'obrRequestBody' => $obrRequestBody,
        ]);
    }

    public function update(ObrRequestBodyUpdateRequest $request, ObrRequestBody $obrRequestBody): Response
    {
        $obrRequestBody->update($request->validated());

        $request->session()->flash('obrRequestBody.id', $obrRequestBody->id);

        return redirect()->route('obrRequestBodies.index');
    }

    public function destroy(Request $request, ObrRequestBody $obrRequestBody): Response
    {
        $obrRequestBody->delete();

        return redirect()->route('obrRequestBodies.index');
    }
}
