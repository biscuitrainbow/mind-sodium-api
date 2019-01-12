<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ApiController;
use App\SeasoningUnits;

class SeasoningUnitController extends ApiController
{
    public function index()
    {
        return SeasoningUnits::all();
    }
}
