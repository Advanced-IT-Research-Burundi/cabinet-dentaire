<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Appointment;
use App\Models\Dentist;
use App\Models\Patient;
use App\Models\TreatmentType;
use App\Models\User;

class AppointmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Appointment::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'dentist_id' => Dentist::factory(),
            'date' => fake()->date(),
            'start_time' => fake()->time(),
            'end_time' => fake()->time(),
            'reason' => fake()->text(),
            'status' => fake()->randomElement(["Confirme","Annule","Termine","En_attente","Reporte"]),
            'notes' => fake()->text(),
            'reminder_sent' => fake()->boolean(),
            'created_at' => fake()->dateTime(),
            'creator_id' => User::factory(),
            'planned_treatment_id' => TreatmentType::factory(),
        ];
    }
}
