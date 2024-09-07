<?php
use Illuminate\Database\Seeder;

class RegionTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('regions')->insert([
            'name' => 'Arab World',
            'name_ar' => 'الوطن العربي',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('regions')->insert([
            'name' => 'Central & North Asia',
            'name_ar' => 'وسط وشمال آسيا',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('regions')->insert([
            'name' => 'East Asia & Pacific',
            'name_ar' => 'شرق آسيا والمحيط الهادئ',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('regions')->insert([
            'name' => 'Europe',
            'name_ar' => 'أوروبا',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('regions')->insert([
            'name' => 'Latin America & The Caribbean',
            'name_ar' => 'أمريكا اللاتينية والكاريبي',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('regions')->insert([
            'name' => 'North America',
            'name_ar' => 'أمريكا الشمالية',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('regions')->insert([
            'name' => 'South Asia',
            'name_ar' => 'جنوب آسيا',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('regions')->insert([
            'name' => 'Sub-Saharan Africa',
            'name_ar' => 'أفريقيا',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
