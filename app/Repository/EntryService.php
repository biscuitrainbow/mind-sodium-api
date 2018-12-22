<?php 

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use App\Food;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EntryService
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

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
        $user->foodEntries()->attach(
            $food->id,
            [
                'serving' => $request->serving,
                'total_sodium' => $request->total_sodium,
                'date_time' => $request->date_time,
            ]
        );
    }

    public function index()
    {
        return $this->model::all();
    }

    public function userEntries()
    {
        $user = auth()->user();

        $entries = $user->foodEntries()->orderBy('date_time')->get();
        $entries = $entries->map(function ($entry) {
            (float)$entry->serving = $entry->pivot->serving;
            $entry->total_sodium = $entry->pivot->total_sodium;
            $entry->date_time = $entry->pivot->date_time;

            return $entry;
        });

        return $entries;
    }

    public function userEntriesWithDateGrouped()
    {
        $user = auth()->user();
        $userEntries = $this->userEntries();

        $dateGroupedEntries = $userEntries->groupBy(function ($item, $key) {
            return $item->date_time->toDateString();
        });

        $dateGroupedEntries = $dateGroupedEntries->map(function ($dateGroupedEntry, $key) use ($user) {

            $total_sodium = $dateGroupedEntry->reduce(function ($accumulate, $entry) use ($user) {
                $total = $accumulate + $entry->total_sodium;
                return $total;
            });


            return [
                'date' => $key,
                'total_sodium' => $total_sodium,
                'deceed' => $total_sodium < $user->sodium_limit
            ];
        });

        $entries = collect();
        $dateGroupedEntries->each(function ($item) use ($entries) {
            $entries->push($item);
        });

        return $entries;
    }

    public function deceedEntries()
    {
        $user = auth()->user();
        $userEntriesWithDateGrouped = $this->userEntriesWithDateGrouped();

        $notTodayEntries = $userEntriesWithDateGrouped->filter(function ($entry) {
            $now = Carbon::now();
            $entryDate = $entry['date'];

            $notToday = $entryDate != $now->toDateString();
            return $notToday;
        });

        $deceedEntries = $notTodayEntries->where('deceed', true)->values();

        return $deceedEntries;
    }

}