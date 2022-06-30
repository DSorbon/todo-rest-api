<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'auth', 'controller'=> AuthController::class], function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::post('logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum']);


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::group(['controller' => TaskController::class, 'prefix' => 'tasks'], function () {
        Route::get('', 'index');
        Route::post('', 'store');
        Route::get('/{id}', 'show');
        Route::put('/{id}', 'update');
        Route::delete('/{id}', 'destroy');
    });
});