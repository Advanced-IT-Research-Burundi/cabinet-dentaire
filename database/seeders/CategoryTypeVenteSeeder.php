<?php

namespace Database\Seeders;

use App\Models\CategoryTypeVente;
use Illuminate\Database\Seeder;

class CategoryTypeVenteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'ACTES', 'description' => 'Actes et soins dentaires'],
            ['name' => 'MEDICAMENTS', 'description' => 'Médicaments et produits pharmaceutiques'],
            ['name' => 'MATERIELS', 'description' => 'Matériels et équipements'],
            ['name' => 'PARAMEDICAUX', 'description' => 'Produits et consommables paramédicaux'],
        ];

        foreach ($categories as $cat) {
            CategoryTypeVente::firstOrCreate(
                ['name' => $cat['name']],
                ['description' => $cat['description']]
            );
        }
    }
}
