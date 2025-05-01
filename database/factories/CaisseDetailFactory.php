<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\CaisseDetail;
use App\Models\Caisses,id;
use App\Models\Users,id;

class CaisseDetailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CaisseDetail::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'caisse_id' => Caisses,id::factory(),
            'type' => fake()->regexify('[A-Za-z0-9]{250}'),
            'price' => fake()->randomFloat(0, 0, 9999999999.),
            'total' => fake()->randomFloat(0, 0, 9999999999.),
            'status' => fake()->regexify('[A-Za-z0-9]{250}'),
            'user_id' => Users,id::factory(),
        ];
    }
}
