<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\DetailOrder;
use App\Models\Stock;
use App\Models\Patient;
use App\Models\Assurance;
use App\Models\Treatment;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with('detailOrders')->paginate(10);
        return view('order.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $type = $request->get('type', 'treatment'); // Default to treatment
        $stocks = Stock::all();
        $patients = Patient::all();
        $treatments = Treatment::all();

        return view('order.create', compact('stocks', 'patients', 'type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'type' => 'required|in:treatment,product',
            'tax_rate' => 'required|numeric|min:0',
            'amount' => 'required|numeric|min:0',
            'final_amount' => 'required|numeric|min:0',
            'type_paiement' => 'required|string|max:255',
            'assurance_id' => 'nullable|exists:assurances,id',
            'detail_orders' => 'required_if:type,product|array',
            'detail_orders.*.product_id' => 'required_if:type,product|exists:stocks,id',
            'detail_orders.*.quantite' => 'required_if:type,product|numeric|min:0',
            'detail_orders.*.price_unitaire' => 'required_if:type,product|numeric|min:0',
            'treatments' => 'required_if:type,treatment|array',
            'treatments.*.treatment_id' => 'required_if:type,treatment|exists:treatments,id',
        ]);

        $order = Order::create($validated);

        if ($validated['type'] === 'product') {
            foreach ($validated['detail_orders'] as $detail) {
                $detail['order_id'] = $order->id;
                DetailOrder::create($detail);
            }
        } elseif ($validated['type'] === 'treatment') {
            foreach ($validated['treatments'] as $treatment) {
                $order->treatments()->attach($treatment['treatment_id']);
            }
        }

        return redirect()->route('orders.index')->with('success', 'Commande créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load('detailOrders');
        return view('order.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $stocks = Stock::all();
        $patients = Patient::all();
        $assurances = Assurance::all();
        $order->load('detailOrders');
        return view('order.edit', compact('order', 'stocks', 'patients', 'assurances'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'tax' => 'nullable|numeric',
            'total_quantity' => 'nullable|numeric',
            'total_sacs' => 'nullable|numeric',
            'amount_tax' => 'nullable|numeric',
            'type_paiement' => 'required|string|max:255',
            'date_emission' => 'nullable|date',
            'date_echeance' => 'nullable|date',
            'amount' => 'required|numeric',
            'status' => 'required|in:Brouillon,Emise,Partiellement_payee,Payee,Annulee,En_Retard',
            'notes' => 'nullable|string',
            'detail_orders' => 'required|array',
            'detail_orders.*.product_id' => 'required|exists:stocks,id',
            'detail_orders.*.quantite' => 'required|numeric|min:0',
            'detail_orders.*.price_unitaire' => 'required|numeric|min:0',
            'tax_rate' => 'required|numeric|min:0',
            'tax_amount' => 'required|numeric|min:0',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'discount_amount' => 'required|numeric|min:0',
            'insurance_covered_amount' => 'required|numeric|min:0',
            'patient_amount' => 'required|numeric|min:0',
        ]);

        $order->update($validated);

        $order->detailOrders()->delete();
        foreach ($validated['detail_orders'] as $detail) {
            $detail['order_id'] = $order->id;
            DetailOrder::create($detail);
        }

        return redirect()->route('orders.index')->with('success', 'Commande mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Commande supprimée avec succès.');
    }
}
