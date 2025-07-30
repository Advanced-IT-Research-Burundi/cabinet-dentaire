<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\ObrPointer;

class ObrPointerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ObrPointer::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'invoice_id' => fake()->text(),
            'invoice_signature' => fake()->text(),
            'status' => fake()->regexify('[A-Za-z0-9]{20}'),
            'electronic_signature' => fake()->text(),
            'msg' => fake()->text(),
            'result' => fake()->text(),
        ];
    }
}
