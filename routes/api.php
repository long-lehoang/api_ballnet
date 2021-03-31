<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\SportController;


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

Route::post('/login', [AuthController::class, 'login']);
Route::post('/signup', [AuthController::class, 'signup']);
Route::group(['middleware' => 'auth:api'], function() {
    //user
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/delete', [AuthController::class, 'delete']);
    Route::post('/password', [AuthController::class, 'changePassword']);
    Route::get('/user/{username}', [AuthController::class, 'show']);
    //profile
    Route::group(['prefix' => '/profiles'], function(){
        Route::get('/{username}', [ProfileController::class, 'show']);
        Route::post('/name', [ProfileController::class, 'updateName']);
        Route::post('/username', [ProfileController::class, 'updateUsername']);
        Route::post('/address', [ProfileController::class, 'updateAddress']);
        Route::post('/overview', [ProfileController::class, 'updateOverview']);
        Route::post('/email', [ProfileController::class, 'updateEmail']);
        Route::post('/phone', [ProfileController::class, 'updatePhone']);
        Route::post('/birthday', [ProfileController::class, 'updateBirthday']);
        Route::post('/avatar', [ProfileController::class, 'updateAvatar']);
        Route::post('/cover', [ProfileController::class, 'updateCover']);
    });
    
    //friend
    Route::get('/friends', [FriendController::class, 'index']);
    Route::get('/friends/{username}/count', [FriendController::class, 'count']);
    
    //follow
    Route::get('/follows/{username}/count', [FollowController::class, 'count']);
    
    //post
    Route::apiResource('posts', PostController::class);
    Route::group(['prefix' => '/posts'], function() {
        Route::post('/{id}/like', [PostController::class, 'like']);
        Route::delete('/{id}/like', [PostController::class, 'unLike']);
        Route::post('/{id}/comment', [PostController::class, 'comment']);
        Route::delete('/{id}/comment', [PostController::class, 'unComment']);
        Route::post('/{id}/share', [PostController::class, 'share']);
        Route::delete('/{id}/share', [PostController::class, 'unShare']);

        Route::post('/{id}', [PostController::class, 'update']);
        Route::get('/{id}/comment', [PostController::class, 'getComments']);
    });
    
    //sport
    Route::group(['prefix' => '/sports'], function() {
        Route::get('/{username}', [SportController::class, 'show']);
        Route::get('/{username}/main', [SportController::class, 'getMainSport']);
    });
    
});

Route::get('/username/{username}', [AuthController::class, 'checkUsername']);