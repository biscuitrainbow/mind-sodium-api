<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Food;
use Carbon\Carbon;
use App\Achievements\UserMadeAPost;
use App\Achievement;
use App\Achievements\TotalSodiumBalancing3Days;
use App\Achievements\TotalSodiumBalancing7Days;
use App\User;
use App\Achievements\TotalSodiumBalancing15Days;
use App\Achievements\TotalSodiumBalancing30Days;
use App\Repository\EntryService;


class EntryController extends ApiController
{

    protected $entryService;

    public function __construct(Food $food)
    {
        $this->entryService = new EntryService($food);
    }

    public function store(Request $request)
    {
        $this->entryService->store($request);
    }


    public function index(Request $request)
    {
        $entries = $this->entryService->index();

        return $this->respond($entries);
    }

    public function userEntries()
    {
        $entries = $this->entryService->userEntries();

        return $this->respond($entries);
    }
}
