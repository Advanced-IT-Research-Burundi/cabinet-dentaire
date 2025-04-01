<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Dentist;
use App\Models\MedicalHistory;
use App\Models\Patient;

class MedicalHistoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MedicalHistory::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'date' => fake()->date(),
            'document_type' => fake()->randomElement(["Radiographie","Ordonnance","Analyse","Rapport","Autre"]),
            'title' => fake()->sentence(4),
            'details' => fake()->text(),
            'file' => fake()->regexify('[A-Za-z0-9]{255}'),
            'dentist_id' => Dentist::factory(),
            'created_at' => fake()->dateTime(),
        ];
    }
}
