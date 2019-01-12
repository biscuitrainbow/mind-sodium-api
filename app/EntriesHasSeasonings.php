<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EntriesHasSeasonings extends Model
{

    protected $hidden = [
        'created_at',
        'updated_at',
        'seasoning_id',
        'seasoning_unit_id'
    ];

    protected $table = 'entries_has_seasonings';

    public function seasoning()
    {
        return $this->belongsTo(Seasoning::class, 'seasoning_id');
    }

    public function unit()
    {
        return $this->belongsTo(SeasoningUnits::class, 'seasoning_unit_id');
    }
}
