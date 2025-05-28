<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);


        \DB::table('users')->insert([
            'name' => 'budental services',
            'first_name' => 'budental',
            'last_name' => 'services',
            'adresse' => 'Rohero, N°12, Ave d\'Italie',
            'pays' => 'Burnudi',
            'ville' => 'Bujumbura',
            'phone' => '+257 62 10 63 08',
            'secondary_phone' => '+257 62 50 50 00',
            'role' => 'Admin',
            'email' => 'budentalservices@gmail.com',
            'password' => \Hash::make('12345678'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        // Insert a default caisse de l'entreprise

        \DB::table('caisses')->insert([
            'id' => 1,
            'name' => 'Caisse de l\'Entreprise',
            'type' => 'Caisse',
            'date' => now(),
            'montant' => 0,
            'description' => 'Caisse initiale de l\'entreprise',
            'status' => 'active',
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $this->call([
            CompanySeeder::class,
            TraitementSeeder::class,
        ]);
    }
}
