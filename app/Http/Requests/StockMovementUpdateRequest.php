<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockMovementUpdateRequest extends FormRequest
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
            'stock_id' => ['required', 'integer', 'exists:stocks,id,id'],
            'type' => ['required', 'string', 'max:250'],
            'date' => ['required'],
            'quantity' => ['required', 'numeric'],
            'price' => ['required', 'numeric'],
            'description' => ['nullable', 'string', 'max:250'],
            'status' => ['nullable', 'string', 'max:250'],
            'is_syncronized' => ['nullable'],
        ];
    }
}
