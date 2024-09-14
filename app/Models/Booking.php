<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'appointment_id', 'seat_id', 'status', 'quantity','class_completed'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class); // Class is reserved, use ClassModel
    }

    public function seat()
    {
        return $this->belongsTo(SeatPoint::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
}
