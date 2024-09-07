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
        // $max = Poll::max('id');
        // Poll::where('id',"<=",$max )->delete();
        // Lookup::truncate();
        $data_default = [
            [
                'type' => 'TYPE_POLL',
                'key' => 'POLL_PUBLIC',
                'value' => 'public',
                'column_id' => 'type_id',
                'description' => 'This type of poll is public',
            ],
            [
                'type' => 'TYPE_POLL',
                'key' => 'POLL_PRIVATE',
                'value' => 'private',
                'column_id' => 'type_id',
                'description' => 'This type of poll is private',
            ],
            [
                'type' => 'AREA',
                'key' => 'POLL_AREA_GLOBAL',
                'value' => 'global',
                'column_id' => 'area_id',
                'description' => 'This area is global',
            ],
            [
                'type' => 'AREA',
                'key' => 'POLL_AREA_CITY',
                'value' => 'city',
                'column_id' => 'area_id',
                'description' => 'This area is city-specific',
            ],
            [
                'type' => 'AREA',
                'key' => 'POLL_AREA_COUNTRY',
                'value' => 'country',
                'column_id' => 'area_id',
                'description' => 'This area is country-specific',
            ],
            [
                'type' => 'AREA',
                'key' => 'POLL_AREA_REGION',
                'value' => 'region',
                'column_id' => 'area_id',
                'description' => 'This area is region-specific',
            ],
            [
                'type' => 'USER_TYPE',
                'key' => 'POLL_USER_TYPE_ALL',
                'value' => 'all',
                'column_id' => 'user_type_id',
                'description' => 'This user type is all',
            ],
            [
                'type' => 'USER_TYPE',
                'key' => 'POLL_USER_TYPE_INDIVIDUAL',
                'value' => 'individual',
                'column_id' => 'user_type_id',
                'description' => 'This user type is individual',
            ],
            [
                'type' => 'USER_TYPE',
                'key' => 'POLL_USER_TYPE_ORGANIZATION',
                'value' => 'organization',
                'column_id' => 'user_type_id',
                'description' => 'This user type is organization',
            ],
            [
                'type' => 'INDIVIDUAL_TYPE',
                'key' => 'POLL_INDIVIDUAL_TYPE_BOTH',
                'value' => 'both',
                'column_id' => 'individual_type_id',
                'referans_key' => 'POLL_USER_TYPE_INDIVIDUAL',
                'description' => 'This individual type is both',
            ],
            [
                'type' => 'INDIVIDUAL_TYPE',
                'key' => 'POLL_INDIVIDUAL_TYPE_MALE',
                'value' => 'male',
                'column_id' => 'individual_type_id',
                "referans_key"=>"POLL_USER_TYPE_INDIVIDUAL",
                'description' => 'This individual type is male',
               
            ],
            [
                'type' => 'INDIVIDUAL_TYPE',
                'key' => 'POLL_INDIVIDUAL_TYPE_FEMALE',
                'value' => 'female',
                'column_id' => 'individual_type_id',
                "referans_key"=>"POLL_USER_TYPE_INDIVIDUAL",
                'description' => 'This individual type is female',
            ],

            [
                'type' => 'DESIGN',
                'key' => 'POLL_DESIGN_QUESTION_BOOLEAN',
                'value' => 'boolean',
                'column_id' => 'design_id',
                'description' => 'This design type is boolean',
            ],
            [
                'type' => 'DESIGN',
                'key' => 'POLL_DESIGN_QUESTION_MULTIPLE_CHOICE',
                'value' => 'multiple_choice',
                'column_id' => 'design_id',
                'description' => 'This design type is multiple choice',
            ],
            [
                'type' => 'DESIGN',
                'key' => 'POLL_DESIGN_QUESTION_NUMERIC',
                'value' => 'numeric',
                'column_id' => 'design_id',
                'description' => 'This design type is numeric',
            ],
            [
                'type' => 'DESIGN',
                'key' => 'POLL_DESIGN_QUESTION_COUNTRY_OPTIONS',
                'value' => 'country_options',
                'column_id' => 'design_id',
                'description' => 'This design type is country options',
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

