<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Achievement;
use App\Achievements\TotalSodiumBalancing3Days;
use App\Achievements\TotalSodiumBalancing7Days;
use App\Achievements\TotalSodiumBalancing15Days;
use App\Achievements\TotalSodiumBalancing30Days;
use App\Food;
use App\Repository\UserService;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Repository\EntryService;
use App\Repository\AchievementService;
use Carbon\Carbon;

class AchievementController extends ApiController
{

    protected $userService;
    protected $entryService;
    protected $achievementService;

    public function __construct(User $user, Food $food, Achievement $achievement)
    {
        $this->userService = new UserService($user);
        $this->entryService = new EntryService($food);
        $this->achievementService = new AchievementService($achievement, $this->entryService);
    }


    public function index()
    {
        // $user->unlock(new TotalSodiumBalancing3Days());
        // $user->unlock(new TotalSodiumBalancing7Days());
        // $user->unlock(new TotalSodiumBalancing15Days());
        // $user->unlock(new TotalSodiumBalancing30Days());

        $achievements = $this->achievementService->index();

        return $this->respond($achievements);
    }

    public function userAchievements()
    {
        $achievements = $this->achievementService->userAchievements();

        return $this->respond($achievements);
    }


    public function unlock()
    {
        $unlockingAchievements = $this->achievementService->unlock();

        return $this->respond($unlockingAchievements);
    }
}
