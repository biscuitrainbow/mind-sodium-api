<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    protected $table = 'entries';
    protected $fillable = ['serving', 'total_sodium', 'date_time'];

    protected $hidden = [
        'created_at',
        'updated_at',
        'user_id',
        'food_id'
    ];

    public function seasonings()
    {
        //return $this->hasMany(EntriesHasSeasonings::class, 'entry_id');
         return $this->belongsToMany(Seasoning::class, 'entries_has_seasonings', 'entry_id', 'seasoning_id')->withPivot(['seasoning_unit_id as unit','amount as amount']);

    }


    public function unit()
    {
        return $this->belongsTo(SeasoningUnits::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
