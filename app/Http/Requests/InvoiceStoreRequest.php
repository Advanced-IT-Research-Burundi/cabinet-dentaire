<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceStoreRequest extends FormRequest
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
            'invoice_number' => ['required', 'string', 'max:50', 'unique:invoices,invoice_number'],
            'issue_date' => ['required', 'date'],
            'due_date' => ['required', 'date'],
            'total_amount' => ['required', 'numeric', 'between:-99999999.99,99999999.99'],
            'insurance_amount' => ['required', 'numeric', 'between:-99999999.99,99999999.99'],
            'patient_amount' => ['required', 'numeric', 'between:-99999999.99,99999999.99'],
            'status' => ['required', 'in:Brouillon,Emise,Partiellement_payee,Payee,Annulee,En_retard'],
            'notes' => ['required', 'string'],
            'creator_id' => ['required', 'integer', 'exists:users,id'],
            'created_at' => ['required'],
            'updated_at' => ['required'],
        ];
    }
}
