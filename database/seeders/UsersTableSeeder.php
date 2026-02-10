<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        DB::table('users')->updateOrInsert(
            ['id' => 1],
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'email_verified_at' => null,
                'verified_by' => null,
                'verified_at' => null,
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'username' => 'admin',
                'company_id' => 1,
                'section_id' => null,
                'parent_id' => null,
                'status' => 1,
                'description' => null,
                'contact_number' => null,
                'is_otp_enabled' => 0,
                'pincode' => null,
                'is_pincode_enabled' => 0,
                'otp' => null,
                'otp_time' => null,
                'last_login' => null,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ]
        );
    }
}
