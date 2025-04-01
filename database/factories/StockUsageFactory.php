<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Stock;
use App\Models\StockUsage;
use App\Models\Treatment;
use App\Models\User;

class StockUsageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StockUsage::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'stock_id' => Stock::factory(),
            'treatment_id' => Treatment::factory(),
            'quantity_used' => fake()->randomFloat(2, 0, 99999999.99),
            'usage_date' => fake()->dateTime(),
            'user_id' => User::factory(),
        ];
    }
}
