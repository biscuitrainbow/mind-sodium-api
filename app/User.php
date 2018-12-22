<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Gstt\Achievements\Achiever;

use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, Achiever;

    protected $casts = ['is_admin' => 'bool', 'serving' => 'float', 'is_new_user' => 'bool'];

    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'date_of_birth',
        'health_condition',
        'sodium_limit',
        'is_new_user'
    ];


    protected $hidden = [
        'pivot',
        'password',
        'remember_token',
        'created_at',
        'updated_at',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'date_time'
    ];

    public function foodEntries()
    {
        return $this->belongsToMany(Food::class, 'users_has_foods', 'user_id', 'food_id')
            ->withPivot(['serving', 'total_sodium', 'date_time']);
    }

    public function mentalHealths()
    {
        return $this->hasMany(MentalHealth::class, 'user_id');
    }

    public function foods()
    {
        return $this->hasMany(Food::class, 'user_id');
    }

    public function news()
    {
        return $this->hasMany(News::class, 'user_id');
    }

    public function bloodPressures()
    {
        return $this->hasMany(BloodPressure::class, 'user_id');
    }
}
