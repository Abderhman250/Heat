<?php

namespace App\Http\Resources;

use App\Comment;
use App\Followers;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isNull;

class PollResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if (!isset($this->id))
            return [];

        $user_id = Auth::id();
        $intrests = [];
        $votes =  isset($this->votes) ? $this->votes : collect([]);
        $countVotes =$this->votes->count();
        $votes = $votes->firstWhere('user_id', $user_id);

        $isFollowed = $this->isFollowedByUser($user_id);
        $timer = $this->calculateTimer($this->end_date ?? null);

        if ($votes)
            $votes = [
                "id" => $votes->id,
                "choices_id" => $votes->choices_id,
                "why_question" => $votes->why_question,
                "vote_ago" => ($votes->created_at !== null) ? Carbon::parse($votes->created_at)->diffForHumans() : null,
            ];

        foreach ($this->intrest ?? [] as $intrest) {

            $intrests[] =  [
                'id' => $intrest->id,
                'name' => $intrest->name
            ];
        }
        $voteDimond = $this->getVoteDimond($countVotes);

        return [

            'id' => $this->id,
            'title' => $this->title,
            'photo' => $this->photo,
            'type_id' =>  $this->lookup($this->type),
            'area_id' => $this->lookup($this->area),
            'user_type_id' =>  $this->lookup($this->user_type),
            'individual_type_id' => $this->lookup($this->individual_type),
            'design_id' => $this->lookup($this->designs),
            'is_draft' => (bool) $this->is_draft,
            'group_id' => $this->group_id,
            'is_hide' => $this->is_hide,
            'is_followed' => $isFollowed,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'timer_end' => $timer,
            'interests' =>  $intrests,
            'answers' => $this->getAnswers($votes),
            'is_why_question' => (bool) $this->is_why_question,
            'is_why_que_mandatory' => (bool) $this->is_why_que_mandatory,
            'one_time_vote' => (bool) $this->one_time_vote,
            'votes' => $votes,
            'user' => [
                "id" => $this->user_id,
                "username" => $this->user->username,
                "photo" => $this->user->photo,
                "region" => $this->user->country->region->name ?? null,
                "country" => $this->user->country->name ?? null,
                "city" => $this->user->city->name ?? null,
                'is_follow' =>  $this->getIsFollower(),
            ],
            'participation_statistics' => [
                'comment' => Comment::where('poll_id', $this->id)->count(),
                'follower' => $this->followers()->count(),
                'viewed' => (int) $this->viewed,
                'share' => (int) $this->shared,
                'votes' => $countVotes,
                'Dimond' => $voteDimond,

            ],

            'poll_ago' => ($this->created_at !== null) ? Carbon::parse($this->created_at)->diffForHumans() : null,
        ];
    }

    private function isFollowedByUser($user)
    {
        $follower = $this->followers ?? false;

        if ($follower)
            return $user ? $this->followers->contains($user) : false;
    }

    private function calculateTimer($end_date)
    {
        $now = Carbon::now();
        $end = Carbon::parse($end_date);
        if(isNull($end_date))
          return ['time' => null,   'time_second' => null];

        if ($end->lessThan($now))
            return ['time' => '00:00:00',   'time_second' => 0];


        $diff = $now->diff($end);
        $secondsDiff = $end->diffInSeconds($now);

        $years = $diff->y;
        $months = $diff->m;
        $days = $diff->d;
        $hours = $diff->h;
        $minutes = $diff->i;
        $seconds = $diff->s;

        $timerString = '';

        if ($years > 0)
            $timerString .= sprintf('%d years ', $years);


        if ($months > 0)
            $timerString .= sprintf('%d months ', $months);


        if ($days > 0)
            $timerString .= sprintf('%d days ', $days);



        $timerString .= sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

        return [
            'time' => $timerString,
            'time_second' => $secondsDiff,
        ];;
    }


    private function  lookup($data)
    {
        if ($data === null)
            return null;

        return [
            "id" => $data->id,
            "value" => $data->value,
            "description" => $data->description
        ];
    }

    private function  getAnswers($votes)
    {


        $answers = [];
        $choices = $this->choice;
        $totalVotes =  $this->votes->count() ?? 0;

        foreach ($choices ?? []  as $choice) {
            $choice_id  = $choice['id'] ?? null;
            $my_answer =  ($votes['choices_id'] ?? null);

            $my_why_question =  ($votes['why_question'] ?? null);

            $count_votes = $this->votes->where('choices_id', $choice_id)->count();
            $percentage_votes = (($totalVotes > 0) ? ($count_votes / $totalVotes) * 100 : 0);

            $answers[] = [
                'id' => $choice_id,
                'answer' => $choice['answer'] ?? null,
                'my_answer' => ($my_answer === $choice->id),
                'why_question' => ($my_answer === $choice->id) ? $my_why_question : null,
                'count_votes' => $count_votes,
                'percentage_votes' =>  $percentage_votes,
            ];
        }


        return  $answers ?? [];
    }




    private function  getIsFollower()
    {

        return Followers::where('user_id', Auth::id())
            ->where('follower_id', $this->user->id)
            ->exists() ?? False;
    }


    private function getVoteDimond($voteCount)
    {
        switch (true) {
            case $voteCount >= 10000:
                return 'Gold';
            case $voteCount >= 2000:
                return 'Silver';
            case $voteCount >= 1000:
                return 'Bronze';
            default:
                return 'Default';
        }
    }
}
