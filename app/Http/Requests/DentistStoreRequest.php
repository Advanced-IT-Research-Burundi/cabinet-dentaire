<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DentistStoreRequest extends FormRequest
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
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'specialty' => ['nullable', 'string', 'max:100'],
            'license_number' => ['nullable', 'string', 'max:50'],
            'biography' => ['nullable', 'string'],
            'calendar_color' => ['nullable', 'string', 'max:7'],
            'available' => ['required'],
        ];
    }
}
