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

    public function cancelToObr($id ){

        $reson = request("reason");

        if(!$reson || $reson == ""){
            return back()->with("error", "Veuillez fournir une raison d'annulation.");

        }

        $invoice = Invoice::find($id);

        $invoice->is_canceled = true;
        $invoice->is_canceled_at = now();
        $invoice->is_canceled_by = auth()->user()->id;
        $invoice->is_canceled_reason = $reson;
        $invoice->save();

        return back()->with("success", "Facture annulée avec succès.");
    }



    public function invoices_obr(Request $request)
    {
        $query = Invoice::with('obrPointer', 'patient', 'creator')
        ->latest();

        // Filtrer par numéro de facture
        if ($request->filled('facture_no')) {
            $query->where('id', $request->facture_no);
        }

        // Filtrer par patient (customer_name)
        if ($request->filled('patient')) {
            $query->whereHas('patient', function($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->patient . '%')
                  ->orWhere('middle_name', 'like', '%' . $request->patient . '%')
                  ->orWhere('last_name', 'like', '%' . $request->patient . '%')

                ;
            });
        }

        // Filtrer par date
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Filtrer par status
        if ($request->filled('status')) {
            $query->where('is_sent_to_obr', $request->status);
        }
        // Exécuter la requête
        $invoices = $query->paginate(15); // Pagination au lieu de get()

        return view("invoice.obr_history", compact("invoices"));
    }
    public function index(Request $request)
    {
        $query = Invoice::query();

        // Recherche par patient
        if ($request->filled('patient')) {
            $searchTerm = $request->patient;
            $searchTerms = explode(' ', $searchTerm);

            $query->whereHas('patient', function ($q) use ($searchTerm, $searchTerms) {
                // Recherche exacte du terme complet
                $q->where(function($query) use ($searchTerm) {
                    $query->where('first_name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('middle_name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('last_name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('phone', 'like', '%' . $searchTerm . '%');
                });

                // Si plusieurs mots sont saisis, recherche par combinaison
                if (count($searchTerms) > 1) {
                    $q->orWhere(function($query) use ($searchTerms) {
                        foreach ($searchTerms as $term) {
                            if (strlen($term) > 2) {
                                $query->orWhere('first_name', 'like', '%' . $term . '%')
                                    ->orWhere('middle_name', 'like', '%' . $term . '%')
                                    ->orWhere('last_name', 'like', '%' . $term . '%');
                            }
                        }
                    });
                }

                // Recherche par concaténation des noms
                $q->orWhereRaw("CONCAT(first_name, ' ', COALESCE(middle_name, ''), ' ', last_name) LIKE ?", ['%' . $searchTerm . '%']);
            });
        }

        // Recherche par date
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('issue_date', [
                $request->date_from,
                $request->date_to
            ]);
        } elseif ($request->filled('date_from')) {
            $query->where('issue_date', '>=', $request->date_from);
        } elseif ($request->filled('date_to')) {
            $query->where('issue_date', '<=', $request->date_to);
        }

        // Recherche par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $invoices = $query->with('patient')
            ->latest()
            ->paginate(10)
            ->withQueryString(); // Garde les paramètres de recherche lors de la pagination

        return view('invoice.index', compact('invoices'));
    }

    public function monthly(){
        return back();
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

    public function show(Invoice $invoice)
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

    public function generatePdf($id)
    {
        $invoice = Invoice::with('patient', 'creator')->find($id);

        $pdf = \PDF::loadView('facture.invoice', compact('invoice'));

        return $pdf->stream('facture_' . $invoice->invoice_number . '.pdf');
    }
}
