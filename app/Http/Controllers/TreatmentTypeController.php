<?php

/**
 * Fichier TreatmentTypeController.php
 * 
 * Ce fichier contient le contrôleur pour la gestion des types de traitements.
 * 
 * PHP version 8.1
 * 
 * @category Controllers
 * @package  App\Http\Controllers
 * @author   BurundiTech Team <contact@budental.bi>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://budental.bi
 */

namespace App\Http\Controllers;

use App\Models\TreatmentType;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Gestion des types de traitements dans l'application.
 *
 * @category Controllers
 * @package  App\Http\Controllers
 * @author   BurundiTech Team <contact@budental.bi>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://budental.bi
 */
class TreatmentTypeController extends Controller
{
    /**
     * Affiche la liste des types de traitements
     *
     * @return View
     */
    public function index(): View
    {
        $treatmentTypes = TreatmentType::orderBy('name')->paginate(10);
        return view('settings.treatment-types.index', compact('treatmentTypes'));
    }

    /**
     * Affiche le formulaire de création
     *
     * @return View
     */
    public function create(): View
    {
        return view('settings.treatment-types.create');
    }

    /**
     * Enregistre un nouveau type de traitement
     *
     * @param Request $request Les données du formulaire
     * 
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'name' => 'required|string|max:100|unique:treatment_types',
                'description' => 'nullable|string',
                'average_duration' => 'nullable|integer|min:1',
                'base_price' => 'nullable|numeric|min:0',
                'category' => 'nullable|string|max:100',
                'code' => 'nullable|string|max:20',
                'active' => 'boolean',
            ]
        );

        TreatmentType::create($validated);

        return redirect()
            ->route('settings.treatment-types.index')
            ->with('success', 'Type de traitement créé avec succès.');
    }

    /**
     * Affiche le formulaire d'édition
     *
     * @param TreatmentType $treatmentType Type de traitement à éditer
     * 
     * @return View
     */
    public function edit(TreatmentType $treatmentType): View
    {
        return view('settings.treatment-types.edit', compact('treatmentType'));
    }

    /**
     * Met à jour le type de traitement
     *
     * @param Request       $request       
     * @param TreatmentType $treatmentType 
     * 
     * @return RedirectResponse
     */
    public function update(
        Request $request,
        TreatmentType $treatmentType
    ): RedirectResponse {
        $uniqueRule = 'unique:treatment_types,name,' . $treatmentType->id;
        
        $validated = $request->validate(
            [
                'name' => ['required', 'string', 'max:100', $uniqueRule],
                'description' => 'nullable|string',
                'average_duration' => 'nullable|integer|min:1',
                'base_price' => 'nullable|numeric|min:0',
                'category' => 'nullable|string|max:100',
                'code' => 'nullable|string|max:20',
                'active' => 'boolean',
            ]
        );

        $treatmentType->update($validated);

        return redirect()
            ->route('settings.treatment-types.index')
            ->with('success', 'Type de traitement modifié avec succès.');
    }

    /**
     * Supprime le type de traitement
     *
     * @param TreatmentType $treatmentType Type de traitement à supprimer
     * 
     * @return RedirectResponse
     */
    public function destroy(TreatmentType $treatmentType): RedirectResponse
    {
        $treatmentType->delete();

        return redirect()
            ->route('settings.treatment-types.index')
            ->with('success', 'Type de traitement supprimé avec succès');
    }
}
