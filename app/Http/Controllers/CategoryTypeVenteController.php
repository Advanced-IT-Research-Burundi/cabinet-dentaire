<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryTypeVenteRequest;
use App\Http\Requests\UpdateCategoryTypeVenteRequest;
use App\Models\CategoryTypeVente;
use Illuminate\Http\Request;

class CategoryTypeVenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $categoryTypeVentes = CategoryTypeVente::when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('category-type-ventes.index', compact('categoryTypeVentes', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('category-type-ventes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryTypeVenteRequest $request)
    {
        CategoryTypeVente::create($request->validated());

        return redirect()->route('category-type-ventes.index')
            ->with('success', 'Type de vente créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CategoryTypeVente $categoryTypeVente)
    {
        return view('category-type-ventes.show', compact('categoryTypeVente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CategoryTypeVente $categoryTypeVente)
    {
        return view('category-type-ventes.edit', compact('categoryTypeVente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryTypeVenteRequest $request, CategoryTypeVente $categoryTypeVente)
    {
        $categoryTypeVente->update($request->validated());

        return redirect()->route('category-type-ventes.index')
            ->with('success', 'Type de vente mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CategoryTypeVente $categoryTypeVente)
    {
        $categoryTypeVente->delete();

        return redirect()->route('category-type-ventes.index')
            ->with('success', 'Type de vente supprimé avec succès.');
    }
}
