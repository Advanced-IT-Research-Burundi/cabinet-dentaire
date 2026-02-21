<?php

/**
 * Treatment Store Request
 *
 * Validates treatment creation requests.
 *
 * @category Requests
 * @package  CabinetDentaire
 * @author   Advanced IT Research Team <contact@advanced-it-research.bi>
 * @license  MIT License
 * @link     https://github.com/Advanced-IT-Research-Burundi/cabinet-dentaire
 * @version  GIT: 1.0.0
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * TreatmentStoreRequest Class
 *
 * @category Requests
 * @package  CabinetDentaire
 * @author   Advanced IT Research Team <contact@advanced-it-research.bi>
 * @license  MIT License
 * @link     https://github.com/Advanced-IT-Research-Burundi/cabinet-dentaire
 */
class TreatmentStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool True if authorized
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<string>> Validation rules
     */
    public function rules(): array
    {
        return [
            'patient_id' => [
                'required',
                'integer',
                'exists:patients,id'
            ],
            'dentist_id' => [
                'required',
                'integer',
                'exists:users,id'
            ],
            // 'treatment_type_id' => [
            //     'required',
            //     'integer',
            //     'exists:treatment_types,id'
            // ],
            'appointment_id' => [
                'required',
                'integer',
                'exists:appointments,id'
            ],
            'date' => ['required', 'date'],
            'description' => ['nullable', 'string'],
            'medical_notes' => ['nullable', 'string'],
            'applied_price' => [
                'nullable',
                'numeric',
                'between:-99999999.99,99999999.99'
            ],
            'status' => [
                'required',
                'in:Planifie,En_cours,Termine,Annule'
            ],
            'treatments_data' => ['required', 'string'],
        ];
    }
}
