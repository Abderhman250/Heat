<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PercentagePollResource extends JsonResource
{
    private $dataCheck;
    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->dataCheck = [
            "last_24_hours" => [
                "selectd" => ' DATE_FORMAT(created_at, "%Y-%m-%d %H:00") as period',
                'groupBy' => 'period'
            ],
            "custom" => [
                "selectd" => ' DATE_FORMAT(created_at, "%Y-%m-%d") as period',
                'groupBy' => 'period'
            ],
            "since_creation" => [
                "selectd" => ' DATE_FORMAT(created_at, "%Y-%m-%d") as period',
                'groupBy' => 'period'
            ],
        ];
    }
    
    public function toArray($request)
    {

        $choices = $this->answers;
        $default_time = $request['default_time'];
        $choice_ids = $request['choice_id'] ?? $this->answers->pluck('id')->toArray();

        $start_date = Carbon::parse($request->get('start_date'));
        $end_date = Carbon::parse($request->get('end_date'))->endOfDay();

        $dataCheck = $this->dataCheck[$default_time];
        $periodVotes = [];

        $votes = $this->votes()
            ->selectRaw('choices_id, COUNT(*) as count,' . $dataCheck['selectd'])
            ->whereIn('choices_id', $choice_ids);

        if ($default_time === 'custom') {

            $votes =   $votes->whereBetween('created_at', [$start_date, $end_date]);
            $periodVotes = $this->initializeDailyPeriods($start_date, $end_date);
            
        }elseif ($default_time === 'since_creation') {

            $start_date =Carbon::parse($this->start_date);
            $end_date = now();
            $votes =   $votes->whereBetween('created_at', [$start_date, $end_date]);
            $periodVotes = $this->initializeDailyPeriods($start_date, $end_date);
            
        }elseif ($default_time === 'last_24_hours') {

            $votes = $votes->where('created_at', '>=', Carbon::now()->subDay());
            $periodVotes = $this->initializeLast24Hours();
        }

        $votes = $votes->groupBy('choices_id', $dataCheck['groupBy'])
            ->get();

        foreach ($votes as $vote) {
            $period = $vote->period;
            if (!isset($periodVotes[$period])) {
                $periodVotes[$period] = [
                    'period' => $period,
                    'total_votes' => 0,
                    'choices' => []
                ];
            }
            $periodVotes[$period]['total_votes'] += $vote->count;
            $periodVotes[$period]['choices'][$vote->choices_id] = $vote->count;
        }

        return $this->calculatePercentages($periodVotes, $choices, $choice_ids);
    }

    private function initializeLast24Hours()
    {
        $periodVotes = [];
        $currentHour = Carbon::now()->startOfHour();
        for ($i = 0; $i < 24; $i++) {
            $hour = $currentHour->copy()->subHours($i)->format('Y-m-d H:00');
            $periodVotes[$hour] = [
                'period' => $hour,
                'total_votes' => 0,
                'choices' => []
            ];
        }
        return $periodVotes;
    }

    private function initializeDailyPeriods($start_date, $end_date)
    {
        $periodVotes = [];
        $period = $start_date->copy();
        while ($period->lte($end_date)) {
            $day = $period->format('Y-m-d');
            $periodVotes[$day] = [
                'period' => $day,
                'total_votes' => 0,
                'choices' => []
            ];
            $period->addDay();
        }
        return $periodVotes;
    }

    private function calculatePercentages($periodVotes, $choices, $choice_ids)
    {
        $result = [];
        foreach ($periodVotes as $period => $data) {
            $periodData = [
                'period' => $period,
                'choices' => []
            ];

            // Calculate the total votes for the specified choices in this period
            $totalVotesForChoices = array_sum(array_intersect_key($data['choices'], array_flip($choice_ids)));

            foreach ($choices as $choice) {
                if (in_array($choice->id, $choice_ids)) {
                    $voteCount = $data['choices'][$choice->id] ?? 0;
                    $percentage = ($totalVotesForChoices > 0) ? ($voteCount / $totalVotesForChoices) * 100 : 0;
                    $periodData['choices'][] = [
                        'choice_id' => $choice->id,
                        'answer' => $choice->answer,
                        'count_votes' => $voteCount,
                        'percentage_votes' => $percentage
                    ];
                }
            }
            $result[] = $periodData;
        }
        return $result;
    }
}
