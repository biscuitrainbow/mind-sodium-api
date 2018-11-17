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
            'is_local' => (float)$request->is_local
        ]);


        $user = auth()->user();

        $user->foodEntries()->attach($food->id, ['serving' => $request->serving, 'total_sodium' => $request->total_sodium]);

        return $this->respondSuccess();
    }

    public function index()
    {
        $user = auth()->user();

        $entries = $user->foodEntries;
        $entries = $entries->map(function ($entry) {
            $entry->serving = $entry->pivot->serving;
            $entry->total_sodium = $entry->pivot->total_sodium;

            return $entry;
        });

        return $this->respond($entries);
    }
}
