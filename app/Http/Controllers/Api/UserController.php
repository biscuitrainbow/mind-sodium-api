<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Transformer\UserTransformer;
use Illuminate\Validation\Rule;

class UserController extends ApiController
{


    public function detail()
    {
        $user = auth()->user();

        return $user;
    }

    public function update(Request $request)
    {

        $user = auth()->user();
        $user->update([
            'name' => $request->name,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth != 'null' ? $request->date_of_birth : null,
            'health_condition' => $request->health_condition,
            'sodium_limit' => $request->sodium_limit,
            'is_new_user' => $request->is_new_user,
            'enable_notification' => $request->enable_notification,
        ]);

        return $this->respondSuccess();
    }
}
