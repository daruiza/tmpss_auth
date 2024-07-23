<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Api\AuthController;

Route::get('/', function () {
    return view('welcome');
});


Route::group(['prefix' => 'auth', ], function () {
    Route::get('redirect/{driver}', function (string $driver) {
        return Socialite::driver($driver)->redirect();
    });

    Route::get('githubcallback', [AuthController::class,'githubCallback']);

});

Route::fallback(function(){
    return response()->json([
        'message' => 'Page Not Found.'], 404);
});


