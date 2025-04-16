<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\StockMovement;
use App\Models\Stocks,id;

class StockMovementFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StockMovement::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'stock_id' => Stocks,id::factory(),
            'type' => fake()->regexify('[A-Za-z0-9]{250}'),
            'date' => fake()->dateTime(),
            'quantity' => fake()->randomFloat(0, 0, 9999999999.),
            'price' => fake()->randomFloat(0, 0, 9999999999.),
            'description' => fake()->text(),
            'status' => fake()->regexify('[A-Za-z0-9]{250}'),
            'is_syncronized' => fake()->boolean(),
        ];
    }
}
