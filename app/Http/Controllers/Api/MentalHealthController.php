<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\MentalHealth;
use Carbon\Carbon;


class MentalHealthController extends ApiController
{
    public function index()
    {
        return MentalHealth::all();
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $mentalHealth = new MentalHealth([
            'level' => $request->level,
            'date_time' => $request->date_time,
        ]);

        $user->mentalHealths()->save($mentalHealth);

        return $this->respondSuccess();
    }

    public function userMentalHealths()
    {
        $user = auth()->user();

        $mentalHealths = $user->mentalHealths->unique(function ($item) {
            return $item->date_time->toDateString();
        })->values();

        $mentalHealths = $mentalHealths->sortBy(function ($item, $key) {
            return $item->date_time->toDateString();
        })->values();

        return $this->respond($mentalHealths);
    }
}
