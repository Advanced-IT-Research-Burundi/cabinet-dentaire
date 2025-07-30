<?php

namespace App\Livewire\Invoinces;

use App\Http\Controllers\SendInvoiceToOBR;
use App\Models\Caisse;
use App\Models\CaisseDetail;
use App\Models\Company;
use App\Models\MouvementStock;
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
            return $product['id'] ?? 0;
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

        $caisse = Caisse::where('user_id', auth()->user()->id)->first();
        if(!$caisse){
                 session()->flash('error', "Veuillez Nous excuse vous n'avez droit de créer une facture");
           return;
        }
        if (empty($this->selectedTreatments) && empty($this->productsChoosed)) {
            session()->flash('error', 'Veuillez sélectionner au moins un élément ');
            return;
     }

        try {
            DB::beginTransaction();
            $treatements = \App\Models\Treatment::
            with(['dentist', 'treatmentType'])
            ->whereIn('id', $this->selectedTreatments)->get();

            // Update Stock QUantite

            $listeroducts = collect($this->productsChoosed)->map(function ($product) {
                return [

                        "item_designation" => $product['product_name'],
                        "item_quantity" => $product['quantite'],
                        "item_price" => $product['price'],
                        "item_ct" => 0,
                        "item_tl" => 0,
                        "item_price_nvat" => 0,
                        "vat" => "0",
                        "item_price_wvat" => $product['price'] * $product['quantite'],
                        "item_total_amount" => $product['price'] * $product['quantite'],
                        "item_product_detail_id" => $product['id'],
                        "item_model" => Stock::class,

                ];
            });

            $listTraitements  = $treatements->map(function ($treatement) {
                return [
                    "item_designation" => $treatement->treatmentType->name,
                    "item_quantity" =>1,
                    "item_price" => $treatement->applied_price,
                    "item_ct" => 0,
                    "item_tl" => 0,
                    "item_price_nvat" => 0,
                    "vat" => "0",
                    "item_price_wvat" => $treatement->applied_price,
                    "item_total_amount" => $treatement->applied_price,
                    "item_product_detail_id" => $treatement->id,
                    "item_model" => \App\Models\Treatment::class,
                ];
            });

            $invoice = \App\Models\Invoice::create([
                'patient_id' => $this->patient->id,
                'total_amount' => $treatements->sum('applied_price')+$listeroducts->sum('item_total_amount'),
                'status' => 'Brouillon',
                'invoice_number' => 12,
                'invoice_date' => now(),
                'invoice_type' => 'FN',
                'payment_type' => '1',
                'invoice_currency' => 'BIF',
                'issue_date' => now(),
                'due_date' => now()->addDays(30),
                'insurance_amount' => 0,
                'patient_amount' => 0,
                'notes' => '',
                'client' =>  json_encode([
                    "customer_name" => $this->patient->full_name,
                    "customer_TIN" => $this->patient->nif,
                    "customer_address" => $this->patient->address,
                    "vat_customer_payer" => $this->patient->vat_customer_payer ?? 0,
                ]),
                'company' => Company::current()->toJson(),
                'description' => json_encode(array_merge($listTraitements->toArray(), $listeroducts->toArray())),
                'creator_id' => auth()->user()->id
        ]);

        $invoice->invoice_number =  str_pad($invoice->id, 4, '0', STR_PAD_LEFT);
        $invoice->invoice_identifier = SendInvoiceToOBR::getInvoiceSignature($invoice->invoice_number, $invoice->invoice_date);
        $invoice->save();
        $this->updateStockQuantite($invoice->id);
        // Mettre à jour l'état des traitements sélectionnés
        foreach ($treatements as $treatement) {
            $treatement->update(['payment_status' => 'Payee', 'invoice_id' => $invoice->id]);
        }
        // Enregistre montant sur la caisse de l'utilisateur
        $caisse->montant +=  $invoice->total_amount;
        $caisse->save();
        CaisseDetail::create([
            'caisse_id' => $caisse->id,
            'type' => "MONTANT FACTURE No ". $invoice->id,
            'price' => 0,
            'total' => $invoice->total_amount,
            'status' => '1',
            'user_id' => auth()->user()->id,
        ]);

        DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Une erreur est survenue: ' . $e->getMessage());
            return;
        }
        session()->flash('success', 'Facture créée avec succès');
        return redirect()->route('invoices.show', $invoice->id);
    }

    public function updateStockQuantite($invoiceID)
    {
        $listesMouvements = [];
        $listeUpdateStocks = [];
        foreach ($this->productsChoosed as $product) {
            $stock = Stock::find($product['id']);
            if ($stock) {
                if ($stock->quantite < $product['quantite']) {
                    session()->flash('error', 'Quantité insuffisante pour le produit ' . $stock->product_name);
                    throw new \Exception('Quantité insuffisante pour le produit ' . $stock->product_name);
                }

                $listesMouvements[] = [
                    'system_or_device_id' => env('OBR_USERNAME', 'BUDENTAL'),
                    'item_code' => $stock->id,
                    'item_designation' => $stock->product_name,
                    'item_quantity' => $product['quantite'],
                    'item_measurement_unit' => $stock->unite_mesure ?? '-',
                    'item_purchase_or_sale_price' => $stock->price,
                    'item_purchase_or_sale_currency' => 'FBU',
                    'item_movement_date' => now()->format('Y-m-d H:i:s'),
                    'item_movement_invoice_ref' => $invoiceID,
                    'item_movement_type' => 'SN',
                    'stock_id' => $stock->id,
                    'user_id' => auth()->user()->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                unset($stock->created_at, $stock->updated_at);
                $arrayStock = $stock->toArray();

                // Vérifie si la date est présente et la convertit
                if (!empty($arrayStock['date_expiration'])) {
                    $arrayStock['date_expiration'] = \Carbon\Carbon::parse($arrayStock['date_expiration'])->format('Y-m-d H:i:s');
                }

                $listeUpdateStocks[] = array_merge($arrayStock, [
                    'quantite' => $stock->quantite - $product['quantite']
                ]);
            }
        }
        try {
            DB::beginTransaction();
            MouvementStock::insert($listesMouvements);
            Stock::upsert($listeUpdateStocks, ['id'], ['quantite']);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', 'Une erreur est survenue: ' . $th->getMessage());
            throw $th;

        }
    }
}
