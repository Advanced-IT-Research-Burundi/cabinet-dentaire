<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
    }
}
