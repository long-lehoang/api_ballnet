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
use App\Http\Controllers\MatchController;
use App\Http\Controllers\TypeSportController;
use App\Http\Controllers\MatchInvitationController;
use App\Http\Controllers\MatchJoiningController;
use App\Http\Controllers\StadiumController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\SuggestionController;
use App\Http\Controllers\ResetPasswordController;


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
Route::post('reset-password/', [ResetPasswordController::class, 'sendMail']);
Route::put('reset-password/{token}', [ResetPasswordController::class, 'reset']);

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
        Route::get('/match/{id}',[ProfileController::class, 'myMatch']);
        Route::get('/post/{id}', [ProfileController::class, 'myPost']);
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
        Route::get('/top', [FriendRequestController::class, 'nearRequest']);
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
    Route::get('/team_sport/{sport}',[TeamController::class, 'teamWithSport']);
    Route::get('/myteam', [TeamController::class, 'myTeams']);
    Route::get('/myteam_captain', [TeamController::class, 'myTeamWithCaptain']);
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
        Route::post('/sport', [TeamController::class, 'setSport']);
        Route::get('/matchs', [TeamController::class, 'getMatch']);
        Route::get('/matchs/invitation', [TeamController::class, 'getMatchInvitation']);
    });

    Route::group(['prefix' => '/team_requests'], function(){
        Route::get('/{teamId}', [TeamRequestController::class, 'requestJoinTeam']);
        Route::get('/', [TeamRequestController::class, 'myInvitation']);
        Route::post('/', [TeamRequestController::class, 'join']);
        Route::post('/invite', [TeamRequestController::class, 'invite']);
        Route::post('/{id}/deny', [TeamRequestController::class, 'cancel']);
        Route::post('/{id}/approve', [TeamRequestController::class, 'approve']);
    });
    
    Route::apiResource('/stadiums', StadiumController::class);
    Route::group(['prefix' => '/stadiums/{id}'], function(){
        Route::post('/avatar', [StadiumController::class, 'setAvatar']);
        Route::post('/extension', [StadiumController::class, 'setExtension']);
    });
    Route::get('/stadiums/owner/{id}', [StadiumController::class, 'myStadium']);
    Route::get('/stadiums/sport/{sport}', [StadiumController::class, 'getStadiumBySport']);

    Route::apiResource('matchs', MatchController::class);
    Route::group(['prefix' => '/matchs'], function(){
        Route::put('/{id}/leave', [MatchController::class, 'leave']); //team leave from match
        Route::post('/{id}/invite', [MatchController::class, 'invite']);
        Route::get('/{id}/member/{team_id}', [MatchController::class, 'memberOfTeam']);
        Route::get('/{id}/request/{team_id}', [MatchController::class, 'requestOfTeam']);
        Route::delete('/{id}/team', [MatchController::class, 'removeTeam']);
        Route::get('/{id}/team/request', [MatchController::class, 'getTeamRequestOfMatch']);
        Route::post('/{id}/review', [MatchController::class, 'review']);
        Route::get('/{id}/review', [MatchController::class, 'getToReview']);
    });

    Route::group(['prefix' => '/match_invitations/{teamId}'], function(){
        Route::get('/', [MatchInvitationController::class, 'index']); //invitation of team
        Route::post('/{id}/accept', [MatchInvitationController::class, 'accept']);
        Route::post('/{id}/cancel', [MatchInvitationController::class, 'cancel']);
        Route::post('/request', [MatchInvitationController::class, 'request']);
    });

    Route::group(['prefix' => '/match_joinings'], function(){
        Route::get('/invitation', [MatchJoiningController::class, 'invitation']);
        Route::get('/match/{id}', [MatchJoiningController::class, 'show']);
        Route::post('/', [MatchJoiningController::class, 'store']);
        Route::delete('/{id}', [MatchJoiningController::class, 'destroy']);
        Route::put('/{id}', [MatchJoiningController::class, 'update']);  
        Route::get('/friend_not_in_match/{match_id}', [MatchJoiningController::class, 'getFriendNotInMatch']);
    });

    //booking
    Route::group(['prefix' => '/booking'], function(){
        Route::post('/{id}/review', [BookingController::class, 'review']);
        Route::get('/{id}/review', [BookingController::class, 'getToReview']);
        Route::post('/', [BookingController::class, 'store']);
    });

    //sport category
    Route::get('/sport_category', [SportCategoryController::class, 'index']);
    Route::get('/sport_category/{name}', [SportCategoryController::class, 'show']);
    Route::get('/type_sport', [TypeSportController::class, 'index']);

    //suggestion
    Route::group(['prefix' => '/suggestion'], function(){
        Route::get('/friend', [SuggestionController::class, 'friend']);
        Route::get('/stadium', [SuggestionController::class, 'stadium']);
        Route::get('/match', [SuggestionController::class, 'match']);
    });
}); 

Route::get('/username/{username}', [AuthController::class, 'checkUsername']);
