<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Ingredient;
use App\Season;

class ObentoController extends Controller
{
    //
    function index() {
        $user_id = Auth::user()->id;
        $ingredients_data = [];
        $ingredients_db = Ingredient::orderby('category', 'asc')->get();
        foreach($ingredients_db as $v) {
            $ingredient = [];
            $ingredient['ingredients_id'] = $v->id;
            $ingredient['ingredient'] = $v->ingredient;
            $ingredient['category'] = $v->category;
            $seasons_db = Season::where('user_id', $user_id)->where('ingredients_id', $v->id)->first();
            if ($seasons_db == NULL) {
                $ingredient['seasons_id'] = '';
                $ingredient['season1'] = '0'; 
                $ingredient['season2'] = '0'; 
                $ingredient['season3'] = '0'; 
                $ingredient['season4'] = '0'; 
                $ingredient['season5'] = '0'; 
                $ingredient['season6'] = '0'; 
                $ingredient['season7'] = '0'; 
                $ingredient['season8'] = '0'; 
                $ingredient['season9'] = '0'; 
                $ingredient['season10'] = '0'; 
                $ingredient['season11'] = '0'; 
                $ingredient['season12'] = '0'; 
                $ingredient['memo'] = '';
            } else {
                $ingredient['seasons_id'] = $seasons_db->id;
                $ingredient['season1'] = $seasons_db->season1; 
                $ingredient['season2'] = $seasons_db->season2; 
                $ingredient['season3'] = $seasons_db->season3; 
                $ingredient['season4'] = $seasons_db->season4; 
                $ingredient['season5'] = $seasons_db->season5; 
                $ingredient['season6'] = $seasons_db->season6; 
                $ingredient['season7'] = $seasons_db->season7; 
                $ingredient['season8'] = $seasons_db->season8; 
                $ingredient['season9'] = $seasons_db->season9; 
                $ingredient['season10'] = $seasons_db->season10; 
                $ingredient['season11'] = $seasons_db->season11; 
                $ingredient['season12'] = $seasons_db->season12; 
                $ingredient['memo'] = $seasons_db->memo; 
            }
            
            $ingredients_data[] = $ingredient;
        }
        $errors = session('errors');
        session()->forget('errors');

        $obento_date = session('obento_date');
        session()->forget('obento_date');
        if ($obento_date == NULL) {
            $obento_date = date('Y-m-d');
        } 

        return view('obento', compact('ingredients_data', 'errors', 'obento_date'));
    }
    
    function post(Request $request) {
        $errors = [];
        if (!$request->filled('obento_date')) {
            $errors[] = 'お弁当の日付を入力してください';
        }
        session(['obento_date' => $request->obento_date]);
        if (!$request->filled('dishes_id')) {
            $errors[] = 'お料理を登録してください';
        }
        if (count($errors) != 0) {
            session(['errors' => $errors]);
            return redirect()->action('ObentoController@index');
        }

        

        return redirect()->action('ObentoController@index');
    }

}