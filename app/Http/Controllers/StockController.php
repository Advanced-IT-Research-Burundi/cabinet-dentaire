<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Category;
use App\Models\Supplier;
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
                $query->where('product_name', 'like', "%{$search}%");
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->paginate(10);

        return view('stock.index', compact('stocks'));
    }

    /**
     * Display the specified stock.
     */
    public function movement($stock)
    {
        $stock = Stock::findOrFail($stock);
        $movements = $stock->movements()->paginate(10);
        $mouvementsTypes = MOUVEMENT_STOCK;
        return view('stock.movement', compact('stock', 'movements', 'mouvementsTypes'));
    }

    // Show the form for creating a new stock
    public function create()
    {
        $categories = Category::all();
        $suppliers = Supplier::all(); // Retrieve all suppliers for the dropdown
        return view('stock.create', compact('categories', 'suppliers'));
    }

    // Store a newly created stock in storage
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code_product' => 'nullable|string|max:255',
            'product_name' => 'required|string|max:255',
            'marque' => 'nullable|string|max:255',
            'unite_mesure' => 'nullable|string|max:255',
            'quantite' => 'required|numeric|min:0',
            'quantite_alert' => 'nullable|numeric|min:0',
            'price' => 'nullable|numeric|min:0',
            'price_ttc' => 'nullable|numeric|min:0',
            'price_max' => 'nullable|numeric|min:0',
            'price_tvac' => 'nullable|numeric|min:0',
            'taux_tva' => 'nullable|numeric|min:0',
            'item_ott_tax' => 'nullable|numeric|min:0',
            'item_tsce_tax' => 'nullable|numeric|min:0',
            'price_min' => 'nullable|numeric|min:0',
            'date_expiration' => 'nullable|date',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:100',
            'supplier' => 'nullable|string|max:255',
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:Disponible,Faible_stock,En_rupture,Expire',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);

        Stock::create($validated);

        return redirect()->route('stocks.index')->with('success', 'Stock créé avec succès.');
    }

    // Display the specified stock
    public function show(Stock $stock)
    {
        $category = $stock->category; // Retrieve the associated category
        $user = $stock->user; // Retrieve the associated user
        return view('stock.show', compact('stock', 'category', 'user'));
    }

    // Show the form for editing the specified stock
    public function edit($id)
    {
        $stock = Stock::findOrFail($id);
        $categories = Category::all(); // Retrieve all categories for the dropdown
        $suppliers = Supplier::all(); // Retrieve all suppliers for the dropdown
        return view('stock.edit', compact('stock', 'categories', 'suppliers'));
    }

    // Update the specified stock in storage
    public function update(Request $request, Stock $stock)
    {
        $validated = $request->validate([
            'code_product' => 'nullable|string|max:255',
            'product_name' => 'required|string|max:255',
            'marque' => 'nullable|string|max:255',
            'unite_mesure' => 'nullable|string|max:255',
            'quantite' => 'required|numeric|min:0',
            'quantite_alert' => 'nullable|numeric|min:0',
            'price' => 'nullable|numeric|min:0',
            'price_ttc' => 'nullable|numeric|min:0',
            'price_max' => 'nullable|numeric|min:0',
            'price_tvac' => 'nullable|numeric|min:0',
            'taux_tva' => 'nullable|numeric|min:0',
            'item_ott_tax' => 'nullable|numeric|min:0',
            'item_tsce_tax' => 'nullable|numeric|min:0',
            'price_min' => 'nullable|numeric|min:0',
            'date_expiration' => 'nullable|date',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:Disponible,Faible_stock,En_rupture,Expire',
            'supplier_id' => 'nullable|exists:suppliers,id',
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
