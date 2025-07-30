<?php

namespace App\Http\Controllers;

use App\Models\MouvementStock;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class MouvementStockController extends Controller
{
    /**
    * Display a listing of the resource.
    */
    public function index(): View
    {
        $mouvementStocks = MouvementStock::with('user', 'stock')
        ->latest()
        ->paginate(10);

        return view('movements_stocks.index', compact('mouvementStocks'));
    }

    /**
    * Show the form for creating a new resource.
    */
    public function create(): View
    {
        $stocks = Stock::all();
        $movementTypes = MOUVEMENT_STOCK;

        return view('movements_stocks.create', compact('stocks', 'movementTypes'));
    }

    /**
    * Store a newly created resource in storage.
    */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'item_code' => 'required|string|max:255',
            'item_designation' => 'required|string|max:255',
            'item_quantity' => 'required|numeric|min:0',
            'item_measurement_unit' => 'required|string|max:50',
            'item_purchase_or_sale_price' => 'required|numeric|min:0',
            'item_movement_type' => 'required|in:' . implode(',', array_keys(MOUVEMENT_STOCK)),
            'stock_id' => 'required|exists:stocks,id',
        ]);

       // dd( $validator);
        if ($validator->fails()) {
            return redirect()->back()
            ->withErrors($validator)
            ->withInput();
        }

        // Use DB transaction

        try {
            DB::beginTransaction();

            $mouvementStock = new MouvementStock();
            $mouvementStock->fill($request->all());
            $mouvementStock->user_id = auth()->id();
            $mouvementStock->system_or_device_id = env('OBR_USERNAME', 'BUDENTAL');
            $mouvementStock->item_purchase_or_sale_currency = 'FBU';
            $mouvementStock->item_movement_date = now()->format('Y-m-d H:i:s');
            $mouvementStock->save();
            // Update stock quantity based on movement type
            $stock = Stock::findOrFail($request->stock_id);
            // Entre Ajustements
            if ($request->item_movement_type == "EAJ") {
                $stock->quantite = $request->item_quantity;
            }else if (str_starts_with($request->item_movement_type, 'E')) {
                // Entry movement types increase stock
                $stock->quantite += $request->item_quantity;
            }
            else {
                // Exit movement types decrease stock
                $stock->quantite -= $request->item_quantity;
            }
           $stock->price = $request->item_purchase_or_sale_price;
            $stock->save();
            DB::commit();
            return redirect()->route('stocks.index')
            ->with('success', 'Stock movement created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
            ->withErrors('Failed to create stock movement: ' . $e->getMessage());
        }
    }


    /**
    * Display the specified resource.
    */
    public function show(MouvementStock $mouvementStock): View
    {
        return view('movements_stocks.show', compact('mouvementStock'));
    }

    /**
    * Show the form for editing the specified resource.
    */
    public function edit(MouvementStock $mouvementStock): View
    {
        $stocks = Stock::all();
        $movementTypes =MOUVEMENT_STOCK;

        return view('movements_stocks.edit', compact('mouvementStock', 'stocks', 'movementTypes'));
    }

    /**
    * Update the specified resource in storage.
    */
    public function update(Request $request, MouvementStock $mouvementStock): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'system_or_device_id' => 'required|string|max:255',
            'item_code' => 'required|string|max:255',
            'item_designation' => 'required|string|max:255',
            'item_quantity' => 'required|numeric|min:0',
            'item_measurement_unit' => 'required|string|max:50',
            'item_purchase_or_sale_price' => 'required|numeric|min:0',
            'item_purchase_or_sale_currency' => 'required|string|max:10',
            'item_movement_type' => 'required|in:' . implode(',', array_keys(MOUVEMENT_STOCK)),
            'stock_id' => 'required|exists:stocks,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
            ->withErrors($validator)
            ->withInput();
        }

        // Revert previous stock quantity change
        $oldStock = Stock::findOrFail($mouvementStock->stock_id);
        if (str_starts_with($mouvementStock->item_movement_type, 'E')) {
            $oldStock->quantite -= $mouvementStock->item_quantity;
        } else {
            $oldStock->quantite += $mouvementStock->item_quantity;
        }
        $oldStock->save();

        // Update movement stock
        $mouvementStock->fill($request->all());
        $mouvementStock->save();

        // Update new stock quantity
        $newStock = Stock::findOrFail($request->stock_id);
        if (str_starts_with($request->item_movement_type, 'E')) {
            $newStock->quantite += $request->item_quantity;
        } else {
            $newStock->quantite -= $request->item_quantity;
        }
        $newStock->save();

        return redirect()->route('movements_stocks.index')
        ->with('success', 'Stock movement updated successfully.');
    }

    /**
    * Remove the specified resource from storage.
    */
    public function destroy(MouvementStock $mouvementStock): RedirectResponse
    {
        // Revert stock quantity change
        $stock = Stock::findOrFail($mouvementStock->stock_id);
        if (str_starts_with($mouvementStock->item_movement_type, 'E')) {
            $stock->quantite -= $mouvementStock->item_quantity;
        } else {
            $stock->quantite += $mouvementStock->item_quantity;
        }
        $stock->save();

        $mouvementStock->delete();

        return redirect()->route('movements_stocks.index')
        ->with('success', 'Stock movement deleted successfully.');
    }
}
