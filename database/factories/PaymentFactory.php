<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\User;

class PaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'invoice_id' => Invoice::factory(),
            'patient_id' => Patient::factory(),
            'payment_method_id' => PaymentMethod::factory(),
            'transaction_number' => fake()->regexify('[A-Za-z0-9]{100}'),
            'amount' => fake()->randomFloat(2, 0, 99999999.99),
            'payment_date' => fake()->dateTime(),
            'status' => fake()->randomElement(["Valide","En_attente","Rejete","Rembourse"]),
            'notes' => fake()->text(),
            'proof_of_payment' => fake()->regexify('[A-Za-z0-9]{255}'),
            'operator_id' => User::factory(),
            'created_at' => fake()->dateTime(),
        ];
    }
}
