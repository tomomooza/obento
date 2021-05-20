<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Obento;
use App\Manage_dish;
use App\Dish;

class AjaxObentoController extends Controller
{
    //
    function post(Request $request) 
    {
        $colors = [
            'white',
            'pink',
            'red',
            'green',
            'yellowish_green',
            'yellow',
            'beige',
            'orange',
            'brown',
            'purple',
            'black',
        ];
        $obentos_data = [];
        $dish = [];
        $obentos_db = Obento::where('user_id', Auth::user()->id)->where('obento_date', $request->obento_date)->first();
        if ($obentos_db) {
            $obentos_data['memo'] = $obentos_db->memo;
            $obentos_data['photo'] = $obentos_db->photo;
            $manage_dishes_db = Manage_dish::where('obentos_id', $obentos_db->id)->get();
            foreach ($manage_dishes_db as $v) {
                $dish = [];
                $dishes_db = Dish::find($v->dishes_id);
                $dish['dishes_id'] = $dishes_db->id;
                $dish['dish_name'] = $dishes_db->dish_name;
                $dish['seasoning'] = $dishes_db->seasoning;
                for ($i = 0; $i < count($colors); $i++) {
                    $dish[$colors[$i]] = $dishes_db->{$colors[$i]};
                }
                $dishes[] = $dish;
            }
            $obentos_data['dishes'] = $dishes;
        } else {
            $obentos_data['memo'] = '';
            $obentos_data['photo'] = '';
            $obentos_data['dishes'] = [];
        }

        return response()->json($obentos_data);
    }
}
