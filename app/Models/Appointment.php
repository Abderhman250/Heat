<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = ['appointment_name', 'class_id', 'coach_id', 'min_participants', 'max_participants', 'start_time', 'end_time', 'location'];

    public function class()
    {
        return $this->belongsTo(ClassModel::class);
    }
    public function coach()
    {
        return $this->belongsTo(Coache::class);
    }
}
