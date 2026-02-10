<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategoryType;

class CategoryTypeSeeder extends Seeder
{
    public function run()
    {
        $types = [
            [
                'title' => 'News Categories',
                'slug'  => 'news-categories',
                'status' => 'active',
                'sort_order' => 1,
            ],
            [
                'title' => 'Event Categories',
                'slug'  => 'event-categories',
                'status' => 'active',
                'sort_order' => 2,
            ],
            [
                'title' => 'Gallery Categories',
                'slug'  => 'gallery-categories',
                'status' => 'active',
                'sort_order' => 3,
            ],
            [
                'title' => 'Download Categories',
                'slug'  => 'download-categories',
                'status' => 'inactive',
                'sort_order' => 4,
            ],
            [
                'title' => 'Job',
                'slug'  => 'job',
                'status' => 'active',
                'sort_order' => 5,
            ],
            [
                'title' => 'Service Categories',
                'slug'  => 'service-categories',
                'status' => 'active',
                'sort_order' => 6,
            ],
            [
                'title' => 'FAQ Categories',
                'slug'  => 'faq-categories',
                'status' => 'active',
                'sort_order' => 7,
            ],
        ];

        foreach ($types as $type) {
            CategoryType::create($type);
        }
    }
}
