<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coache extends Model
{
    use HasFactory;
    protected $fillable = ['username', 'specialty', 'bio', 'profile_photo', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
