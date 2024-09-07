<?php

namespace Database\Seeders;

use App\DesignQuestion;
use App\Lookup;
use App\Poll;
use Illuminate\Database\Seeder;

class DesignQuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DesignQuestion::truncate();

        $poll_max=   Poll::max('id');
        Poll::where('id', '<', $poll_max+1)->delete();
        
        $questions = [
            [
                'design_id' => Lookup::where('key', 'POLL_DESIGN_QUESTION_BOOLEAN')->first()->id,
                'options' =>  (['yes', 'no']),
            ],
            [
                'design_id' => Lookup::where('key', 'POLL_DESIGN_QUESTION_NUMERIC')->first()->id,
                'options' =>  (range(1, 10)),
            ],
            [
                'design_id' => Lookup::where('key', 'POLL_DESIGN_QUESTION_COUNTRY_OPTIONS')->first()->id,
                'options' =>  (['Jordan', 'Saudi Arabia', 'United Arab Emirates', 'Syria']),
            ],
          
        ];

        // foreach ($questions as $question) {
        //     DesignQuestion::updateOrCreate(
        //         ['design_id' => $question['design_id']], 
        //         ['options' => $question['options']]
        //     );
        // }
    }
}
