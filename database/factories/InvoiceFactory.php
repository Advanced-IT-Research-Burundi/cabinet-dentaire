<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\User;

class InvoiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Invoice::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'invoice_number' => fake()->regexify('[A-Za-z0-9]{50}'),
            'issue_date' => fake()->date(),
            'due_date' => fake()->date(),
            'total_amount' => fake()->randomFloat(2, 0, 99999999.99),
            'insurance_amount' => fake()->randomFloat(2, 0, 99999999.99),
            'patient_amount' => fake()->randomFloat(2, 0, 99999999.99),
            'status' => fake()->randomElement(["Brouillon","Emise","Partiellement_payee","Payee","Annulee","En_retard"]),
            'notes' => fake()->text(),
            'creator_id' => User::factory(),
            'created_at' => fake()->dateTime(),
            'updated_at' => fake()->dateTime(),
        ];
    }
}
