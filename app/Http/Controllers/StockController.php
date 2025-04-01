<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    // Display a listing of the stocks
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $stocks = Stock::query()
            ->when($search, function ($query, $search) {
                $query->where('product_name', 'like', "%{$search}%")
                      ->orWhere('category', 'like', "%{$search}%");
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->paginate(10);

        return view('stock.index', compact('stocks'));
    }

    // Show the form for creating a new stock
    public function create()
    {
        return view('stock.create');
    }

    // Store a newly created stock in storage
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'available_quantity' => 'required|integer|min:0',
            'unit_measure' => 'nullable|string|max:50',
            'minimum_quantity' => 'nullable|integer|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'expiration_date' => 'nullable|date',
            'status' => ['required'], // Removed enum reference
        ]);

        Stock::create($validated);

        return redirect()->route('stocks.index')->with('success', 'Stock créé avec succès.');
    }

    // Display the specified stock
    public function show(Stock $stock)
    {
        return view('stock.show', compact('stock'));
    }

    // Show the form for editing the specified stock
    public function edit(Stock $stock)
    {
        return view('stock.edit', compact('stock'));
    }

    // Update the specified stock in storage
    public function update(Request $request, Stock $stock)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'available_quantity' => 'required|integer|min:0',
            'unit_measure' => 'nullable|string|max:50',
            'minimum_quantity' => 'nullable|integer|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'expiration_date' => 'nullable|date',
            'status' => ['required'], // Removed enum reference
        ]);

        $stock->update($validated);

        return redirect()->route('stocks.index')->with('success', 'Stock mis à jour avec succès.');
    }

    // Remove the specified stock from storage
    public function destroy(Stock $stock)
    {
        $stock->delete();

        return redirect()->route('stocks.index')->with('success', 'Stock supprimé avec succès.');
    }
}
