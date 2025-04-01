<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Treatment;

class InvoiceDetailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InvoiceDetail::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'invoice_id' => Invoice::factory(),
            'treatment_id' => Treatment::factory(),
            'description' => fake()->text(),
            'quantity' => fake()->numberBetween(-10000, 10000),
            'unit_price' => fake()->randomFloat(2, 0, 99999999.99),
            'total_amount' => fake()->randomFloat(2, 0, 99999999.99),
            'tax_rate' => fake()->randomFloat(2, 0, 999.99),
            'tax_amount' => fake()->randomFloat(2, 0, 99999999.99),
            'discount_percentage' => fake()->randomFloat(2, 0, 999.99),
            'discount_amount' => fake()->randomFloat(2, 0, 99999999.99),
            'insurance_covered_amount' => fake()->randomFloat(2, 0, 99999999.99),
            'patient_amount' => fake()->randomFloat(2, 0, 99999999.99),
        ];
    }
}
