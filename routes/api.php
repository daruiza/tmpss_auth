<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;

use App\Http\Controllers\Api\AuthController;

Route::group(['prefix' => 'auth', ], function () {
    Route::post('clientlogin', [AuthController::class,'clientLogin']);
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('clientlogout', [AuthController::class,'clientLogout']);
        Route::get('user', [AuthController::class,'user']);
    });


    Route::get('auth/redirect', function () {
        return Socialite::driver('github')->redirect();
    });
     
    Route::get('auth/callback', function () {
        $user = Socialite::driver('github')->user();
     
        // $user->token
    });
});



Route::get('hello', function(){
    return response()->json([
        'message' => 'Hello'], 404);
});

Route::fallback(function(){
    return response()->json([
        'message' => 'Page Not Found.'], 404);
});