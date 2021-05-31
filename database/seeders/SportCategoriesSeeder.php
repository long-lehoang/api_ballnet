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
                'name' => 'Bóng đá',
            ],
            [
                'name' => 'Quần vợt',
            ],
            [
                'name' => 'Bóng chuyền',
            ],
            [
                'name' => 'Bóng bàn',
            ],
            [
                'name' => 'Bóng rổ' 
            ],
            [
                'name' => 'Bóng chày'
            ]
        ]);
    }
}
