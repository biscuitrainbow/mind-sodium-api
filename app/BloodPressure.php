<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BloodPressure extends Model
{
    protected $fillable = ['diastolic', 'systolic'];
    protected $casts = ['diastolic' => 'int', 'systolic' => 'int'];

}
