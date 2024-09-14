<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    use HasFactory;
    protected $table = 'classes';

    protected $fillable = [
        'name',
        'description',
        'photo',
        'room',
        'seat_selection_required',
        'capacity',
        'class_type_id',
    ];
    
    protected $casts = [
        'seat_selection_required' => 'bool',
    ];
    
    
    public function classType()
    {
        return $this->belongsTo(ClassType::class);
    }

    public function seatPoints()
    {
        return $this->hasMany(SeatPoint::class, 'class_id');
    }

    public function plans()
    {
        return $this->hasMany(Plan::class,'class_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
