<?php

use App\Http\Controllers\TokenController;
use App\Http\Controllers\UploadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');

    Route::prefix("/token")->group(function() {
        Route::post('/new', [TokenController::class, 'newToken']);
    });

    Route::post('/upload', [UploadController::class, 'upload']);
});
