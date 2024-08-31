<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function() {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login-post');

    Route::middleware('auth:sanctum')->group(function() {
        Route::get('/delete/{id}', [UploadController::class, 'deleteView'])->name('delete');
        Route::get('/upload', [UploadController::class, 'uploadView'])->name('upload');
        Route::post('/upload', [UploadController::class, 'upload_post'])->name('upload-post');
    });
});
Route::get('/{id}', [UploadController::class, 'show'])->name("getUpload");

