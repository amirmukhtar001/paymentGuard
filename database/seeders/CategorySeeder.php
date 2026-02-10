<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\CategoryType;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // NEWS categories
        $newsType = CategoryType::where('slug', 'news-categories')->first();

        $newsParent = Category::create([
            'category_type_id' => $newsType->id,
            'title' => 'Sports',
            'slug' => 'sports',
            'status' => 'active',
            'sort_order' => 1,
        ]);

        // EVENT categories
        $eventType = CategoryType::where('slug', 'event-categories')->first();

        Category::create([
            'category_type_id' => $eventType->id,
            'title' => 'Workshops',
            'slug' => 'workshops',
            'status' => 'active',
            'sort_order' => 1,
        ]);

        Category::create([
            'category_type_id' => $eventType->id,
            'title' => 'Conferences',
            'slug' => 'conferences',
            'status' => 'active',
            'sort_order' => 2,
        ]);

        // GALLERY categories
        $galleryType = CategoryType::where('slug', 'gallery-categories')->first();

        Category::create([
            'category_type_id' => $galleryType->id,
            'title' => 'Nature',
            'slug' => 'nature',
            'status' => 'active',
            'sort_order' => 1,
        ]);

        // DOWNLOAD categories
        $downloadType = CategoryType::where('slug', 'download-categories')->first();

        Category::create([
            'category_type_id' => $downloadType->id,
            'title' => 'Documents',
            'slug' => 'documents',
            'status' => 'active',
            'sort_order' => 1,
        ]);
    }
}
