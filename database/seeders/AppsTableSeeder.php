<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('apps')->insert([
            [
                'id' => 1,
                'title' => 'Settings',
                'route' => 'settings',
                'app_form' => null,
                'description' => null,
                'icon' => 'bx bx-wrench',
                'sdp' => 0,
                'app_type' => 'CORE_APP',
                'active' => 1,
                'extra_fields' => null,
                'created_at' => '2022-05-16 17:16:02',
                'updated_at' => '2022-10-05 22:23:28',
                'deleted_at' => null,
            ]
        ]);
    }
}
