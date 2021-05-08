<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DishController extends Controller
{
    function index() {
        return view('dish');
    }
    
    function post(Request $request) {
        return redirect()->action('DishController@index');
    }

}
