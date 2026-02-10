<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Web\Designation;
use App\Enums\StatusEnum;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $designations = [
            [
                'title'       => 'Chief Secretary',
                'description' => 'Head of the organization',
                'sort_order'  => 1,
            ],
            [
                'title'       => 'Additional Chief Secretary',
                'description' => 'Responsible for technology strategy',
                'sort_order'  => 2,
            ],
            [
                'title'       => 'Director',
                'description' => 'Manages projects and teams',
                'sort_order'  => 3,
            ],
            [
                'title'       => 'Deputy Director',
                'description' => 'Leads development tasks',
                'sort_order'  => 4,
            ],
            [
                'title'       => 'Managing Director',
                'description' => 'Assists in software development',
                'sort_order'  => 5,
            ],

            [
                'title'       => 'Director General',
                'description' => 'Assists in software development',
                'sort_order'  => 6,
            ],

            [
                'title'       => 'Assistant Director',
                'description' => 'Assists in software development',
                'sort_order'  => 7,
            ]

        ];

        foreach ($designations as $item) {
            Designation::updateOrCreate(
                ['slug' => Str::slug($item['title'])], // prevent duplicates
                [
                    'uuid'        => (string) Str::uuid(),
                    'title'       => $item['title'],
                    'description' => $item['description'],
                    'sort_order'  => $item['sort_order'],
                    'status'      => StatusEnum::ACTIVE,
                ]
            );
        }
    }
}
