<?php

namespace App\Console\Commands;

use App\Models\Patient;
use Illuminate\Console\Command;

use Illuminate\Database\Eloquent\Factories\Factory;

class TestingAppBudental extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:testing-app-budental';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $this->info('App Budental is running');
        // create 1000000 Patients
        for ($i = 0; $i < 1000000; $i++) {
            $this->info('Patient ' . $i . '/1000000');
            $patient = new Patient();
            $patient->first_name = "Jean " . $i;
            $patient->middle_name = " " . $i;
            $patient->last_name = "Dupont " . $i;
            $patient->birth_date = now()->subYears(rand(4, 60));
            $patient->gender = rand(0, 1) ? 'M' : 'F';
            $patient->phone = "06" . rand(10000000, 99999999);
            $patient->secondary_phone = "06" . rand(10000000, 99999999);
            $patient->email = "patient" . $i . "@gmail.com";
            $patient->address = "123 rue de test";
            $patient->city = "Testville";
            $patient->postal_code = "12345";
            $patient->country = "France";
            $patient->insurance_number = rand(10000000, 99999999);
            $patient->insurance_company = "Assurance Test";
            $patient->insurance_id = 1;
            $patient->medical_history = "Histoire médicale test";
            $patient->allergies = "Allergies test";
            $patient->creator_id = 1;
            $patient->patient_type = 'physique';
            $patient->nif = rand(10000000, 99999999);
            $patient->societe = "Societe Test";
            $patient->save();
        }
        $this->info('App Budental is finished');
    }
}
