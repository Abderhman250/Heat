<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassType extends Model
{
    use HasFactory;
    
    protected $fillable = ['type_name', 'booking_process'];

    public function classes()
    {
        return $this->hasMany(ClassModel::class);
    }
}
