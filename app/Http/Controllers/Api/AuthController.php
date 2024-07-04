<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    
    public function login(Request $request)
    {
        return response()->json([
            'data'=>'login',
            // '$request'=>$request,
            'user'=>Auth::user(),
            'check'=>Auth::check()
        ]);
    }

    public function logout(Request $request){}
    

    public function user(Request $request){
        return response()->json([
            'data'=>'login',
            'user'=>Auth::user(),
            'check'=>Auth::check()
        ]);
    }
    
}
