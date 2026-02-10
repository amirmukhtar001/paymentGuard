<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistrictsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('districts')->insert([
            [
                'id' => 1,
                'province_id' => 1,
                'division_id' => 7,
                'title' => 'Peshawar',
                'ur_title' => 'پشاور',
                'short_title' => 'pes',
                'latitude' => '34.014975',
                'longitude' => '71.580490',
                'description' => null,
                'active' => 1,
                'district_group_id' => 1,
                'deleted_at' => null,
                'created_at' => '2017-02-08 18:09:00',
                'updated_at' => null,
                'check' => 0
            ],
            [
                'id' => 2,
                'province_id' => 1,
                'division_id' => 6,
                'title' => 'Mardan',
                'ur_title' => 'مردان',
                'short_title' => 'mar',
                'latitude' => '34.200114',
                'longitude' => '72.050801',
                'description' => '',
                'active' => 1,
                'district_group_id' => 1,
                'deleted_at' => null,
                'created_at' => '2017-02-08 18:09:00',
                'updated_at' => null,
                'check' => 0
            ],
            [
                'id' => 3,
                'province_id' => 1,
                'division_id' => 7,
                'title' => 'Charsadda',
                'ur_title' => 'چارسدہ',
                'short_title' => 'cha',
                'latitude' => '34.149433',
                'longitude' => '71.742781',
                'description' => '',
                'active' => 1,
                'district_group_id' => 2,
                'deleted_at' => null,
                'created_at' => '2017-02-08 18:09:00',
                'updated_at' => null,
                'check' => 0
            ],
            [
                'id' => 4,
                'province_id' => 1,
                'division_id' => 1,
                'title' => 'Bannu',
                'ur_title' => 'بنوں',
                'short_title' => 'ban',
                'latitude' => '32.989724',
                'longitude' => '70.603833',
                'description' => '',
                'active' => 1,
                'district_group_id' => 1,
                'deleted_at' => null,
                'created_at' => '2017-02-08 18:09:00',
                'updated_at' => null,
                'check' => 0
            ],
            [
                'id' => 5,
                'province_id' => 1,
                'division_id' => 2,
                'title' => 'Dera Ismail Khan',
                'ur_title' => 'ڈیرہ اسماعیل خان',
                'short_title' => 'dik',
                'latitude' => '31.842362',
                'longitude' => '70.895234',
                'description' => '',
                'active' => 1,
                'district_group_id' => 1,
                'deleted_at' => null,
                'created_at' => '2017-02-08 18:09:00',
                'updated_at' => null,
                'check' => 0
            ],
            // Add more records as needed
        ]);
    }
}
