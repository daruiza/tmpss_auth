<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('login', function (Request $request) {
    return 'Login';
});

Route::get('user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('hello', function (Request $request) {
    return response()->json([
        '$request'=>$request,
        '$headers'=>$request->header('accept')
    ]);
});
