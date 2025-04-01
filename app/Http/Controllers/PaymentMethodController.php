<?php

/**
 * Fichier PaymentMethodController.php
 * 
 * Ce fichier contient le contrôleur pour la gestion des méthodes de paiement.
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

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Gestion des méthodes de paiement dans l'application.
 *
 * @category Controllers
 * @package  App\Http\Controllers
 * @author   BurundiTech Team <contact@budental.bi>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://budental.bi
 */
class PaymentMethodController extends Controller
{
    /**
     * Affiche la liste des méthodes de paiement
     *
     * @return View
     */
    public function index(): View
    {
        $paymentMethods = PaymentMethod::orderBy('name')->paginate(10);
        return view('settings.payment-methods.index', compact('paymentMethods'));
    }

    /**
     * Affiche le formulaire de création
     *
     * @return View
     */
    public function create(): View
    {
        return view('settings.payment-methods.create');
    }

    /**
     * Enregistre une nouvelle méthode de paiement
     *
     * @param Request $request Les données du formulaire
     * 
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:payment_methods',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'requires_confirmation' => 'boolean',
            'confirmation_instructions' => 'nullable|string|required_if:requires_confirmation,true',
        ]);

        PaymentMethod::create($validated);

        return redirect()
            ->route('settings.payment-methods.index')
            ->with('success', 'Méthode de paiement créée avec succès');
    }

    /**
     * Affiche le formulaire d'édition
     *
     * @param PaymentMethod $paymentMethod Méthode de paiement à éditer
     * 
     * @return View
     */
    public function edit(PaymentMethod $paymentMethod): View
    {
        return view('settings.payment-methods.edit', compact('paymentMethod'));
    }

    /**
     * Met à jour la méthode de paiement
     *
     * @param Request       $request       Les données du formulaire
     * @param PaymentMethod $paymentMethod Méthode de paiement à mettre à jour
     * 
     * @return RedirectResponse
     */
    public function update(
        Request $request,
        PaymentMethod $paymentMethod
    ): RedirectResponse {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:payment_methods,name,'
                . $paymentMethod->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'requires_confirmation' => 'boolean',
            'confirmation_instructions' => 'nullable|string|required_if:requires_confirmation,true',
        ]);

        $paymentMethod->update($validated);

        return redirect()
            ->route('settings.payment-methods.index')
            ->with('success', 'Méthode de paiement mise à jour avec succès');
    }

    /**
     * Supprime la méthode de paiement
     *
     * @param PaymentMethod $paymentMethod Méthode de paiement à supprimer
     * 
     * @return RedirectResponse
     */
    public function destroy(PaymentMethod $paymentMethod): RedirectResponse
    {
        $paymentMethod->delete();

        return redirect()
            ->route('settings.payment-methods.index')
            ->with('success', 'Méthode de paiement supprimée avec succès');
    }
}
