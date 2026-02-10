<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmailSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('email_settings')->insert([
            ['event_key'=>'user_created',      'enabled'=>1,'to_admin'=>1,'to_user'=>1,'cc_emails'=>null,'created_at'=>now(),'updated_at'=>now()],
            ['event_key'=>'farmer_registered', 'enabled'=>1,'to_admin'=>1,'to_user'=>0,'cc_emails'=>'ops@yourapp.com','created_at'=>now(),'updated_at'=>now()],
        ]);
    }
}
