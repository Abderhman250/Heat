<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('app_contents')->insert([
            'phone' => '+962',
            'address' => 'amman',
            'email' => 'email@email.com',
            'privacy_policy' => ' privacy policy',
            'privacy_policy_ar' => 'privacy policy arabic',
            'about' => 'about',
            'about_ar' => 'about_ar',
            'terms' =>'terms',
            'terms_ar' => 'terms_ar',
            'photo'=>""
        ]);
    }
}
