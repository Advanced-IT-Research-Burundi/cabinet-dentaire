<?php

namespace App\Livewire\Invoinces;

use App\Models\Patient;
use Livewire\Component;

class CreateInvoice extends Component
{
    public $patientID;
    public $patientName;
    public $patient;
    public function render()
    {
        return view('livewire.invoinces.create-invoice');
    }

    public function search()
    {
        // Prioritize search by ID, then search other fields
        $this->patient = Patient::when($this->patientID, function ($query) {
                $query->where('id', '=',  $this->patientID );
            })
            ->when(!$this->patientID, function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->patientName . '%')
                        ->orWhere('first_name', 'like', '%' . $this->patientName . '%')
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
}
