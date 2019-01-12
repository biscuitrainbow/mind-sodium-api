<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use FatSecret;
use App\Food;
use function foo\func;

class FoodController extends ApiController
{
    public function index()
    {
        $user = auth()->user();
        $foods = $user->foods;

        return $this->respond($foods);
    }


    public function store(Request $request)
    {
        $storedFood = new Food([
            'name' => $request->food_name,
            'sodium' => $request->food_sodium,
            'is_local' => (float)1,
            'type' => $request->food_type,
        ]);

        $user = auth()->user();
        $user->foods()->save($storedFood);

        $respondFood = Food::find($storedFood->id);

        return $this->respond($respondFood);
    }


    public function search(Request $request)
    {
        $q = $request->q;

        $fatSecretFoods = FatSecret::searchIngredients($q, 1, 50);

        if ($fatSecretFoods['foods']['total_results'] == 0) {
            $fatSecretFoods = collect();
        } else {
            $fatSecretFoods = collect($fatSecretFoods['foods']['food']);
            $fatSecretFoods = $fatSecretFoods->map(function ($food) {
                $modifiedFood['id'] = (int)$food['food_id'];
                $modifiedFood['name'] = $food['food_name'];
                $modifiedFood['is_local'] = false;
                $modifiedFood['type'] = 'ทั่วไป';
                return $modifiedFood;
            });
        }

        $localFood = Food::where('name', 'like', '%' . $q . '%')->get();
        $localFood = $localFood->map(function ($food) {
            $food->type = 'อาหารไทย';
            return $food;
        });

        $foods = $fatSecretFoods->merge($localFood);

        return $this->respond($foods);
    }


    public function detailFatsecret(Request $request, $food)
    {
        $food = FatSecret::getIngredient($food);

        return $this->respond($food);
    }


    public function destroy(Food $food)
    {
        $food->delete();

        return $this->respondSuccess();
    }
}
