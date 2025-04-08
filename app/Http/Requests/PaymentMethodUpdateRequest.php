<?php

/**
 * Payment Method Update Request
 *
 * @category Requests
 * @package  CabinetDentaire
 * @author   Advanced IT Research Team <contact@advanced-it-research.bi>
 * @version  GIT: 1.0.0
 * @license  MIT License
 * @link     https://github.com/Advanced-IT-Research-Burundi/cabinet-dentaire
 * @php      8.1
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * PaymentMethodUpdateRequest Class
 *
 * @category Requests
 * @package  CabinetDentaire
 * @author   Advanced IT Research Team <contact@advanced-it-research.bi>
 * @license  MIT License
 * @link     https://github.com/Advanced-IT-Research-Burundi/cabinet-dentaire
 */
class PaymentMethodUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool Returns true if authorized
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed> Returns validation rules
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:payment_methods,name,'
                . $this->payment_method->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'requires_confirmation' => 'boolean',
            'confirmation_instructions' => 'nullable|string|'
                . 'required_if:requires_confirmation,true',
        ];
    }
}
