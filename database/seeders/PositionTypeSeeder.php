<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Web\PositionType;

class PositionTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'Prime Minister',
            'Federal Minister',
            'Minister of State',
            'Advisor to PM',
            'Special Assistant to PM',
            'Senator',
            'Member of National Assembly',
            'Chief Minister',
            'Provincial Minister',
        ];

        foreach ($types as $type) {
            PositionType::firstOrCreate(['name' => $type]);
        }
    }
}
