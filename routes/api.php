<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;

Route::group(['prefix' => 'auth', ], function () {
    // Route::post('login', 'Api\AuthController@login')->name('login');
    Route::get('login', [AuthController::class,'login'])->name('login');
    Route::group(['middleware' => 'authvalid'], function () {
        Route::get('logout', 'Api\AuthController@logout');
        Route::get('user', 'Api\AuthController@user');
    });
});

Route::get('user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::get('hello', function (Request $request) {
    return response()->json([
        '$request'=>$request,
        '$headers'=>$request->header('accept')
    ]);
});
