<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ObrPointerStoreRequest extends FormRequest
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
            'invoice_id' => ['nullable', 'string'],
            'invoice_signature' => ['nullable', 'string'],
            'status' => ['nullable', 'string', 'max:20'],
            'electronic_signature' => ['nullable', 'string'],
            'msg' => ['nullable', 'string'],
            'result' => ['nullable', 'string'],
        ];
    }
}
