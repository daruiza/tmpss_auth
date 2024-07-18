<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Http;

use App\Http\Controllers\Api\AuthController;

Route::group(['prefix' => 'auth', ], function () {
    Route::post('clientlogin', [AuthController::class,'clientLogin']);
    Route::post('passwordlogin', [AuthController::class,'passwordLogin']);
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('clientlogout', [AuthController::class,'clientLogout']);
        Route::get('user', [AuthController::class,'user']);
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