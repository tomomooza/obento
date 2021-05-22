<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Obento;
use App\Manage_dish;
use App\Dish;
use Auth;

class MenuController extends Controller
{
    //
    function index() {
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

        $year= session('year');
        session()->forget('year');
        $month = session('month');
        session()->forget('month');
        if (empty($year)) {
            $year = date('Y');
        }
        if (empty($month)) {
            $month = date('m');
        }

        $obentos_db = Obento::where('user_id', Auth::user()->id)->whereYear('obento_date', $year)->whereMonth('obento_date', $month)->orderby('obento_date', 'ASC')->get();
        $obentos_data = [];
        if ($obentos_db) {
            foreach($obentos_db as $v) {
                $manage_dishes = Manage_dish::where('obentos_id', $v->id)->get();
                $dishes = [];
                foreach($colors as $c) {
                    ${$c} = 0;
                }
                foreach($manage_dishes as $w) {
                    $dishes_db = Dish::find($w->dishes_id);
                    $dish = [];
                    $dish['dish_name'] = $dishes_db->dish_name;
                    $dish['seasoning'] = $dishes_db->seasoning;
                    foreach($colors as $c) {
                        $dish[$c] = $dishes_db->{$c};
                        if ($dishes_db->{$c} == 1) {
                            ${$c} = 1;
                        }
                    }
                    $dishes[] = $dish;
                }
                $obento = [];
                $obento['obento_date'] = $v->obento_date;
                $obento['memo'] = $v->memo;
                $obento['photo'] = $v->photo;
                $obento['dishes'] = $dishes; 
                foreach($colors as $c) {
                    $obento[$c] = ${$c};
                }
                $obentos_data[] = $obento;
            }
        }
        
        $errors = session('errors');
        session()->forget('errors');

        return view('menu',compact('obentos_data', 'errors', 'year', 'month'));
    }

    function post(Request $request) {
        $errors = [];
        if (!$request->filled('year')) {
            $errors[] ='年を選択してください'; 
        }
        if (!$request->filled('month')) {
            $errors[] ='月を選択してください'; 
        }
        if (count($errors) !=0) {
            session(['errors' => $errors]);
            return redirect()->action('MenuController@index');
        }
       session(['year'=>$request->year]);
       session(['month'=>$request->month]);
       return redirect()->action('MenuController@index');
    }
}
