<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceStoreRequest;
use App\Http\Requests\InvoiceUpdateRequest;
use App\Models\Invoice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::query();

        // Recherche par patient
        if ($request->has('patient')) {
            $query->whereHas('patient', function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->patient . '%')
                    ->orWhere('middle_name', 'like', '%' . $request->patient . '%')
                    ->orWhere('last_name', 'like', '%' . $request->patient . '%')
                    ->orWhere('phone', 'like', '%' . $request->patient . '%');
            });
        }

        // Recherche par date
        if ($request->has('date_from') || $request->has('date_to')) {
            $query->whereBetween('issue_date', [
                $request->date_from ?? '2000-01-01',
                $request->date_to ?? now()
            ]);
        }

        // Recherche par statut
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $invoices = $query->with('patient')
            ->latest()
            ->paginate(10);

        return view('invoice.index', compact('invoices'));
    }

    public function create(Request $request)
    {
        return view('invoice.create');
    }

    public function store(InvoiceStoreRequest $request)
    {
        $invoice = Invoice::create($request->validated());

        $request->session()->flash('invoice.id', $invoice->id);

        return redirect()->route('invoices.index');
    }

    public function show(Request $request, Invoice $invoice)
    {
        return view('invoice.show', [
            'invoice' => $invoice,
        ]);
    }

    public function edit(Request $request, Invoice $invoice)
    {
        return view('invoice.edit', [
            'invoice' => $invoice,
        ]);
    }

    public function update(InvoiceUpdateRequest $request, Invoice $invoice)
    {
        $invoice->update($request->validated());

        $request->session()->flash('invoice.id', $invoice->id);

        return redirect()->route('invoices.index');
    }

    public function destroy(Request $request, Invoice $invoice)
    {
        $invoice->delete();

        return redirect()->route('invoices.index');
    }
}
