<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Save extends Model
{
    use Notifiable;
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
    public function poll()
    {
        return $this->belongsTo('App\Poll', 'poll_id', 'id');
    }

    function saves()
    {
        return $this->hasManyThrough('App\Poll', 'App\Save', 'poll_id', 'id');
    }
    function comments()
    {
        return $this->hasManyThrough('App\Poll', 'App\Comment', 'poll_id', 'id');
    }
}
