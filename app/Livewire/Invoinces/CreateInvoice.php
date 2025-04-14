<?php

namespace App\Livewire\Invoinces;

use App\Models\Patient;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateInvoice extends Component
{
    public $patientID;
    public $patientName;
    public $patient;
    public $selectedTreatments = [];
    public $treatments;

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
            $invoice = \App\Models\Invoice::create([
                'patient_id' => $this->patient->id,
                'total_amount' => $this->patient->treatementsNotPaids
                    ->whereIn('id', $this->selectedTreatments)
                    ->sum('applied_price'),
            'status' => 'Brouillon',
            'invoice_number' => rand(1000, 9999),
            'issue_date' => now(),
            'due_date' => now()->addDays(30),
            'insurance_amount' => 0,
            'patient_amount' => 0,
            'notes' => '',
            'creator_id' => auth()->user()->id
        ]);
        // Mettre à jour l'état des traitements sélectionnés
        foreach ($this->selectedTreatments as $treatementId) {
            $treatement = \App\Models\Treatment::where('id', $treatementId)->first();
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
