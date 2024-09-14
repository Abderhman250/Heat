<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = ['plan_name', 'class_id', 'total_classes', 'description', 'photo'];

    public function class()
    {
        return $this->belongsTo(ClassModel::class);
    }

    public function userPlans()
    {
        return $this->hasMany(UserPlan::class);
    }
}
