<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\TreatmentType;

class TreatmentTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TreatmentType::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'description' => fake()->text(),
            'average_duration' => fake()->numberBetween(-10000, 10000),
            'base_price' => fake()->randomFloat(2, 0, 99999999.99),
            'category' => fake()->regexify('[A-Za-z0-9]{100}'),
            'code' => fake()->regexify('[A-Za-z0-9]{20}'),
            'active' => fake()->boolean(),
        ];
    }
}
