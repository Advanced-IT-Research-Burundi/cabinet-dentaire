<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CaisseDetailUpdateRequest extends FormRequest
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
            'caisse_id' => ['required', 'integer', 'exists:caisses,id,id'],
            'type' => ['nullable', 'string', 'max:250'],
            'price' => ['required', 'numeric'],
            'total' => ['required', 'numeric'],
            'status' => ['nullable', 'string', 'max:250'],
            'user_id' => ['required', 'integer', 'exists:users,id,id'],
        ];
    }
}
