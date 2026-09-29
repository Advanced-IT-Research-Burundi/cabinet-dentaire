<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryTypeVenteRequest extends FormRequest
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
        $categoryTypeVente = $this->route('category_type_vente');
        $id = is_object($categoryTypeVente) ? $categoryTypeVente->id : $categoryTypeVente;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('category_type_ventes', 'name')->ignore($id),
            ],
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
