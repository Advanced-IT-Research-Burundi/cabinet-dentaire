<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Notification;
use App\Models\Recipient;

class NotificationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Notification::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'message' => fake()->text(),
            'type' => fake()->randomElement(["Rappel_RDV","Facture","Paiement","Stock","Systeme"]),
            'recipient_id' => Recipient::factory(),
            'recipient_type' => fake()->randomElement(["Patient","Dentiste","Utilisateur"]),
            'link' => fake()->regexify('[A-Za-z0-9]{255}'),
            'status' => fake()->randomElement(["Non_envoye","Envoye","Lu"]),
            'sent_date' => fake()->dateTime(),
            'read_date' => fake()->dateTime(),
            'send_method' => fake()->randomElement(["Email","SMS","Application","Tous"]),
        ];
    }
}
