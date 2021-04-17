<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class SportCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sport_categories')->insert([
            [
                'name' => 'Football',
            ],
            [
                'name' => 'Tennis',
            ],
            [
                'name' => 'Volleyball',
            ],
            [
                'name' => 'Badminton',
            ],
            [
                'name' => 'Snooker' 
            ],
            [
                'name' => 'League of Legends'
            ]
        ]);
    }
}
