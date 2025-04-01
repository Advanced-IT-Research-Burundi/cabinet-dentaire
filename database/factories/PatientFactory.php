<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Patient;
use App\Models\User;

class PatientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Patient::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'middle_name' => fake()->regexify('[A-Za-z0-9]{100}'),
            'last_name' => fake()->lastName(),
            'birth_date' => fake()->date(),
            'gender' => fake()->randomElement(["M","F","Autre"]),
            'phone' => fake()->phoneNumber(),
            'secondary_phone' => fake()->regexify('[A-Za-z0-9]{20}'),
            'email' => fake()->safeEmail(),
            'address' => fake()->text(),
            'city' => fake()->city(),
            'postal_code' => fake()->postcode(),
            'country' => fake()->country(),
            'insurance_number' => fake()->regexify('[A-Za-z0-9]{100}'),
            'insurance_company' => fake()->regexify('[A-Za-z0-9]{100}'),
            'medical_history' => fake()->text(),
            'allergies' => fake()->text(),
            'creator_id' => User::factory(),
        ];
    }
}
