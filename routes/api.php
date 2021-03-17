<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FriendController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/signup', [AuthController::class, 'signup']);
Route::group(['middleware' => 'auth:api'], function() {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/friends', [FriendController::class, 'index']);
    Route::apiResource('profiles', InfoController::class);

    Route::apiResource('posts', PostController::class);
    Route::group(['prefix' => '/posts'], function() {
        Route::post('/{id}/like', [PostController::class, 'like']);
        Route::delete('/{id}/like', [PostController::class, 'unLike']);
        Route::post('/{id}/comment', [PostController::class, 'comment']);
        Route::delete('/{id}/comment', [PostController::class, 'unComment']);
        Route::post('/{id}/share', [PostController::class, 'share']);
        Route::delete('/{id}/share', [PostController::class, 'unShare']);
    });
});