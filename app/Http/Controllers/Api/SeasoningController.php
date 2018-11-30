<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Seasoning;

class SeasoningController extends ApiController
{
    public function index()
    {
        return Seasoning::all();
    }
}
