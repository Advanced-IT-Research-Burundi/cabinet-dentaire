<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Dentist;
use App\Models\User;

class DentistFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Dentist::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'specialty' => fake()->regexify('[A-Za-z0-9]{100}'),
            'license_number' => fake()->regexify('[A-Za-z0-9]{50}'),
            'biography' => fake()->text(),
            'calendar_color' => fake()->regexify('[A-Za-z0-9]{7}'),
            'available' => fake()->boolean(),
        ];
    }
}
