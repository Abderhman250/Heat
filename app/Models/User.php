<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use App\Helper\Helper;
use Illuminate\Notifications\Notifiable;
use App\Country;
use App\City;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'is_active', 'username', 'status',
        'phone', 'first_name', 'last_name', 'gender', 'country_id', 'city_id', 'otp', 'dob', 'completing_info',
        'google_id', 'facebook_id', 'apple_id','org_name','photo'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function intrest()
    {

        return $this->belongsToMany(Interests::class, 'user_interst', 'user_id', 'interest_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
    }

    public function followings()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id');
    }

    public function followedPolls()
    {
        return $this->belongsToMany(Poll::class, 'follow_poll', 'user_id', 'poll_id')->withTimestamps();
    }

    public function userFollowings()
    {
        return $this->hasMany(User::class, 'followers', 'follower_id', 'user_id');
    }

    public function findForPassport($username)
    {
        return $this->where('phone', $username)->first();
    }

    public function poll()
    {
        return $this->hasMany('App\Poll', 'user_id', 'id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function saves()
    {
        return $this->hasMany('App\Save', 'user_id', 'id');
    }

    public function votes()
    {
        return $this->hasMany('App\Vote', 'user_id', 'id');
    }

    public function invite()
    {
        return $this->hasMany('App\GroupInvite', 'user_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment', 'user_id', 'id');
    }

    public function getCreatedAttribute()
    {
        return $this->created_at->toDateString();
    }
    public function token()
    {
        return $this->hasOne('App\MobileToken', 'user_id', 'id');
    }

    /**
     * Generate unique OTP
     *
     * @return string A unique OTP
     */
    public static function generateUniqueOTP(): string
    {
        $otp = Helper::generateRandomNumber();

        // Check if the generated OTP already exists in the users table
        if (self::isOTPUnique($otp))
            return $otp; // Unique OTP found
        else
            return self::generateUniqueOTP(); // Regenerate OTP recursively

    }

    /**
     * check if OTP is exists or not
     *
     * @param string $otp
     * @return boolean
     */
    private static function isOTPUnique(string $otp): bool
    {
        return !User::where('otp', $otp)->exists();
    }
    
    public function blocks()
    {
        return $this->hasMany(UserBlock::class, 'user_id');
    }
    
  
    public function my_blocks()
    {
        return $this->hasMany(UserBlock::class, 'blocked_user_id');
    }
    

    public function scopeBlockedUserIds($query, $userId)
    {
    
        return $query->whereHas('blocks', function ($query) use ($userId) {
                $query->where('blocked_user_id', $userId);
            })
            ->orWhereHas('my_blocks', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            });
    }
    
}
