<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;

Route::group(['prefix' => 'auth', ], function () {
    Route::post('clientlogin', [AuthController::class,'clientLogin']);
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('logout', [AuthController::class,'logout']);
        Route::get('user', [AuthController::class,'user']);
    });
});

Route::fallback(function(){
    return response()->json([
        'message' => 'Page Not Found.'], 404);
});