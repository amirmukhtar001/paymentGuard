<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $settings = [
            ['key' => 'site_title', 'value' => config('app.name', 'Payment Guard')],
            ['key' => 'active_layout', 'value' => 'app_screen_frest'],
            ['key' => 'section_title', 'value' => 'Section'],
            ['key' => 'company_title', 'value' => 'Company'],
        ];

        foreach ($settings as $row) {
            DB::table('settings')->updateOrInsert(
                ['key' => $row['key']],
                array_merge($row, ['deleted_at' => null, 'created_at' => $now, 'updated_at' => $now])
            );
        }
    }
}
