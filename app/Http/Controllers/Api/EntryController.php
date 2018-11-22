<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Food;
use Carbon\Carbon;

class EntryController extends ApiController
{
    public function store(Request $request)
    {

        $food = Food::firstOrCreate(['id' => $request->food_id], [
            'id' => $request->food_id,
            'name' => $request->food_name,
            'sodium' => $request->food_sodium,
            'is_local' => (float)1,
            'type' => $request->food_type,
        ]);


        $user = auth()->user();

        $user->foodEntries()->attach($food->id, ['serving' => $request->serving, 'total_sodium' => $request->total_sodium, 'date_time' => Carbon::now()]);

        return $this->respondSuccess();
    }

    public function index(Request $request)
    {
        $date = $request->date;
        $user = auth()->user();

        $entries;

        if ($request->filled('date')) {
            $entries = $user->foodEntries()->whereDate('date_time', $date)->get();
        } else {
            $entries = $user->foodEntries;
        }

        $entries = $entries->map(function ($entry) {
            $entry->serving = $entry->pivot->serving;
            $entry->total_sodium = $entry->pivot->total_sodium;
            $entry->date_time = $entry->pivot->date_time;

            return $entry;
        });

        return $this->respond($entries);
    }
}
