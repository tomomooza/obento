<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Ingredient;
use App\Season;
use App\Dish;
use App\Manage_ingredient;
use Illuminate\Support\Facades\DB;

class DishController extends Controller
{
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
        return view('dish', compact('ingredients_data', 'errors'));
    }
    
    function post(Request $request) {
        $errors = [];
        if (!$request->filled('dish_name')) {
            $errors[] = 'お料理の名前を入力して下さい';
        }

        if (
            !$request->filled('white') &&
            !$request->filled('pink') &&
            !$request->filled('red') &&
            !$request->filled('green') &&
            !$request->filled('yellowish_green') &&
            !$request->filled('yellow') &&
            !$request->filled('beige') &&
            !$request->filled('orange') &&
            !$request->filled('brown') &&
            !$request->filled('purple') &&
            !$request->filled('black')     
            ) {
            $errors[] = 'お料理の彩りを入力して下さい';
        }

        if (!$request->filled('seasoning')) {
            $errors[] = 'お料理の味付けを入力して下さい';
        }

        if (!$request->filled('ingredients')) {
            $errors[] = 'お料理の食材を入力して下さい';
        }

        if (!$request->filled('new_change')) {
            $errors[] = 'システムエラーです。管理者にお問い合わせ下さい';
        } else {
            if(!($request->new_change == 'new' || $request->new_change == 'change')) {
                $errors[] = 'システムエラーです。管理者にお問い合わせ下さい';
            }
        }

        if (count($errors) != 0) {
            session(['errors' => $errors]);
            return redirect()->action('DishController@index');
        }

        //他人のお料理を検索した場合ー＞新規登録になる。
        //自分のお料理を検索した場合ー＞新規と変更を選べる。
        //検索せずに、新規入力した場合ー＞新規登録になる。
        //自分のお料理に関しては、料理名の重複は不可とする。
        if ($request->filled('dishes_id') && $request->new_change == 'change') {
            //更新
            $dishes_db = Dish::where('user_id', Auth::user()->id)->where('dish_name', $request->dish_name)->first();
            if (!($dishes_db == NULL) && $dishes_db->id !=$request->dishes_id) {
                $errors[] = 'お料理名が重複しています。別の名前にしてください';
                session(['errors' =>$errors]);
                return redirect()->action('DishController@index');
            }
            $dishes_db = Dish::find($request->dishes_id);
            Manage_ingredient::where('dishes_id', $request->dish_id)->delete();
        } else {
            //新規登録
            $dishes_db = Dish::where('user_id', Auth::user()->id)->where('dish_name', $request->dish_name)->first();
            if (!($dishes_db == NULL)) {
                $errors[] = 'お料理名が重複しています。別の名前にしてください。';
                session(['errors' => $errors]);
                return redirect()->action('DishController@index');
            }
            $dishes_db = new Dish;
            $dishes_db['user_id'] = Auth::user()->id;
        }
        $dishes_db ['dish_name'] = $request->dish_name;
        if ($request->filled('white') && $request->white == '1') {
            $dishes_db['white'] = 1;
        } else {
            $dishes_db['white'] = 0;
        }
        if ($request->filled('pink') && $request->pink == '1') {
            $dishes_db['pink'] = 1;
        } else {
            $dishes_db['pink'] = 0;
        }
        if ($request->filled('red') && $request->red == '1') {
            $dishes_db['red'] = 1;
        } else {
            $dishes_db['red'] = 0;
        }
        if ($request->filled('green') && $request->green == '1') {
            $dishes_db['green'] = 1;
        } else {
            $dishes_db['green'] = 0;
        }
        if ($request->filled('yellowish_green') && $request->yellowish_green == '1') {
            $dishes_db['yellowish_green'] = 1;
        } else {
            $dishes_db['yellowish_green'] = 0;
        }
        if ($request->filled('yellow') && $request->yellow == '1') {
            $dishes_db['yellow'] = 1;
        } else {
            $dishes_db['yellow'] = 0;
        }
        if ($request->filled('beige') && $request->beige == '1') {
            $dishes_db['beige'] = 1;
        } else {
            $dishes_db['beige'] = 0;
        }
        if ($request->filled('orange') && $request->orange == '1') {
            $dishes_db['orange'] = 1;
        } else {
            $dishes_db['orange'] = 0;
        }
        if ($request->filled('brown') && $request->brown == '1') {
            $dishes_db['brown'] = 1;
        } else {
            $dishes_db['brown'] = 0;
        }
        if ($request->filled('purple') && $request->purple == '1') {
            $dishes_db['purple'] = 1;
        } else {
            $dishes_db['purple'] = 0;
        }
        if ($request->filled('black') && $request->black == '1') {
            $dishes_db['black'] = 1;
        } else {
            $dishes_db['black'] = 0;
        }
        $dishes_db['seasoning'] = $request->seasoning;
        if ($request->filled('memo')) {
            $dishes_db['memo'] = $request->memo;
        } else {
            $dishes_db['memo'] = '';
        }
        $dishes_db['public_private'] = $request->public_private;

        DB::beginTransaction();
        try {
            $dishes_db-> save();
            if ($request->filled('dishes_id')) {
                //更新
                Manage_ingredient::where('dishes_id', $request->dishes_id)->delete();
            }  
            foreach ($request->ingredients as $v) {
                $manage_ingredients_db = new Manage_ingredient;
                $manage_ingredients_db['dishes_id'] = $dishes_db->id;
                $manage_ingredients_db['ingredients_id'] = $v;
                $manage_ingredients_db->save();
            }
            DB::commit();
            $errors[] = 'お料理の登録をしました';
        } catch (\Exception $e) {
            DB::rollback();
            $errors[] = 'データ更新でエラーが発生しました。管理者にお問い合わせ下さい';
        }

        session(['errors' => $errors]);
        return redirect()->action('DishController@index');
    }

}
