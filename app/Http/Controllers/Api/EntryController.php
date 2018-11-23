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


        if ($request->filled('date')) {
            $entries = $user->foodEntries()->whereDate('date_time', $date)->orderBy('date_time')->get();
        } else {
            $entries = $user->foodEntries()->orderBy('date_time')->get();
        }

        $entries = $entries->map(function ($entry) {
            $entry->serving = $entry->pivot->serving;
            $entry->total_sodium = $entry->pivot->total_sodium;
            $entry->date_time = $entry->pivot->date_time;

            return $entry;
        });


        return $deceedEntries = $this->toDateGroupedEntries($entries)->where('excess', false)->values();


        //return $this->respond($entries);
    }

    public function dategrouped(Request $request)
    {
        $user = auth()->user();

        $entries = $user->foodEntries()->orderBy('date_time')->get();
        $entries = $entries->map(function ($entry) {
            $entry->serving = $entry->pivot->serving;
            $entry->total_sodium = $entry->pivot->total_sodium;
            $entry->date_time = $entry->pivot->date_time;

            return $entry;
        });


        $dateGroupedEntries = $this->toDateGroupedEntries($entries);

        return $this->respond($dateGroupedEntries);
    }

    private function toDateGroupedEntries($entries)
    {
        $user = auth()->user();

        $dateGroupedEntries = $entries->groupBy(function ($item, $key) {
            return $item->date_time->toDateString();
        });

        $dateGroupedEntries = $dateGroupedEntries->map(function ($et, $key) use ($user) {
            $total_sodium = $et->reduce(function ($accumulate, $entry) use ($user) {
                return $accumulate + $entry->total_sodium;
            });


            return [
                'date' => $key,
                'total_sodium' => $total_sodium,
                'excess' => $total_sodium > $user->sodium_limit
            ];
        });

        $entries = collect();
        $dateGroupedEntries->each(function ($item) use ($entries) {
            $entries->push($item);
        });

        return $entries;
    }

    private function toDeeceedEntries($entries)
    {
        $dateGroupedEntries = $this->toDateGroupedEntries($entries);
        $entries = $entries->where('excess', false)->values();

        return $entries;
    }


}
