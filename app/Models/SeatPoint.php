<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeatPoint extends Model
{
    use HasFactory;
    protected $fillable = ['seat_number','seat_not_active','line','class_id', 'note'];

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
