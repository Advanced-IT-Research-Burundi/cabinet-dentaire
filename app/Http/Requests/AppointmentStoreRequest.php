<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentStoreRequest extends FormRequest
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
            'date' => ['required', 'date'],
            'start_time' => ['required'],
            'end_time' => ['required'],
            'reason' => ['nullable', 'string'],
            'status' => ['required', 'in:Confirme,Annule,Termine,En_attente,Reporte'],
            'notes' => ['nullable', 'string'],
            'reminder_sent' => ['required'],
            'created_at' => ['required'],
            'creator_id' => ['required', 'integer', 'exists:users,id'],
            'planned_treatment_id' => ['nullable', 'integer', 'exists:treatment_types,id'],
        ];
    }
}
