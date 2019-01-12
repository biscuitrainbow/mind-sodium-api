<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BloodPressure extends Model
{
    protected $fillable = ['diastolic', 'systolic','date_time'];
    protected $casts = ['diastolic' => 'int', 'systolic' => 'int'];

}
