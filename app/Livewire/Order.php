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

    public function mount(){
        $this->patients = Patient::take(10)->get();
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
    }

    public function render()
    {
        return view('livewire.order');
    }
}
