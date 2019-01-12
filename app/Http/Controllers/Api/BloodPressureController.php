<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\BloodPressure;

class BloodPressureController extends ApiController
{
    public function index()
    {
        $user = auth()->user();
        $bloodPressures = $user->bloodPressures()->orderBy('date_time', 'desc')->get();

        return $this->respond($bloodPressures);
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        $createdBloodPressure = $user->bloodPressures()->save(new BloodPressure([
            'systolic' => $request->systolic,
            'diastolic' => $request->diastolic,
            'date_time' => $request->date_time
        ]));

        return $this->respondCreated($createdBloodPressure);
    }

    public function update(Request $request, BloodPressure $bloodPressure)
    {
        $bloodPressure->update([
            'systolic' => $request->systolic,
            'diastolic' => $request->diastolic,
            'date_time' => $request->date_time
        ]);

        return $this->respondSuccess();
    }

    public function destroy(BloodPressure $bloodPressure)
    {
        $bloodPressures = $bloodPressure->delete();

        return $this->respondSuccess();
    }
}

