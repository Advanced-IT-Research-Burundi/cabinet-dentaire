<?php

namespace App\Livewire;

use App\Models\Patient;
use App\Models\Treatment;
use Livewire\Component;

class Order extends Component
{
    public $patient_id;
    public $treatments;
    public $patients;
    public $products;
    public $type = 'treatment';
    public $search_patient;
    public $loading_patients = true;
    public Patient $selectedPatient;
    public $selectedTreatments = [];
    public $totalAmount = 0;
    public $patientAmount;
    public $insuranceAmount;

    public function mount(){
        // $this->selectedPatient = Patient::find(1);
        $this->patients = Patient::take(10)->get();
        // $this->treatments = Treatment::where('patient_id', $this->selectedPatient->id)->get();
    }

    public function updatedSearchPatient()
    {
        $this->patients = Patient::where('first_name', 'like', '%' . $this->search_patient . '%')->orWhere('last_name', 'like',  '%' . $this->search_patient . '%')->take(10)->get();

        $this->loading_patients = false;
    }

    public function selectPatient(Patient $patient){
        $this->reset('search_patient');
        $this->patients = Patient::take(10)->get();
        $this->selectedPatient = $patient;

        $this->treatments = Treatment::where('patient_id', $this->selectedPatient->id)->get();
        $this->selectedTreatments = [];

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

    public function getTotalAmount(){
        $totalAmount = 0;
        foreach ($this->selectedTreatments as $index => $treatment) {
            $totalAmount += $treatment['applied_price'];
        }

        $this->totalAmount = $totalAmount;

        $this->patientAmount = $totalAmount * ($this->selectedPatient?->assurance->coverage_percentage / 100);
        $this->insuranceAmount = $totalAmount * ($this->selectedPatient?->assurance->coverage_percentage / 100);
    }

    public function render()
    {
        return view('livewire.order');
    }
}
