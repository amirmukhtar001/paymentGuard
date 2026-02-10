<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionRoutesMediaGallarySlidersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $routes = [
            // Galleries
            [
                'title' => 'All Galleries',
                'route' => 'settings.galleries.list',
                'permission_id' => 657,
                'menu_id' => 342,
                'is_default' => 'yes',
            ],
            [
                'title' => 'Galleries Datatable',
                'route' => 'settings.galleries.datatable',
                'permission_id' => 657,
                'menu_id' => 342,
                'is_default' => 'no',
            ],
            [
                'title' => 'Create Gallery',
                'route' => 'settings.galleries.create',
                'permission_id' => 658,
                'menu_id' => 342,
                'is_default' => 'yes',
            ],
            [
                'title' => 'Store Gallery',
                'route' => 'settings.galleries.store',
                'permission_id' => 658,
                'menu_id' => 342,
                'is_default' => 'no',
            ],
            [
                'title' => 'Edit Gallery',
                'route' => 'settings.galleries.edit',
                'permission_id' => 659,
                'menu_id' => 342,
                'is_default' => 'yes',
            ],
            [
                'title' => 'Update Gallery',
                'route' => 'settings.galleries.update',
                'permission_id' => 659,
                'menu_id' => 342,
                'is_default' => 'no',
            ],
            [
                'title' => 'Delete Gallery',
                'route' => 'settings.galleries.destroy',
                'permission_id' => 660,
                'menu_id' => 342,
                'is_default' => 'yes',
            ],
            [
                'title' => 'Show Gallery',
                'route' => 'settings.galleries.show',
                'permission_id' => 661,
                'menu_id' => 342,
                'is_default' => 'yes',
            ],

            // Media
            [
                'title' => 'All Media',
                'route' => 'settings.media.list',
                'permission_id' => 662,
                'menu_id' => 343,
                'is_default' => 'yes',
            ],
            [
                'title' => 'Media Datatable',
                'route' => 'settings.media.datatable',
                'permission_id' => 662,
                'menu_id' => 343,
                'is_default' => 'no',
            ],
            [
                'title' => 'Create Media',
                'route' => 'settings.media.create',
                'permission_id' => 663,
                'menu_id' => 343,
                'is_default' => 'yes',
            ],
            [
                'title' => 'Store Media',
                'route' => 'settings.media.store',
                'permission_id' => 663,
                'menu_id' => 343,
                'is_default' => 'no',
            ],
            [
                'title' => 'Edit Media',
                'route' => 'settings.media.edit',
                'permission_id' => 664,
                'menu_id' => 343,
                'is_default' => 'yes',
            ],
            [
                'title' => 'Update Media',
                'route' => 'settings.media.update',
                'permission_id' => 664,
                'menu_id' => 343,
                'is_default' => 'no',
            ],
            [
                'title' => 'Delete Media',
                'route' => 'settings.media.destroy',
                'permission_id' => 665,
                'menu_id' => 343,
                'is_default' => 'yes',
            ],
            [
                'title' => 'Show Media',
                'route' => 'settings.media.show',
                'permission_id' => 666,
                'menu_id' => 343,
                'is_default' => 'yes',
            ],

            // Sliders
            [
                'title' => 'All Sliders',
                'route' => 'settings.sliders.list',
                'permission_id' => 667,
                'menu_id' => 344,
                'is_default' => 'yes',
            ],
            [
                'title' => 'Sliders Datatable',
                'route' => 'settings.sliders.datatable',
                'permission_id' => 667,
                'menu_id' => 344,
                'is_default' => 'no',
            ],
            [
                'title' => 'Create Slider',
                'route' => 'settings.sliders.create',
                'permission_id' => 668,
                'menu_id' => 344,
                'is_default' => 'yes',
            ],
            [
                'title' => 'Store Slider',
                'route' => 'settings.sliders.store',
                'permission_id' => 668,
                'menu_id' => 344,
                'is_default' => 'no',
            ],
            [
                'title' => 'Edit Slider',
                'route' => 'settings.sliders.edit',
                'permission_id' => 669,
                'menu_id' => 344,
                'is_default' => 'yes',
            ],
            [
                'title' => 'Update Slider',
                'route' => 'settings.sliders.update',
                'permission_id' => 669,
                'menu_id' => 344,
                'is_default' => 'no',
            ],
            [
                'title' => 'Delete Slider',
                'route' => 'settings.sliders.destroy',
                'permission_id' => 670,
                'menu_id' => 344,
                'is_default' => 'yes',
            ],
            [
                'title' => 'Show Slider',
                'route' => 'settings.sliders.show',
                'permission_id' => 671,
                'menu_id' => 344,
                'is_default' => 'yes',
            ],

            // Slider Slides
            [
                'title' => 'All Slider Slides',
                'route' => 'settings.slider_slides.list',
                'permission_id' => 672,
                'menu_id' => 345,
                'is_default' => 'yes',
            ],
            [
                'title' => 'Slider Slides Datatable',
                'route' => 'settings.slider_slides.datatable',
                'permission_id' => 672,
                'menu_id' => 345,
                'is_default' => 'no',
            ],
            [
                'title' => 'Create Slider Slide',
                'route' => 'settings.slider_slides.create',
                'permission_id' => 673,
                'menu_id' => 345,
                'is_default' => 'yes',
            ],
            [
                'title' => 'Store Slider Slide',
                'route' => 'settings.slider_slides.store',
                'permission_id' => 673,
                'menu_id' => 345,
                'is_default' => 'no',
            ],
            [
                'title' => 'Edit Slider Slide',
                'route' => 'settings.slider_slides.edit',
                'permission_id' => 674,
                'menu_id' => 345,
                'is_default' => 'yes',
            ],
            [
                'title' => 'Update Slider Slide',
                'route' => 'settings.slider_slides.update',
                'permission_id' => 674,
                'menu_id' => 345,
                'is_default' => 'no',
            ],
            [
                'title' => 'Delete Slider Slide',
                'route' => 'settings.slider_slides.destroy',
                'permission_id' => 675,
                'menu_id' => 345,
                'is_default' => 'yes',
            ],
            [
                'title' => 'Show Slider Slide',
                'route' => 'settings.slider_slides.show',
                'permission_id' => 676,
                'menu_id' => 345,
                'is_default' => 'yes',
            ],
            // Website Sections
            [
                'title' => 'Website Sections List',
                'route' => 'settings.website-sections.list',
                'permission_id' => 690,
                'menu_id' => 346,
                'is_default' => 'yes',
            ],
            [
                'title' => 'Website Sections Datatable',
                'route' => 'settings.website-sections.datatable',
                'permission_id' => 690,
                'menu_id' => 346,
                'is_default' => 'no',
            ],
            [
                'title' => 'Website Sections Orderable',
                'route' => 'settings.website-sections.orderable',
                'permission_id' => 690,
                'menu_id' => 346,
                'is_default' => 'no',
            ],
            [
                'title' => 'Website Sections Update Sort Order',
                'route' => 'settings.website-sections.sort-order',
                'permission_id' => 695,
                'menu_id' => 346,
                'is_default' => 'yes',
            ],
            [
                'title' => 'Website Sections Create',
                'route' => 'settings.website-sections.create',
                'permission_id' => 691,
                'menu_id' => 346,
                'is_default' => 'yes',
            ],
            [
                'title' => 'Website Sections Store',
                'route' => 'settings.website-sections.store',
                'permission_id' => 691,
                'menu_id' => 346,
                'is_default' => 'no',
            ],
            [
                'title' => 'Website Sections Edit',
                'route' => 'settings.website-sections.edit',
                'permission_id' => 692,
                'menu_id' => 346,
                'is_default' => 'yes',
            ],
            [
                'title' => 'Website Sections Update',
                'route' => 'settings.website-sections.update',
                'permission_id' => 692,
                'menu_id' => 346,
                'is_default' => 'no',
            ],
            [
                'title' => 'Website Sections Destroy',
                'route' => 'settings.website-sections.destroy',
                'permission_id' => 693,
                'menu_id' => 346,
                'is_default' => 'yes',
            ],
            [
                'title' => 'Website Sections Show',
                'route' => 'settings.website-sections.show',
                'permission_id' => 694,
                'menu_id' => 346,
                'is_default' => 'yes',
            ],
        ];

        foreach ($routes as $route) {
            DB::table('permission_routes')->updateOrInsert(
                ['route' => $route['route']],
                array_merge(
                    $route,
                    [
                        'created_at' => $route['created_at'] ?? now(),
                        'updated_at' => $route['updated_at'] ?? null,
                        'deleted_at' => $route['deleted_at'] ?? null,
                        'description' => $route['description'] ?? null,
                    ]
                )
            );
        }
    }
}
