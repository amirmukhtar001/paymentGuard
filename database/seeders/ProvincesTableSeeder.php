<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvincesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('provinces')->insert([
            [
                'id' => 1,
                'country_id' => 1,
                'title' => 'Khyber Pakhtunkhwa',
                'abbreviation' => 'KP',
                'description' => null,
                'active' => 1,
                'created_at' => '2015-02-06 06:44:31',
                'updated_at' => '2015-02-06 00:00:00',
                'image_path' => 'https://api.pmdu.gov.pk/assets/provinces/test1-05.png',
                'govt_name' => 'Govt. Of Khyber Pakhtunkhwa / حکومتِ خیبر پختونخوا',
            ],
            [
                'id' => 2,
                'country_id' => 1,
                'title' => 'Punjab',
                'abbreviation' => 'PU',
                'description' => '',
                'active' => 1,
                'created_at' => '2015-02-06 09:43:48',
                'updated_at' => '2015-02-06 19:43:48',
                'image_path' => 'https://api.pmdu.gov.pk/assets/provinces/test1-05.png',
                'govt_name' => 'Govt. Of Punjab / حکومتِ پنجاب',
            ],
            [
                'id' => 3,
                'country_id' => 1,
                'title' => 'Sindh',
                'abbreviation' => 'SD',
                'description' => '',
                'active' => 1,
                'created_at' => '2015-02-06 09:44:01',
                'updated_at' => '2015-02-06 19:44:01',
                'image_path' => 'https://api.pmdu.gov.pk/assets/provinces/test1-05.png',
                'govt_name' => 'Govt. Of Sindh / حکومت سندھ',
            ],
            [
                'id' => 4,
                'country_id' => 1,
                'title' => 'Balochistan',
                'abbreviation' => 'BL',
                'description' => '',
                'active' => 1,
                'created_at' => '2015-02-06 09:44:11',
                'updated_at' => '2015-02-06 19:44:11',
                'image_path' => 'https://api.pmdu.gov.pk/assets/provinces/test1-05.png',
                'govt_name' => 'Govt. Of Baluchistan / حکومتِ بلوچستان',
            ],
            [
                'id' => 6,
                'country_id' => 1,
                'title' => 'Federal Govt',
                'abbreviation' => 'IS',
                'description' => '',
                'active' => 1,
                'created_at' => '2015-05-03 08:03:14',
                'updated_at' => null,
                'image_path' => 'https://api.pmdu.gov.pk/assets/provinces/test1-05.png',
                'govt_name' => 'Federal Govt. / وفاقی حکومت',
            ],
            [
                'id' => 7,
                'country_id' => 1,
                'title' => 'Gilgit-Baltistan',
                'abbreviation' => 'GB',
                'description' => '',
                'active' => 1,
                'created_at' => '2015-05-11 01:09:18',
                'updated_at' => null,
                'image_path' => 'https://api.pmdu.gov.pk/assets/provinces/test1-05.png',
                'govt_name' => 'Govt. Of Gilgit Baltistan / حکومتِ گلگت بلتستان',
            ],
            [
                'id' => 8,
                'country_id' => 1,
                'title' => 'Azad Jammu and Kashmir',
                'abbreviation' => 'AK',
                'description' => '',
                'active' => 1,
                'created_at' => '2015-05-12 02:42:39',
                'updated_at' => null,
                'image_path' => 'https://api.pmdu.gov.pk/assets/provinces/test1-05.png',
                'govt_name' => 'Govt. Of Azad Kashmir / حکومت آزاد کشمیر',
            ],
        ]);
    }
}
