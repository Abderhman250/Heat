<?php

use Illuminate\Database\Seeder;

class CountryCitySeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('countries')->insert([
            'id' => 2,
            'name_ar' => 'Algeria',
            'name' => 'الجزائر',
            'phone_code' => '213',
            'region_id' => '1'
        ]);

        DB::table('cities')->insert([
            'id' => '62037',
            'name_ar' => 'Algiers',
            'name' => 'الجزائر',
            'country_id' => '2',
        ]);
    }
}
