<?php

namespace App\Http\Controllers;

use App\Http\Requests\CaisseStoreRequest;
use App\Http\Requests\CaisseUpdateRequest;
use App\Models\Caisse;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CaisseController extends Controller
{
    public function index(Request $request)
    {
        $caisses = Caisse::all();

        return view('caisse.index', [
            'caisses' => $caisses,
        ]);
    }

    public function create(Request $request)
    {
        $users = User::all();
        return view('caisse.create', [
            'users' => $users,
        ]);
    }

    public function store(CaisseStoreRequest $request)
    {
        $caisse = Caisse::create($request->validated());

        $request->session()->flash('caisse.id', $caisse->id);

        return redirect()->route('caisses.index');
    }

    public function show(Request $request, Caisse $caisse)
    {
        return view('caisse.show', [
            'caisse' => $caisse,
        ]);
    }

    public function edit(Request $request, Caisse $caisse)
    {
        return view('caisse.edit', [
            'caisse' => $caisse,
        ]);
    }

    public function update(CaisseUpdateRequest $request, Caisse $caisse)
    {
        $caisse->update($request->validated());

        $request->session()->flash('caisse.id', $caisse->id);

        return redirect()->route('caisses.index');
    }

    public function destroy(Request $request, Caisse $caisse)
    {
        $caisse->delete();

        return redirect()->route('caisses.index');
    }
}
