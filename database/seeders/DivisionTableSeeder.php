<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DivisionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('divisions')->insert([
            ['id' => 1, 'province_id' => 1, 'title' => 'Bannu', 'short_title' => 'ban', 'description' => null, 'active' => 1, 'created_at' => '2016-05-03 07:36:39', 'updated_at' => null, 'deleted_at' => null],
            ['id' => 2, 'province_id' => 1, 'title' => 'Dera Ismail Khan', 'short_title' => 'dik', 'description' => null, 'active' => 1, 'created_at' => '2016-05-03 07:36:58', 'updated_at' => null, 'deleted_at' => null],
            ['id' => 3, 'province_id' => 1, 'title' => 'Hazara', 'short_title' => 'haz', 'description' => null, 'active' => 1, 'created_at' => '2016-05-03 07:37:12', 'updated_at' => null, 'deleted_at' => null],
            ['id' => 4, 'province_id' => 1, 'title' => 'Kohat', 'short_title' => 'koh', 'description' => null, 'active' => 1, 'created_at' => '2016-05-03 07:37:29', 'updated_at' => null, 'deleted_at' => null],
            ['id' => 5, 'province_id' => 1, 'title' => 'Malakand', 'short_title' => 'mal', 'description' => null, 'active' => 1, 'created_at' => '2016-05-03 07:37:43', 'updated_at' => null, 'deleted_at' => null],
            ['id' => 6, 'province_id' => 1, 'title' => 'Mardan', 'short_title' => 'mar', 'description' => null, 'active' => 1, 'created_at' => '2016-05-03 07:38:00', 'updated_at' => null, 'deleted_at' => null],
            ['id' => 7, 'province_id' => 1, 'title' => 'Peshawar', 'short_title' => 'pes', 'description' => null, 'active' => 1, 'created_at' => '2016-05-03 07:38:22', 'updated_at' => null, 'deleted_at' => null],
            ['id' => 8, 'province_id' => 2, 'title' => 'Bahawalpur', 'short_title' => 'bah', 'description' => null, 'active' => 1, 'created_at' => '2018-10-12 09:01:40', 'updated_at' => null, 'deleted_at' => null],
            ['id' => 9, 'province_id' => 2, 'title' => 'Dera Ghazi Khan', 'short_title' => 'dgk', 'description' => null, 'active' => 1, 'created_at' => '2018-10-12 09:01:40', 'updated_at' => null, 'deleted_at' => null],
            ['id' => 10, 'province_id' => 2, 'title' => 'Faisalabad', 'short_title' => 'fai', 'description' => null, 'active' => 1, 'created_at' => '2018-10-12 09:01:40', 'updated_at' => null, 'deleted_at' => null],
            ['id' => 11, 'province_id' => 2, 'title' => 'Gujranwala', 'short_title' => 'guj', 'description' => null, 'active' => 1, 'created_at' => '2018-10-12 09:01:40', 'updated_at' => null, 'deleted_at' => null],
            ['id' => 12, 'province_id' => 2, 'title' => 'Lahore', 'short_title' => 'lah', 'description' => null, 'active' => 1, 'created_at' => '2018-10-12 09:01:40', 'updated_at' => null, 'deleted_at' => null],
            ['id' => 13, 'province_id' => 2, 'title' => 'Multan', 'short_title' => 'mul', 'description' => null, 'active' => 1, 'created_at' => '2018-10-12 09:01:40', 'updated_at' => null, 'deleted_at' => null],
            ['id' => 14, 'province_id' => 2, 'title' => 'Rawalpindi', 'short_title' => 'raw', 'description' => null, 'active' => 1, 'created_at' => '2018-10-12 09:01:40', 'updated_at' => null, 'deleted_at' => null],
            ['id' => 15, 'province_id' => 2, 'title' => 'Sahiwal', 'short_title' => 'sah', 'description' => null, 'active' => 1, 'created_at' => '2018-10-12 09:01:40', 'updated_at' => null, 'deleted_at' => null],
            ['id' => 16, 'province_id' => 2, 'title' => 'Sargodha', 'short_title' => 'sar', 'description' => null, 'active' => 1, 'created_at' => '2018-10-12 09:01:40', 'updated_at' => null, 'deleted_at' => null],
            ['id' => 17, 'province_id' => 3, 'title' => 'Banbhore', 'short_title' => 'bab', 'description' => null, 'active' => 1, 'created_at' => '2018-10-12 09:01:40', 'updated_at' => null, 'deleted_at' => null],
            ['id' => 18, 'province_id' => 3, 'title' => 'Hyderabad', 'short_title' => 'hyd', 'description' => null, 'active' => 1, 'created_at' => '2018-10-12 09:01:40', 'updated_at' => null, 'deleted_at' => null],
            ['id' => 19, 'province_id' => 3, 'title' => 'Karachi', 'short_title' => 'kar', 'description' => null, 'active' => 1, 'created_at' => '2018-10-12 09:01:40', 'updated_at' => null, 'deleted_at' => null],
            ['id' => 20, 'province_id' => 3, 'title' => 'Larkana', 'short_title' => 'lar', 'description' => null, 'active' => 1, 'created_at' => '2018-10-12 09:01:40', 'updated_at' => null, 'deleted_at' => null],
            ['id' => 21, 'province_id' => 3, 'title' => 'Mirpur Khas', 'short_title' => 'mrk', 'description' => null, 'active' => 1, 'created_at' => '2018-10-12 09:01:40', 'updated_at' => null, 'deleted_at' => null],
            ['id' => 22, 'province_id' => 3, 'title' => 'Shaheed Benazir Abad', 'short_title' => 'sha', 'description' => null, 'active' => 1, 'created_at' => '2018-10-12 09:01:40', 'updated_at' => null, 'deleted_at' => null],
            ['id' => 23, 'province_id' => 3, 'title' => 'Sukkur', 'short_title' => 'suk', 'description' => null, 'active' => 1, 'created_at' => '2018-10-12 09:01:40', 'updated_at' => null, 'deleted_at' => null],
            ['id' => 24, 'province_id' => 4, 'title' => 'Kalat', 'short_title' => 'kal', 'description' => null, 'active' => 1, 'created_at' => '2018-10-12 09:01:40', 'updated_at' => null, 'deleted_at' => null],
            ['id' => 25, 'province_id' => 4, 'title' => 'Makran', 'short_title' => 'mak', 'description' => null, 'active' => 1, 'created_at' => '2018-10-12 09:01:40', 'updated_at' => null, 'deleted_at' => null],
            ['id' => 26, 'province_id' => 4, 'title' => 'Nasirabad', 'short_title' => 'nas', 'description' => null, 'active' => 1, 'created_at' => '2018-10-12 09:01:40', 'updated_at' => null, 'deleted_at' => null],
            ['id' => 27, 'province_id' => 4, 'title' => 'Quetta', 'short_title' => 'que', 'description' => null, 'active' => 1, 'created_at' => '2018-10-12 09:01:40', 'updated_at' => null, 'deleted_at' => null],
            ['id' => 28, 'province_id' => 4, 'title' => 'Sibi', 'short_title' => 'sib', 'description' => null, 'active' => 1, 'created_at' => '2018-10-12 09:01:40', 'updated_at' => null, 'deleted_at' => null],
            ['id' => 29, 'province_id' => 4, 'title' => 'Zhob', 'short_title' => 'zho', 'description' => null, 'active' => 1, 'created_at' => '2018-10-12 09:01:40', 'updated_at' => null, 'deleted_at' => null],
            ['id' => 30, 'province_id' => 7, 'title' => 'Baltistan', 'short_title' => 'bal', 'description' => null, 'active' => 1, 'created_at' => '2018-10-12 09:01:40', 'updated_at' => null, 'deleted_at' => null],
            ['id' => 31, 'province_id' => 7, 'title' => 'Gilgit', 'short_title' => 'gil', 'description' => null, 'active' => 1, 'created_at' => '2018-10-12 09:01:40', 'updated_at' => null, 'deleted_at' => null],
            ['id' => 32, 'province_id' => 8, 'title' => 'Mirpur', 'short_title' => 'mir', 'description' => null, 'active' => 1, 'created_at' => '2018-10-12 09:01:40', 'updated_at' => null, 'deleted_at' => null],
            ['id' => 33, 'province_id' => 8, 'title' => 'Muzaffarabad', 'short_title' => 'muz', 'description' => null, 'active' => 1, 'created_at' => '2018-10-12 09:01:40', 'updated_at' => null, 'deleted_at' => null],
            ['id' => 34, 'province_id' => 8, 'title' => 'Poonch', 'short_title' => 'poo', 'description' => null, 'active' => 1, 'created_at' => '2018-10-12 09:01:40', 'updated_at' => null, 'deleted_at' => null],
            ['id' => 39, 'province_id' => 6, 'title' => 'ICT', 'short_title' => 'ict', 'description' => null, 'active' => 1, 'created_at' => '2016-05-03 07:36:39', 'updated_at' => null, 'deleted_at' => null],
            ['id' => 40, 'province_id' => 4, 'title' => 'Rakhshan', 'short_title' => 'rak', 'description' => null, 'active' => 1, 'created_at' => '2019-05-23 04:27:14', 'updated_at' => null, 'deleted_at' => null],
            ['id' => 45, 'province_id' => 8, 'title' => 'Rawalakot', 'short_title' => 'rla', 'description' => null, 'active' => 1, 'created_at' => '2019-05-23 04:28:39', 'updated_at' => null, 'deleted_at' => null],
            ['id' => 46, 'province_id' => 4, 'title' => 'Loralai', 'short_title' => 'lor', 'description' => null, 'active' => 1, 'created_at' => '2021-12-17 01:47:13', 'updated_at' => null, 'deleted_at' => null],
            ['id' => 47, 'province_id' => 2, 'title' => 'Gujrat', 'short_title' => 'grt', 'description' => null, 'active' => 1, 'created_at' => '2022-10-12 09:01:40', 'updated_at' => null, 'deleted_at' => null]
        ]);
    }
}
