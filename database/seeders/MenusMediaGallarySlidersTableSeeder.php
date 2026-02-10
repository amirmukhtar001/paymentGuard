<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenusMediaGallarySlidersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            [
                'id'            => 342,
                'parent_id'     => null,
                'title'         => 'Galleries',
                'description'   => 'Manage media galleries',
                'icon'          => 'bx bx-images',
                'order'         => 20,
                'is_collapsible'=> 'yes',
                'created_at'    => now(),
                'updated_at'    => now(),
                'deleted_at'    => null,
            ],
            [
                'id'            => 343,
                'parent_id'     => null,
                'title'         => 'Media',
                'description'   => 'Manage standalone media assets',
                'icon'          => 'bx bx-photo-album',
                'order'         => 21,
                'is_collapsible'=> 'yes',
                'created_at'    => now(),
                'updated_at'    => now(),
                'deleted_at'    => null,
            ],
            [
                'id'            => 344,
                'parent_id'     => null,
                'title'         => 'Sliders',
                'description'   => 'Manage homepage sliders',
                'icon'          => 'bx bx-slideshow',
                'order'         => 22,
                'is_collapsible'=> 'yes',
                'created_at'    => now(),
                'updated_at'    => now(),
                'deleted_at'    => null,
            ],
            [
                'id'            => 345,
                'parent_id'     => null,
                'title'         => 'Slider Slides',
                'description'   => 'Manage slider slides',
                'icon'          => 'bx bx-slider',
                'order'         => 23,
                'is_collapsible'=> 'yes',
                'created_at'    => now(),
                'updated_at'    => now(),
                'deleted_at'    => null,
            ],
            [
                'id'            => 346,
                'parent_id'     => null,
                'title'         => 'Website Sections',
                'description'   => 'Manage frontend sections',
                'icon'          => 'bx bx-layout',
                'order'         => 24,
                'is_collapsible'=> 'yes',
                'created_at'    => now(),
                'updated_at'    => now(),
                'deleted_at'    => null,
            ],
        ];

        foreach ($menus as $menu) {
            DB::table('menus')->updateOrInsert(
                ['id' => $menu['id']],
                $menu
            );
        }
    }
}
