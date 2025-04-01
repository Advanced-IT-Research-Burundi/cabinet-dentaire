<?php

namespace Database\Seeders;

use App\Models\UserPatientDentistTreatmentTypePaymentMethod;
use Illuminate\Database\Seeder;

class UserPatientDentistTreatmentTypePaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserPatientDentistTreatmentTypePaymentMethod::factory()->count(5)->create();
    }
}
