<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use App\Helper\Helper;
use Illuminate\Notifications\Notifiable;
use App\Country;
use App\City;
use App\Models\Booking;
use App\Models\Coache;
use App\Models\Transaction;
use App\Models\UserPlan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable
{
    use LaratrustUserTrait; // Add this trait
    use HasApiTokens, Notifiable, HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'phone',
        'country_code',
        'gender',
        'email',
        'dob',
        'photo',
        'password',
        'facebook_id',
        'is_coache',
        'is_active',
        'otp'
    ];

    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'user_plans')
            ->withPivot('start_date', 'end_date')
            ->withTimestamps();
    }
    public function coach()
    {
        return $this->hasOne(Coache::class);
    }

    public function role()
    {
        return $this->hasMany(Role::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function userPlans()
    {
        return $this->hasMany(UserPlan::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
