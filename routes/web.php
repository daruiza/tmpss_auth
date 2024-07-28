<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Api\AuthController;

Route::get('/', function () {
    return view('welcome');
});


Route::group(['prefix' => 'auth', ], function () {

    Route::get('redirect/{driver}', [AuthController::class,'redirect']);

    //Route::get('callback/{driver}', [AuthController::class,'callback']);
    Route::get('callback/{driver}', [AuthController::class,'callback']);


    Route::get('callback-github', function () {
        $githubUser  = Socialite::driver('github')->user();
        return response()->json(['data'=>[
            'githubUser'=>$githubUser
        ]]);
    });

    //TODO: realizar e logOut de Socialite

});

Route::fallback(function(){
    return response()->json([
        'message' => 'Page Not Found.'], 404);
});


