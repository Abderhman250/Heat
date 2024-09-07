<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    //
    protected $guarded = [];

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id', 'id');
    }

}
