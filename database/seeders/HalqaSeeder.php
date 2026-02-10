<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Web\Halqa;

class HalqaSeeder extends Seeder
{
    public function run(): void
    {
        $halqas = [
            ['code' => 'NA-1',  'name' => 'National Assembly Constituency NA-1'],
            ['code' => 'NA-55', 'name' => 'National Assembly Constituency NA-55'],
            ['code' => 'NA-75', 'name' => 'National Assembly Constituency NA-75'],

            // Examples of provincial seats
            ['code' => 'PP-12', 'name' => 'Punjab Provincial Assembly PP-12'],
            ['code' => 'PS-101', 'name' => 'Sindh Provincial Assembly PS-101'],
            ['code' => 'PK-10', 'name' => 'KP Provincial Assembly PK-10'],
            ['code' => 'PB-25', 'name' => 'Balochistan Provincial Assembly PB-25'],
        ];

        foreach ($halqas as $h) {
            Halqa::firstOrCreate(['code' => $h['code']], $h);
        }
    }
}
