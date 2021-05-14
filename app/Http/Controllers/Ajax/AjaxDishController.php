<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Dish;
use App\User;
use App\Manage_ingredient;

class AjaxDishController extends Controller
{
    //
    function post(Request $request)
    {
        if ($request->mydish == '1') {
            $dishes_db = Dish::where('user_id', Auth::user()->id);
        } else {
            $dishes_db =Dish::whereNotIn('user_id', [Auth::user()->id])->where('public_private', 1);
        }

        //色による検索
        if (
            $request->filled('white') ||
            $request->filled('pink') ||
            $request->filled('red') ||
            $request->filled('green') ||
            $request->filled('yellowish_green') ||
            $request->filled('yellow') ||
            $request->filled('beige') ||
            $request->filled('orange') ||
            $request->filled('brown') ||
            $request->filled('purple') ||
            $request->filled('black')     
            ) {
            if ($request->filled('white')) {
                $dishes_db = $dishes_db->where('white', 1);
            }
            if ($request->filled('pink')) {
                $dishes_db = $dishes_db->where('pink', 1);
            }
            if ($request->filled('red')) {
                $dishes_db = $dishes_db->where('red', 1);
            }
            if ($request->filled('green')) {
                $dishes_db = $dishes_db->where('green', 1);
            }
            if ($request->filled('yellowish_green')) {
                $dishes_db = $dishes_db->where('yellowish_green', 1);
            }
            if ($request->filled('yellow')) {
                $dishes_db = $dishes_db->where('yellow', 1);
            }
            if ($request->filled('beige')) {
                $dishes_db = $dishes_db->where('beige', 1);
            }
            if ($request->filled('orange')) {
                $dishes_db = $dishes_db->where('orange', 1);
            }
            if ($request->filled('brown')) {
                $dishes_db = $dishes_db->where('brown', 1);
            }
            if ($request->filled('purple')) {
                $dishes_db = $dishes_db->where('purple', 1);
            }
            if ($request->filled('black')) {
                $dishes_db = $dishes_db->where('black', 1);
            }
        }

        //味付けによる検索
        if ($request->filled('seasoning') && $request->seasoning != "") {
            $dishes_db = $dishes_db->where('seasoning', $request->seasoning);
        }

        $dishes_db = $dishes_db->get();
        $dishes_data =[];
        foreach($dishes_db as $v) {
            $dish = [];
            $dish['mydish'] = $request->mydish;
            $dish['dishes_id'] = $v ->id;
            $dish['dish_name'] = $v->dish_name;
            $dishes_data[] = $dish;
        }

        return response()->json($dishes_data);
    }

    function put(Request $request)
    {
        $dishes_db = Dish::find($request->dishes_id);
        $manage_ingredients_db = Manage_ingredient::where('dishes_id', $dishes_db->id)->get();
        $manage=ingredient_data = [];
        foreach ($manage_ingredients_db as $v) {
            $manage_ingredients_data[] = $v['ingredients_id'];
        }

        $dishes_data = [];
        if (Auth::user()->id == $dishes_db->user_id) {
            $dishes_data ['mydish'] = '1';
            $dishes_data['author_name'] = Auth::user()->name;
            $dishes_data['dishes_id'] = $dishes_db->id;
        } else {
            $dishes_data['mydish'] = '0';
            $dishes_data['author_name'] = User::find($dishes_db->user_id)->name;
            $dishes_data['dishes_id'] = '';
        }
        $dishes_data['dish_name'] = $dishes_db->dish_name;
        $dishes_data['seasoning'] = $dishes_db->seasoning;
        $dishes_data['memo'] = $dishes_db->memo;
        $dishes_data['white'] = $dishes_db->white;
        $dishes_data['pink'] = $dishes_db->pink;
        $dishes_data['red'] = $dishes_db->red;
        $dishes_data['green'] = $dishes_db->green;
        $dishes_data['yellowish_green'] = $dishes_db->yellowish_green;
        $dishes_data['yellow'] = $dishes_db->yellow;
        $dishes_data['beige'] = $dishes_db->beige;
        $dishes_data['orange'] = $dishes_db->orange;
        $dishes_data['brown'] = $dishes_db->brown;
        $dishes_data['purple'] = $dishes_db->purple;
        $dishes_data['black'] = $dishes_db->black;
        $dishes_data['manage_ingredients'] = $manage_ingredients_data;
        
        return response()-> json($dishes_data);
    }
}
