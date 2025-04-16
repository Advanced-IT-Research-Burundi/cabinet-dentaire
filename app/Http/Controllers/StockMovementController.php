<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockMovementStoreRequest;
use App\Http\Requests\StockMovementUpdateRequest;
use App\Models\StockMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockMovementController extends Controller
{
    public function index(Request $request): Response
    {
        $stockMovements = StockMovement::all();

        return view('stockMovement.index', [
            'stockMovements' => $stockMovements,
        ]);
    }

    public function create(Request $request): Response
    {
        return view('stockMovement.create');
    }

    public function store(StockMovementStoreRequest $request): Response
    {
        $stockMovement = StockMovement::create($request->validated());

        $request->session()->flash('stockMovement.id', $stockMovement->id);

        return redirect()->route('stockMovements.index');
    }

    public function show(Request $request, StockMovement $stockMovement): Response
    {
        return view('stockMovement.show', [
            'stockMovement' => $stockMovement,
        ]);
    }

    public function edit(Request $request, StockMovement $stockMovement): Response
    {
        return view('stockMovement.edit', [
            'stockMovement' => $stockMovement,
        ]);
    }

    public function update(StockMovementUpdateRequest $request, StockMovement $stockMovement): Response
    {
        $stockMovement->update($request->validated());

        $request->session()->flash('stockMovement.id', $stockMovement->id);

        return redirect()->route('stockMovements.index');
    }

    public function destroy(Request $request, StockMovement $stockMovement): Response
    {
        $stockMovement->delete();

        return redirect()->route('stockMovements.index');
    }
}
