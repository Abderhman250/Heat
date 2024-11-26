<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionPlan extends Model
{
    use HasFactory;

    protected $table = 'section_plans';

    protected $fillable = ['section_name', 'description'];

    public function plans()
    {
        return $this->hasMany(Plan::class);
    }
}
