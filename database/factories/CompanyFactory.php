<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Company;

class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Company::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'tp_name' => fake()->regexify('[A-Za-z0-9]{250}'),
            'tp_type' => fake()->regexify('[A-Za-z0-9]{250}'),
            'tp_TIN' => fake()->regexify('[A-Za-z0-9]{250}'),
            'tp_trade_number' => fake()->regexify('[A-Za-z0-9]{250}'),
            'tp_postal_number' => fake()->regexify('[A-Za-z0-9]{250}'),
            'tp_phone_number' => fake()->regexify('[A-Za-z0-9]{250}'),
            'tp_address_privonce' => fake()->regexify('[A-Za-z0-9]{250}'),
            'tp_address_avenue' => fake()->regexify('[A-Za-z0-9]{250}'),
            'tp_address_quartier' => fake()->regexify('[A-Za-z0-9]{250}'),
            'tp_address_commune' => fake()->regexify('[A-Za-z0-9]{250}'),
            'tp_address_rue' => fake()->regexify('[A-Za-z0-9]{250}'),
            'tp_address_number' => fake()->regexify('[A-Za-z0-9]{250}'),
            'vat_taxpayer' => fake()->regexify('[A-Za-z0-9]{250}'),
            'ct_taxpayer' => fake()->regexify('[A-Za-z0-9]{250}'),
            'tl_taxpayer' => fake()->regexify('[A-Za-z0-9]{250}'),
            'tp_fiscal_center' => fake()->regexify('[A-Za-z0-9]{250}'),
            'tp_activity_sector' => fake()->regexify('[A-Za-z0-9]{250}'),
            'tp_legal_form' => fake()->regexify('[A-Za-z0-9]{250}'),
            'payment_type' => fake()->regexify('[A-Za-z0-9]{250}'),
            'is_actif' => fake()->boolean(),
            'user_id' => fake()->numberBetween(-10000, 10000),
            'created_at' => fake()->dateTime(),
            'updated_at' => fake()->dateTime(),
            'deleted_at' => fake()->dateTime(),
            'tp_email' => fake()->regexify('[A-Za-z0-9]{250}'),
            'tp_website' => fake()->regexify('[A-Za-z0-9]{250}'),
            'tp_logo' => fake()->regexify('[A-Za-z0-9]{250}'),
            'tp_bank' => fake()->regexify('[A-Za-z0-9]{250}'),
            'tp_account_number' => fake()->regexify('[A-Za-z0-9]{250}'),
            'tp_facebook' => fake()->regexify('[A-Za-z0-9]{250}'),
            'tp_twitter' => fake()->regexify('[A-Za-z0-9]{250}'),
            'tp_instagram' => fake()->regexify('[A-Za-z0-9]{250}'),
            'tp_youtube' => fake()->regexify('[A-Za-z0-9]{250}'),
            'tp_whatsapp' => fake()->regexify('[A-Za-z0-9]{250}'),
            'tp_address' => fake()->regexify('[A-Za-z0-9]{250}'),
        ];
    }
}
