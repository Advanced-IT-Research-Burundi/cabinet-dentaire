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
    public function index(Request $request): Response
    {
        $invoices = Invoice::all();

        return view('invoice.index', [
            'invoices' => $invoices,
        ]);
    }

    public function create(Request $request): Response
    {
        return view('invoice.create');
    }

    public function store(InvoiceStoreRequest $request): Response
    {
        $invoice = Invoice::create($request->validated());

        $request->session()->flash('invoice.id', $invoice->id);

        return redirect()->route('invoices.index');
    }

    public function show(Request $request, Invoice $invoice): Response
    {
        return view('invoice.show', [
            'invoice' => $invoice,
        ]);
    }

    public function edit(Request $request, Invoice $invoice): Response
    {
        return view('invoice.edit', [
            'invoice' => $invoice,
        ]);
    }

    public function update(InvoiceUpdateRequest $request, Invoice $invoice): Response
    {
        $invoice->update($request->validated());

        $request->session()->flash('invoice.id', $invoice->id);

        return redirect()->route('invoices.index');
    }

    public function destroy(Request $request, Invoice $invoice): Response
    {
        $invoice->delete();

        return redirect()->route('invoices.index');
    }
}
