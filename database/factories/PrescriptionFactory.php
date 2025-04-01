<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Appointment;
use App\Models\Dentist;
use App\Models\Patient;
use App\Models\Prescription;

class PrescriptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Prescription::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'dentist_id' => Dentist::factory(),
            'appointment_id' => Appointment::factory(),
            'prescription_date' => fake()->date(),
            'content' => fake()->paragraphs(3, true),
            'instructions' => fake()->text(),
            'expiration_date' => fake()->date(),
            'status' => fake()->randomElement(["Active","Expiree","Annulee"]),
        ];
    }
}
