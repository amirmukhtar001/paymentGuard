<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Web\WebsiteMenu;
use Illuminate\Support\Str;

class WebsiteMenusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WebsiteMenu::insert([
            [
                'uuid' =>  (string) Str::uuid(),
                'company_id' => 1,
                'menu_type_id' => 1,
                'title' => 'Main Portal',
                'status' => 'active',
                'created_at' => '2025-11-26 10:08:28',
                'updated_at' => '2025-11-26 10:08:28',
                'deleted_at' => null,
            ],
             [
                'uuid' =>  (string) Str::uuid(),
                'company_id' => 1,
                'menu_type_id' =>2,
                'title' => 'Main Portal Footer',
                'status' => 'active',
                'created_at' => '2025-11-26 10:08:28',
                'updated_at' => '2025-11-26 10:08:28',
                'deleted_at' => null,
            ],
            [
                'uuid' => (string) Str::uuid(),
                'company_id' => 2,
                'menu_type_id' => 1,
                'title' => 'Food Department Menu',
                'status' => 'active',
                'created_at' => '2025-11-26 10:10:00',
                'updated_at' => '2025-11-26 10:10:00',
                'deleted_at' => null,
            ]
        ]);
    }
}
