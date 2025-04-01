<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Stock;

class StockFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Stock::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'product_name' => fake()->regexify('[A-Za-z0-9]{255}'),
            'category' => fake()->regexify('[A-Za-z0-9]{100}'),
            'description' => fake()->text(),
            'available_quantity' => fake()->numberBetween(-10000, 10000),
            'unit_measure' => fake()->regexify('[A-Za-z0-9]{50}'),
            'minimum_quantity' => fake()->numberBetween(-10000, 10000),
            'last_order_date' => fake()->date(),
            'purchase_price' => fake()->randomFloat(2, 0, 99999999.99),
            'supplier' => fake()->regexify('[A-Za-z0-9]{255}'),
            'location' => fake()->regexify('[A-Za-z0-9]{100}'),
            'expiration_date' => fake()->date(),
            'status' => fake()->randomElement(["Disponible","Faible_stock","En_rupture","Expire"]),
            'created_at' => fake()->dateTime(),
            'updated_at' => fake()->dateTime(),
        ];
    }
}
