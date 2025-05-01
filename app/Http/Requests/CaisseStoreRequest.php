<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CaisseStoreRequest extends FormRequest
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
            'type' => ['nullable', 'string', 'max:250'],
            'date' => ['required'],
            'montant' => ['required', 'numeric'],
            'description' => ['nullable', 'string', 'max:250'],
            'status' => ['nullable', 'string', 'max:250'],
            'user_id' => ['required', 'integer', 'exists:users,id,id'],
        ];
    }
}
