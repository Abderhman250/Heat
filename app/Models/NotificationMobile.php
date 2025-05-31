<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class NotificationMobile extends Model
{
    use HasFactory , SoftDeletes;
    
    protected $table   = "notification_mobiles";
    protected $guarded = ['id'];

    public static function getTableName() {
        return with(new static)->getTable();
    }

    public function getUser()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

}
