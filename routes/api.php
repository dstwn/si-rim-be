<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\Group\GroupController;
use App\Http\Controllers\Api\Group\UserGroupController;

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
Route::group(['prefix' => 'v1'], function () {
    Route::prefix('auth')->group(function () {
        Route::post('/register', [RegisterController::class, 'register'])->name('auth.register');
        Route::post('/login', [LoginController::class, 'login'])->name('auth.login');
        Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::get('/profile', [AuthController::class, 'profile'])->name('auth.profile');
    });
    Route::group(['middleware' => ['role:Super-Admin']], function () {
        Route::prefix('admin')->group(function () {
            Route::resource('users', UserController::class, )->only(['index', 'store', 'update', 'destroy', 'show']);
            Route::resource('groups', GroupController::class)->only(['index', 'store', 'update', 'destroy', 'show']);
            Route::resource('users-groups', UserGroupController::class)->only(['index', 'store']);
        });
    });
});
