<?php 

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use App\Food;
use App\Achievement;
use Carbon\Carbon;
use App\Achievements\TotalSodiumBalancing3Days;
use App\Achievements\TotalSodiumBalancing7Days;
use App\Achievements\TotalSodiumBalancing15Days;
use App\Achievements\TotalSodiumBalancing30Days;

class AchievementService
{
    protected $model;
    protected $entryRepository;

    public function __construct(Model $model, EntryService $entryRepository)
    {
        $this->model = $model;
        $this->entryRepository = $entryRepository;
    }


    public function index()
    {
        return $this->model::all();
    }

    public function userAchievements()
    {
        $user = auth()->user();

        $achievements = $user->achievements->map(function ($achievement) {
            $achievementId = $achievement->achievement_id;
            $achievementById = Achievement::find($achievementId);

            $achievement->name = $achievementById->name;
            $achievement->description = $achievementById->description;

            return $achievement;
        });

        $achievements = $achievements->sortBy('achievement_id')->values();

        return $achievements;
    }

    public function unlock()
    {
        $user = auth()->user();

        $deceedEntries = $this->entryRepository->deceedEntries();
        $deceedDays = $deceedEntries->count();
        $totalSosdiumBalancingResult = $this->unlockTotalSodiumBalancing($deceedDays, $user);

        $unlockedAchievements = collect();
        $unlockedAchievements->push($totalSosdiumBalancingResult);

        $unlockedAchievements = $unlockedAchievements->filter(function ($achievement) {
            return !is_null($achievement);
        });

        return $unlockedAchievements;
    }

    private function unlockTotalSodiumBalancing($deceedDays, $user)
    {
        $unlocked = false;
        $achievement = null;

        if ($deceedDays == 3) {
            $status = $user->achievementStatus(new TotalSodiumBalancing3Days());

            if (is_null($status->unlocked_at)) {
                $user->unlock(new TotalSodiumBalancing3Days());

                $unlocked = true;
                $achievement = $user->achievementStatus(new TotalSodiumBalancing3Days());
            }

        } else if ($deceedDays == 7) {
            $status = $user->achievementStatus(new TotalSodiumBalancing7Days());

            if (is_null($status->unlocked_at)) {
                $user->unlock(new TotalSodiumBalancing7Days());

                $unlocked = true;
                $achievement = $user->achievementStatus(new TotalSodiumBalancing7Days());
            }
        } else if ($deceedDays == 15) {
            $status = $user->achievementStatus(new TotalSodiumBalancing15Days());

            if (is_null($status->unlocked_at)) {
                $user->unlock(new TotalSodiumBalancing15Days());

                $unlocked = true;
                $achievement = $user->achievementStatus(new TotalSodiumBalancing15Days());
            }
        } else if ($deceedDays == 30) {
            $status = $user->achievementStatus(new TotalSodiumBalancing30Days());

            if (is_null($status->unlocked_at)) {
                $user->unlock(new TotalSodiumBalancing30Days());

                $unlocked = true;
                $achievement = $user->achievementStatus(new TotalSodiumBalancing30Days());
            }
        }

        if (!is_null($achievement)) {
            $achievement->name = Achievement::find($achievement->achievement_id)->name;
            $achievement->description = Achievement::find($achievement->achievement_id)->description;
        }

        return $achievement;
    }


}