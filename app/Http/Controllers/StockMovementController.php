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
    public function index(Request $request)
    {
        $stockMovements = StockMovement::all();
        //dd($stockMovements);

        return view('stockMovement.index', [
            'stockMovements' => $stockMovements,
        ]);
    }

    public function create(Request $request)
    {
        return view('stockMovement.create');
    }

    public function store(StockMovementStoreRequest $request)
    {
        // dd($request->all());
        $stockMovement = StockMovement::create($request->validated());
        $request->session()->flash('stockMovement.id', $stockMovement->id);

        return redirect()->route('stock_movements.index');
    }

    public function show(Request $request, StockMovement $stockMovement)
    {
        return view('stockMovement.show', [
            'stockMovement' => $stockMovement,
        ]);
    }

    public function edit(Request $request, StockMovement $stockMovement)
    {
        return view('stockMovement.edit', [
            'stockMovement' => $stockMovement,
        ]);
    }

    public function update(StockMovementUpdateRequest $request, StockMovement $stockMovement)
    {
        $stockMovement->update($request->validated());

        $request->session()->flash('stockMovement.id', $stockMovement->id);

        return redirect()->route('stock_movements.index');
    }

    public function destroy(Request $request, StockMovement $stockMovement)
    {
        $stockMovement->delete();

        return redirect()->route('stock_movements.index');
    }
}
