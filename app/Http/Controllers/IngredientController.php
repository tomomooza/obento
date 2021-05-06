<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Ingredient;

class IngredientController extends Controller
{
    //
    function index() {
        $ingredients_data = [];
        $ingredients_db = Ingredient::get();
        foreach($ingredients_db as $v) {
            $ingredient = [];
            $ingredient['id'] = $v->id;
            $ingredient['ingredient'] = $v->ingredient;
            $ingredient['category'] = $v->category;
            $ingredients_data[] = $ingredient;
        }
        $error = session('error');
        session()->forget('error');
        return view('ingredient', compact('ingredients_data', 'error'));
    }
    function post(Request $request) {
        if ($request->filled('ingredient') && $request->filled('category')) {
            $ingredients_db = Ingredient::where('ingredient', $request->ingredient)->first();
            if ($ingredients_db == NULL) {
                $ingredients_db = new Ingredient;
                $form = $request->all();
                unset($form['_token']);
                $ingredients_db->fill($form)->save();
            } else {
                session(['error'=>'その食材はすでに登録されています']);
            }
        } else {
            session(['error'=>'食材名を入力して下さい']);
        }
        return redirect()->action('IngredientController@index');
    }
    function put(Request $request) {
        if ($request->filled('select_ingredient')) {
            $ingredients_db = Ingredient::where('ingredient', $request->change_ingredient)->first();
            if ($ingredients_db == NULL) {
                $form = [];
                $form['id'] = $request->select_ingredient;
                $form['ingredient'] = $request->change_ingredient;
                $form['category'] = $request->change_category;
                $ingredients_db = new Ingredient;
                $ingredients_db->fill($form)->save();
            } else if ($ingredients_db['id'] == $request->select_ingredient) {
                $form = [];
                $form['id'] = $request->select_ingredient;
                $form['ingredient'] = $request->change_ingredient;
                $form['category'] = $request->change_category;
                $ingredients_db = new Ingredient;
                $ingredients_db->fill($form)->save();
            } else {
                session(['error'=>'食材名が重複しているため変更できません']);
            }
        } else {
            session(['error'=>'食材を選択して下さい']);
        }
        return redirect()->action('IngredientController@index');
    }
    
}
