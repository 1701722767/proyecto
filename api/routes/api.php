<?php

use App\Http\Controllers\SiteController;
use App\Http\Controllers\UserController;
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

Route::group([
    'middleware' => 'api',
    'prefix'     => 'v1/auth',

], function ($router) {
    Route::post('login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');
    Route::post('logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
    Route::post('refresh', [\App\Http\Controllers\AuthController::class, 'refresh'])->name('refresh');
    Route::get('me', [\App\Http\Controllers\AuthController::class, 'me'])->name('me');
});

Route::group([
    'middleware' => 'api',
    'prefix'     => 'v1/users',

], function ($router) {
    Route::post('', [UserController::class, 'create'])
        ->name('create_user');
});

Route::group([
    'middleware' => 'api',
    'prefix'     => 'v1/sites',

], function ($router) {
    Route::post('', [SiteController::class, 'createSiteAndUser'])
        ->name('create_site');
});
