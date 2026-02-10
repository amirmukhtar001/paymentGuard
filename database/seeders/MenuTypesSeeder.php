<?php

namespace Database\Seeders;

use App\Models\Web\MenuType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class MenuTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MenuType::insert([
            [
                'name' => 'Header Menu',
                'slug' => 'header_menu',
                'description' => 'Menu displayed in the header section of the website'
            ],
            [
                'name' => 'Footer Menu',
                'slug' => 'footer_menu',
                'description' => 'Menu displayed in the footer section of the website'
            ],
            [
                'name' => 'Left Menu',
                'slug' => 'left_menu',
                'description' => 'Menu displayed in the Left section of the website'
            ],
            [
                'name' => 'Right Menu',
                'slug' => 'right_menu',
                'description' => 'Menu displayed in the Right section of the website'
            ],
        ]);
    }
}
