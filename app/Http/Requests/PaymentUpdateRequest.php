<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentUpdateRequest extends FormRequest
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
            'invoice_id' => ['required', 'integer', 'exists:invoices,id'],
            'patient_id' => ['required', 'integer', 'exists:patients,id'],
            'payment_method_id' => ['required', 'integer', 'exists:payment_methods,id'],
            'transaction_number' => ['nullable', 'string', 'max:100'],
            'amount' => ['nullable', 'numeric', 'between:-99999999.99,99999999.99'],
            'payment_date' => ['required'],
            'status' => ['required', 'in:Valide,En_attente,Rejete,Rembourse'],
            'notes' => ['nullable', 'string'],
            'proof_of_payment' => ['nullable', 'string', 'max:255'],
            'operator_id' => ['required', 'integer', 'exists:users,id'],
            'created_at' => ['required'],
        ];
    }
}
