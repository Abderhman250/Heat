<?php
namespace Database\Seeders;

use App\Lookup;
use Illuminate\Database\Seeder;

class LookupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
  
        $data_default = [
            [
                'type' => 'GENDER',
                'key' => 'GENDER_FEMALE',
                'value' => 'female',
                'column_id' => 'gender_id',
                'description' => 'This type of gender is female',
            ],
            [
                'type' => 'GENDER',
                'key' => 'GENDER_MALE',
                'value' => 'male',
                'column_id' => 'gender_id',
                'description' => 'This type of gender is male',
            ],
 
           
        ];
    
        foreach ($data_default as $data) {
            Lookup::firstOrCreate(
                [
                    'type' => $data['type'],
                    'key' => $data['key'],
                ],
                [
                    'value' => $data['value'],
                    'column_id' => $data['column_id'],
                    'referans_key'=> $data['referans_key'] ?? null,
                    'description' => $data['description'],
                ]
            );

        }
    }
}

