<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MentalHealth extends Model
{
    protected $table = 'mental_healths';

    protected $fillable = [
        'level',
        'date_time'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'date_time'
    ];
}
