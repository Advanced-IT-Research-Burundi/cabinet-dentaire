<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TreatmentUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'patient_id' => ['required', 'integer', 'exists:patients,id'],
            'dentist_id' => ['required', 'integer', 'exists:dentists,id'],
            'treatment_type_id' => ['required', 'integer', 'exists:treatment_types,id'],
            'appointment_id' => ['required', 'integer', 'exists:appointments,id'],
            'date' => ['required', 'date'],
            'description' => ['nullable', 'string'],
            'medical_notes' => ['nullable', 'string'],
            'applied_price' => ['nullable', 'numeric', 'between:-99999999.99,99999999.99'],
            'status' => ['required', 'in:Planifie,En_cours,Termine,Annule'],
            'created_at' => ['required'],
            'updated_at' => ['required'],
        ];
    }
}
