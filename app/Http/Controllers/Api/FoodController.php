<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use FatSecret;
use App\Food;
use function foo\func;

class FoodController extends ApiController
{
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
        return FatSecret::getIngredient($food);
    }
}
