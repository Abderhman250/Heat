<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {


        return [
            'id' => $this->id,
            'comment' => $this->comment,
            'user' => [
                'id' => $this->user->id,
                'username' => $this->user->username,
                'photo' => $this->user->photo,
            ],
            'poll_id' => $this->poll_id,
            'is_my_comment' =>$this->user->id === Auth::id(),
            'comment_ago' => ($this->created_at !== null) ? Carbon::parse($this->created_at)->diffForHumans() : null,
            'created_at' => $this->created_at,

        ];
    }
}
