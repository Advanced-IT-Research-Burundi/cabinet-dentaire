<?php

namespace App\Livewire\Invoinces;

use App\Models\Caisse;
use App\Models\Company;
use App\Models\Patient;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateInvoice extends Component
{
    public $patientID;
    public $patientName;
    public $patient;
    public $selectedTreatments = [];
    public $treatments;
    public $productName;
    public $products;
    public $productsChoosed = [];
    public $totalPriceProducts = 0;

    public function mount()
    {
        $this->treatments = [];
    }

    public function render()
    {
        return view('livewire.invoinces.create-invoice');
    }

    public function clear()
    {
        $this->patientID = null;
        $this->patientName = null;
        $this->patient = null;
    }

    public function removeProduct($index)
    {
        $this->productsChoosed = array_filter($this->productsChoosed, function ($product) use ($index) {
            return $product['id'] !== $index;
        });
    }

    public function updated($propertyName)
    {

        if ($propertyName == 'productsChoosed') {
            $this->totalPriceProducts = collect($this->productsChoosed)->sum('price');
        }
    }

    public function addProductToInvoice()
    {

    }

    public function getTotalPrixProduitsProperty()
    {
        // returne la somme des prix Multiplier par la quantite
        return collect($this->productsChoosed)->sum(function ($product) {
            return $product['price'] * $product['quantite'];
        });
    }


    public function searchProduct()
    {
        $itemsList = array_map(function ($product) {
            return $product['id'];
        }, $this->productsChoosed);
        $this->products = Stock::where('product_name', 'like', '%' . $this->productName . '%')
        ->whereNotIn('id', $itemsList)
        ->take(5)->get();
    }

    public function addProduct($id)
    {
        $product = Stock::find($id);
        $this->productsChoosed[$id] = [
            'id' => $product->id,
            'product_name' => $product->product_name,
            'price' => $product->price,
            'quantite' => 1,
            'quantite_disponible' => $product->quantite,
        ];
        $this->searchProduct();
    }

    public function clearProduct()
    {
        $this->productName = null;
    }

    public function search()
    {
        // Prioritize search by ID, then search other fields
        $this->patient = Patient::
        with(['treatementsNotPaids', 'treatementsNotPaids.dentist', 'treatementsNotPaids.treatmentType'])
        ->when($this->patientID, function ($query) {
                $query->where('id', '=',  $this->patientID );
            })
            ->when(!$this->patientID, function ($query) {
                $query->where(function ($query) {
                    $query->where('first_name', 'like', '%' . $this->patientName . '%')
                        ->orWhere('middle_name', 'like', '%' . $this->patientName . '%')
                        ->orWhere('last_name', 'like', '%' . $this->patientName . '%')
                        ->orWhere('birth_date', 'like', '%' . $this->patientName . '%')
                        ->orWhere('gender', 'like', '%' . $this->patientName . '%')
                        ->orWhere('phone', 'like', '%' . $this->patientName . '%')
                        ->orWhere('secondary_phone', 'like', '%' . $this->patientName . '%')
                        ->orWhere('email', 'like', '%' . $this->patientName . '%')
                        ->orWhere('address', 'like', '%' . $this->patientName . '%');
                });
            })
            ->first();

        if ($this->patient) {
            $this->patientID = $this->patient->id;
            $this->patientName = $this->patient->full_name;
        } else {
            $this->patientID = null;
            $this->patientName = null;
        }
    }

    public function selectAll()
    {
        if ($this->patient) {
            $this->selectedTreatments = $this->patient->treatementsNotPaids->pluck('id')->toArray();
        }
    }

    public function deselectAll()
    {
        $this->selectedTreatments = [];
    }

    public function createInvoice()
    {
        if (!$this->patient) {
            session()->flash('error', 'Veuillez d\'abord sélectionner un patient');
            return;
        }

        if (empty($this->selectedTreatments)) {
            session()->flash('error', 'Veuillez sélectionner au moins un traitement');
            return;
        }

        try {
            DB::beginTransaction();
            $treatements = \App\Models\Treatment::
            with(['dentist', 'treatmentType'])
            ->whereIn('id', $this->selectedTreatments)->get();

            $treatementsValues = $treatements->map(function ($treatement) {
                return [
                    'id' => $treatement->id,
                    'applied_price' => $treatement->applied_price,
                    'dentist' => $treatement->dentist->name,
                    'treatmentType' => $treatement->treatmentType->name
                ];
            });

            $invoice = \App\Models\Invoice::create([
                'patient_id' => $this->patient->id,
                'total_amount' => $treatements->sum('applied_price'),
                'status' => 'Brouillon',
                'invoice_number' => rand(1000, 9999),
                'issue_date' => now(),
                'due_date' => now()->addDays(30),
                'insurance_amount' => 0,
                'patient_amount' => 0,
                'notes' => '',
                'client' => $this->patient->toJson(),
                'company' => Company::current()->toJson(),
                'description' => json_encode($treatementsValues->toArray()),
                'creator_id' => auth()->user()->id
        ]);
        // Mettre à jour l'état des traitements sélectionnés
        foreach ($treatements as $treatement) {
            $treatement->update(['payment_status' => 'Payee', 'invoice_id' => $invoice->id]);
        }
        DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Une erreur est survenue: ' . $e->getMessage());
            return;
        }
        session()->flash('success', 'Facture créée avec succès');
        return redirect()->route('invoices.index');
    }
}
