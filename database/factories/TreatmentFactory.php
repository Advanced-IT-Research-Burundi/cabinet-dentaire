<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Appointment;
use App\Models\Dentist;
use App\Models\Patient;
use App\Models\Treatment;
use App\Models\TreatmentType;

class TreatmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Treatment::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'dentist_id' => Dentist::factory(),
            'treatment_type_id' => TreatmentType::factory(),
            'appointment_id' => Appointment::factory(),
            'date' => fake()->date(),
            'description' => fake()->text(),
            'medical_notes' => fake()->text(),
            'applied_price' => fake()->randomFloat(2, 0, 99999999.99),
            'status' => fake()->randomElement(["Planifie","En_cours","Termine","Annule"]),
            'created_at' => fake()->dateTime(),
            'updated_at' => fake()->dateTime(),
        ];
    }
}
