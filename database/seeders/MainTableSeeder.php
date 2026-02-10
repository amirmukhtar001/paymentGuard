<?php

namespace Database\Seeders;

 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class MainTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        Model::unguard();
        $this->call([
            PositionTypeSeeder::class,
            DepartmentSeeder::class,
            PartySeeder::class,
            HalqaSeeder::class,
        ]);

        Model::reguard();
    }
}
