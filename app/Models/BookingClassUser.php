<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingClassUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'class_id',
        'quantity',
        'class_completed'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class); // Assuming the model is named ClassModel
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
