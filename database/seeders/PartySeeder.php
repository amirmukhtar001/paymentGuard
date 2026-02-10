<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Web\Party;

class PartySeeder extends Seeder
{
    public function run(): void
    {
        $parties = [
            ['short_name' => 'PML-N', 'full_name' => 'Pakistan Muslim League (Nawaz)'],
            ['short_name' => 'PPP', 'full_name' => 'Pakistan Peoples Party'],
            ['short_name' => 'PTI', 'full_name' => 'Pakistan Tehreek-e-Insaf'],
            ['short_name' => 'MQM', 'full_name' => 'Muttahida Qaumi Movement'],
            ['short_name' => 'JUI-F', 'full_name' => 'Jamiat Ulema-e-Islam (F)'],
            ['short_name' => 'PML-Q', 'full_name' => 'Pakistan Muslim League (Q)'],
            ['short_name' => 'ANP', 'full_name' => 'Awami National Party'],
            ['short_name' => 'BNP', 'full_name' => 'Balochistan National Party'],
        ];

        foreach ($parties as $party) {
            Party::firstOrCreate(['short_name' => $party['short_name']], $party);
        }
    }
}
