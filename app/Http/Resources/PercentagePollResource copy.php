<?php

namespace App\Http\Resources;

use App\Poll;
use App\Vote;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PercentagePollResource2 extends JsonResource
{


    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'answers' => $this->answersPercentage($request),
        ];
    }

    private function answersPercentage($request)
    {
        $default_time = $request->default_time ?? null;
        $start_date = $request->start_date ?? null;
        $end_date = $request->end_date ?? null;

        $choices = $this->choice;
        $votes = $this->votes;

        if (isset($request->choice_id)) {
            $choices = $choices->whereIn('id', $request->choice_id);
            $votes = $votes->whereIn('choices_id', $request->choice_id);
        }

        if ($default_time) {
            $votes = $this->filterVotesByTime($votes, $default_time, $start_date, $end_date);
        }

        $totalVotes = $votes->count() ?? 0;

        return $choices->map(function ($choice) use ($votes, $totalVotes) {
            $choice_id = $choice['id'] ?? null;
            $count_votes = $votes->where('choices_id', $choice_id)->count();
            $percentage_votes = ($totalVotes > 0) ? ($count_votes / $totalVotes) * 100 : 0;

            return [
                'id' => $choice_id,
                'answer' => $choice['answer'] ?? null,
                'count_votes' => $count_votes,
                'percentage_votes' => $percentage_votes,
             ];
        })->all();
    }

    private function filterVotesByTime($votes, $default_time, $start_date, $end_date)
    {
        switch ($default_time) {
            case "last_24_hours":
                return $votes->whereBetween('created_at', [Carbon::now()->subDay(), Carbon::now()]);
            case "custom":
                if ($start_date !== null && $end_date !== null) {
                    $parsedStartDate = Carbon::parse($start_date);
                    $parsedEndDate = Carbon::parse($end_date);
                    return $votes->whereBetween('created_at', [$parsedStartDate, $parsedEndDate]);
                }
                break;
        }
        return $votes;
    }

}
