<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Api\AuthController;

Route::get('/', function () {
    return view('welcome');
});


Route::group(['prefix' => 'auth', ], function () {

    Route::get('redirect/{driver}', [AuthController::class,'redirect']);
    Route::get('callback_github', [AuthController::class,'callback_github']);

    //TODO: realizar e logOut de Socialite

});

//TODO: realaizar tambien el login via sanctum

Route::fallback(function(){
    return response()->json([
        'message' => 'Page Not Found.'], 404);
});


