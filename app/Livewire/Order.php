<?php

namespace App\Livewire;

use App\Models\Patient;
use App\Models\Stock;
use App\Models\Treatment;
use Livewire\Component;

class Order extends Component
{
    public $patient_id;
    public $treatments;
    public $patients;
    public $products;
    public $type = 'treatment';
    public $searchProduct;
    public $search_patient;
    public $loading_patients = true;
    public Patient $selectedPatient;
    public $selectedTreatments = [];
    public $selectedProducts = [];
    public $totalAmount = 0;
    public $patientAmount = 0;
    public $insuranceAmount = 0;

    public function mount(){
        $this->selectedPatient = Patient::find(1);
        $this->patients = Patient::take(10)->get();
        $this->treatments = Treatment::where('patient_id', $this->selectedPatient->id)->get();
        $this->products = Stock::take(10)->get();
    }

    public function updatedSearchPatient()
    {
        $this->patients = Patient::where('first_name', 'like', '%' . $this->search_patient . '%')->orWhere('last_name', 'like',  '%' . $this->search_patient . '%')->take(10)->get();

        $this->loading_patients = false;
    }

    public function updatedSelectedProducts($value, $name){

        // dd($value, $name);
        $attribute = explode('.', $name);
        if($attribute[1] == 'price'){
            $this->selectedProducts[$attribute[0]]['total_price'] = $this->selectedProducts[$attribute[0]]['qty'] * floatval($value);
            $this->getTotalAmount();
        }

        if ($attribute[1] == 'qty') {
            $this->selectedProducts[$attribute[0]]['total_price'] = $this->selectedProducts[$attribute[0]]['price'] * floatval($value);
            $this->getTotalAmount();
        }
    }

    public function selectPatient(Patient $patient){
        $this->reset('search_patient');
        $this->patients = Patient::take(10)->get();
        $this->selectedPatient = $patient;

        $this->treatments = Treatment::where('patient_id', $this->selectedPatient->id)->get();
        $this->selectedTreatments = [];
        $this->selectedProducts = [];
        $this->reset(['totalAmount', 'insuranceAmount', 'patientAmount']);

    }

    public function addTreatment($treatment)
    {
        if (!in_array($treatment, $this->selectedTreatments)) {
            $this->selectedTreatments[] = $treatment;
        }

        $this->getTotalAmount();
    }

    public function removeTreatment($treatmentIndex)
    {
        unset($this->selectedTreatments[$treatmentIndex]);
    }

    public function addProduct($product)
    {
        if (!in_array($product, $this->selectedProducts)) {
            $this->selectedProducts[] = [
                'id' => $product['id'],
                'product_name' => $product['product_name'],
                'qty' => 1,
                'max_qty' => $product['quantite'],
                'price' => $product['price'],
                'total_price' => $product['price'], // Initial price
            ];
        }

        $this->getTotalAmount();
    }

    public function removeProduct($productIndex)
    {
        unset($this->selectedProducts[$productIndex]);
        $this->selectedProducts = array_values($this->selectedProducts);

        $this->getTotalAmount();
    }

    public function getTotalAmount(){
        $totalAmount = 0;
        foreach ($this->selectedTreatments as $treatment) {
            $totalAmount += $treatment['applied_price'];
        }

        foreach ($this->selectedProducts as $product) {
            $totalAmount += $product['total_price'];
        }

        $this->totalAmount = $totalAmount;

        $this->insuranceAmount = $totalAmount * ($this->selectedPatient?->assurance->coverage_percentage / 100);
        $this->patientAmount = number_format($totalAmount - $this->insuranceAmount, '2');
    }

    public function store(){
        
    }

    public function render()
    {
        return view('livewire.order');
    }
}
