<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\FriendRequestController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\SportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TeamRequestController;
use App\Http\Controllers\SportCategoryController;
use App\Http\Controllers\VerificationController;


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
Route::get('email/verify/{id}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::get('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

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
    Route::group(['prefix' => '/friends'], function(){
        Route::get('/', [FriendController::class, 'index']);
        Route::get('/{username}/count', [FriendController::class, 'count']);
        Route::get('/{username}', [FriendController::class, 'getFriendOfUser']);
        Route::delete('/{username}', [FriendController::class, 'delete']);
    });

    //friend_request
    Route::group(['prefix' => '/friend_requests'], function(){
        Route::get('/', [FriendRequestController::class, 'index']);
        Route::post('/', [FriendRequestController::class, 'store']);
        Route::post('/{id}/accept', [FriendRequestController::class, 'acceptRequest']);
        Route::post('/{id}/deny', [FriendRequestController::class, 'delete']);
    });

    Route::get('/people', [PeopleController::class, 'index']);
    
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
    Route::get('/mypost', [PostController::class, 'getMyPost']);
    
    //sport
    Route::group(['prefix' => '/sports'], function() {
        Route::get('/{username}', [SportController::class, 'show']);
        Route::get('/{username}/main', [SportController::class, 'getMainSport']);
    });
    
    //notification
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/read', [NotificationController::class, 'readAll']);
    Route::delete('/notifications/{id}', [NotificationController::class, 'delete']);

    //team
    Route::apiResource('teams', TeamController::class);
    Route::get('/myteam', [TeamController::class, 'myTeams']);
    Route::group(['prefix' => '/teams/{id}'], function(){
        Route::delete('/leave', [TeamController::class, 'leave']);
        Route::get('/feed', [TeamController::class, 'getPosts']);
        Route::get('/permission', [TeamController::class, 'getPermission']);
        Route::get('/admin', [TeamController::class, 'getAdmin']);
        Route::get('/invite', [TeamController::class, 'getFriendToInvite']);
        Route::get('/member', [TeamController::class, 'getMember']);
        Route::post('/location', [TeamController::class, 'setLocation']);
        Route::post('/admin', [TeamController::class, 'setAdmin']);
        Route::post('/overview', [TeamController::class, 'setOverview']);
        Route::post('/avatar', [TeamController::class, 'setAvatar']);
        Route::post('/cover', [TeamController::class, 'setCover']);
        Route::post('/kick', [TeamController::class, 'kickMember']);
        Route::post('/captain', [TeamController::class, 'setCaptain']);
    });

    Route::group(['prefix' => '/team_requests'], function(){
        Route::get('/{teamId}', [TeamRequestController::class, 'requestJoinTeam']);
        Route::get('/', [TeamRequestController::class, 'myInvitation']);
        Route::post('/', [TeamRequestController::class, 'join']);
        Route::post('/invite', [TeamRequestController::class, 'invite']);
        Route::post('/{id}/deny', [TeamRequestController::class, 'cancel']);
        Route::post('/{id}/approve', [TeamRequestController::class, 'approve']);
    });
    
    Route::group(['prefix' => '/stadiums'], function(){
        Route::get('/', [StadiumController::class, 'index']);
    });

    //sport category
    Route::get('/sport_category', [SportCategoryController::class, 'index']);
}); 

Route::get('/username/{username}', [AuthController::class, 'checkUsername']);
