<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Web\Department;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            'Ministry of Interior',
            'Ministry of Finance',
            'Ministry of Foreign Affairs',
            'Ministry of Education',
            'Ministry of Defence',
            'Ministry of Law & Justice',
            'Ministry of Information & Broadcasting',
            'Ministry of Communications',
            'Ministry of Health Services',
            'Ministry of Science & Technology',
        ];

        foreach ($departments as $name) {
            Department::firstOrCreate(['name' => $name]);
        }
    }
}
