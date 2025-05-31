<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = ['plan_name', 'class_id', 'total_classes', 'description', 'photo' ,'price' , 'active','section_plan_id' , 'type'];

    public function class()
    {
        return $this->belongsTo(ClassModel::class);
    }

    public function userPlans()
    {
        return $this->hasMany(UserPlan::class);
    }

    public function sectionPlan()
    {
        return $this->belongsTo(SectionPlan::class);
    }
    public function sectionPlanOne()
    {
        return $this->hasOne(SectionPlan::class,  'id','section_plan_id');
    }


    public function sectionPlanHas()
    {
        return $this->belongsTo(SectionPlan::class);
    }
}
