<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group([
    'middleware' => 'api', 'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/registration', [UserController::class, 'store'])->name('registration');
});

Route::group(['namespace' => 'App\Http\Controllers\Post', 'middleware' => 'jwt.auth'], function () {
    Route::get('/posts', IndexController::class);

    Route::get('/posts/create', CreateController::class);

    Route::get('/posts/{post}', ShowController::class);

    Route::post('/posts', StoreController::class);

    Route::get('/posts/{post}/edit', EditController::class);

    Route::patch('/posts/{post} ', UpdateController::class);

    Route::delete('/posts/{post}', DestroyController::class);

});
