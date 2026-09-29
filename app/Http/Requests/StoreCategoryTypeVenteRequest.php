<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryTypeVenteRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:category_type_ventes,name',
            'description' => 'nullable|string',
        ];
    }

    /**
     * Custom error messages in French.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Le nom du type de vente est obligatoire.',
            'name.unique' => 'Ce nom de type de vente existe déjà.',
            'name.max' => 'Le nom ne doit pas dépasser 255 caractères.',
        ];
    }
}
