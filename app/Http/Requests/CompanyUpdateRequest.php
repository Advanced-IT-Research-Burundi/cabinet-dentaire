<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyUpdateRequest extends FormRequest
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
            'tp_name' => ['nullable', 'string', 'max:250'],
            'tp_type' => ['nullable', 'string', 'max:250'],
            'tp_TIN' => ['nullable', 'string', 'max:250'],
            'tp_trade_number' => ['nullable', 'string', 'max:250'],
            'tp_postal_number' => ['nullable', 'string', 'max:250'],
            'tp_phone_number' => ['nullable', 'string', 'max:250'],
            'tp_address_privonce' => ['nullable', 'string', 'max:250'],
            'tp_address_avenue' => ['nullable', 'string', 'max:250'],
            'tp_address_quartier' => ['nullable', 'string', 'max:250'],
            'tp_address_commune' => ['nullable', 'string', 'max:250'],
            'tp_address_rue' => ['nullable', 'string', 'max:250'],
            'tp_address_number' => ['nullable', 'string', 'max:250'],
            'vat_taxpayer' => ['nullable', 'string', 'max:250'],
            'ct_taxpayer' => ['nullable', 'string', 'max:250'],
            'tl_taxpayer' => ['nullable', 'string', 'max:250'],
            'tp_fiscal_center' => ['nullable', 'string', 'max:250'],
            'tp_activity_sector' => ['nullable', 'string', 'max:250'],
            'tp_legal_form' => ['nullable', 'string', 'max:250'],
            'payment_type' => ['nullable', 'string', 'max:250'],
            'is_actif' => ['required'],
            'user_id' => ['required', 'integer'],
            'created_at' => ['required'],
            'updated_at' => ['required'],
            'deleted_at' => ['required'],
            'tp_email' => ['nullable', 'string', 'max:250'],
            'tp_website' => ['nullable', 'string', 'max:250'],
            'tp_logo' => ['nullable', 'string', 'max:250'],
            'tp_bank' => ['nullable', 'string', 'max:250'],
            'tp_account_number' => ['nullable', 'string', 'max:250'],
            'tp_facebook' => ['nullable', 'string', 'max:250'],
            'tp_twitter' => ['nullable', 'string', 'max:250'],
            'tp_instagram' => ['nullable', 'string', 'max:250'],
            'tp_youtube' => ['nullable', 'string', 'max:250'],
            'tp_whatsapp' => ['nullable', 'string', 'max:250'],
            'tp_address' => ['nullable', 'string', 'max:250'],
        ];
    }
}
