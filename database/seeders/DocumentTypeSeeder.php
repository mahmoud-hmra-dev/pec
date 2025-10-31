<?php

namespace Database\Seeders;

use App\Models\DocumentType;
use App\Models\Hospital;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DocumentType::create([
            'name' => "MOU",
            'code' => "MOU",
        ]);
    }
}
