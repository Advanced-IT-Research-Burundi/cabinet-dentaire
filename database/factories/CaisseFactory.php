<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Caisse;
use App\Models\Users,id;

class CaisseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Caisse::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'type' => fake()->regexify('[A-Za-z0-9]{250}'),
            'date' => fake()->dateTime(),
            'montant' => fake()->randomFloat(0, 0, 9999999999.),
            'description' => fake()->text(),
            'status' => fake()->regexify('[A-Za-z0-9]{250}'),
            'user_id' => Users,id::factory(),
        ];
    }
}
