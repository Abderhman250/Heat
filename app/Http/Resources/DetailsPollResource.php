<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailsPollResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $answers = [];
        $choices = $this->choice;
        $totalVotes =  $this->votes->count() ?? 0;
        foreach ($choices ?? []  as $choice) {
            $choice_id  = $choice['id'] ?? null;
            $count_votes = $this->votes->where('choices_id', $choice_id)->count();
            $percentage_votes= (($totalVotes > 0) ? ($count_votes / $totalVotes) * 100 : 0);
            $answers[] = [
                'id' => $choice_id,
                'answer' => $choice['answer'] ?? null,
                'count_votes' => $count_votes,
                'percentage_votes' =>  $percentage_votes,
            ];
        }

        return [

            'id' => $this->id,
            'title' => $this->title,
            'photo' => $this->photo,
            'group_id' => $this->group_id,
            'user' => [
                "id" => $this->user_id,
                "username" => $this->user->username,
                "photo" => $this->user->photo,
                "region" => $this->user->country->region->name ?? null,
                "country" => $this->user->country->name ?? null,
                "city" => $this->user->city->name ?? null,
            ],
            "answers" => $answers,
            "satrt_date" => $this->start_date,
            "end_date" => $this->end_date,

            'poll_ago' => ($this->created_at !== null) ? Carbon::parse($this->created_at)->diffForHumans() : null,
        ];
    }
}
