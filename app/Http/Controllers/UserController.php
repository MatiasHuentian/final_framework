<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    //regirstro de usuario
    public function register(Request $request){
        echo "registro";
        die();
    }

    //login de usuario

    public function login(Request $request){
        echo "login";
        die();
    }
}
