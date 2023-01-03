<?php

use Illuminate\Http\Request;

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

Route::post('messages', 'MessageController@index');

Route::post('registerAdmin', 'Auth\RegisterController@create');

Route::post('login', 'API\Auth\LoginController@login');

Route::post('register', 'API\Auth\RegisterController@register');

Route::post('resetPass','API\Auth\ForgotPasswordController@sendResetLinkEmail');
Route::post('resetExpiredPassword',
'API\UserController@resetExpiredPassword');


Route::group(['middleware' => 'auth:user-api'], function(){

    // logout
    Route::post('logout', 'API\Auth\LoginController@logout');

    // USERS
    Route::post('updateUser', 'API\UserController@updateUser');
    Route::post('getStatus', 'API\UserController@getStatus');
    Route::post('getUsersRanking', 'API\UserController@getUsersRanking');
    Route::post('getUserAchievements', 'API\UserController@getUserAchievements');
    Route::post('getUserInfo', 'API\UserController@getUserInfo');
    Route::post('sendUserSituation', 'API\UserController@sendUserSituation');
    Route::post('getUserSituation', 'API\UserController@getUserSituation');
    Route::post('changePassword', 'API\UserController@changePassword');


    // CONTENT
    Route::get('getLessonContent/{id}/{spent?}', 'API\ContentController@getLessonContent');
    Route::get('getLessonScreen/{id}', 'API\ContentController@getLessonScreen');
    Route::post('getTopics', 'API\ContentController@getTopics');
    Route::post('getLessons', 'API\ContentController@getLessons');
    Route::post('getAchievements', 'API\ContentController@getAchievements');
    Route::get('getAchievement/{achievement_id}', 'API\UserController@getAchievement');


    // GAME
    Route::get('getGamesinLesson/{id}/{repeat?}', 'API\GameController@getGamesinLesson');
    Route::get('getChallengeTopic/{id}', 'API\GameController@getChallengeTopic');
    Route::post('getGames', 'API\GameController@getGames');
    Route::post('gameResponse', 'API\GameController@gameResponse');
    Route::post('gamesFinished', 'API\GameController@gamesFinished');
    Route::get('isGameCompleted/{game_id}', 'API\GameController@isGameCompleted');
    Route::get('getGame/{game_id}', 'API\GameController@getGame');
    Route::get('resetGamesInLesson/{lesson_id}', 'API\GameController@resetGamesInLesson');
    Route::get('resetChallengeInTopic/{topic_id}', 'API\GameController@resetChallengeInTopic');
    Route::post('userAchievementsPoints', 'API\GameController@userAchievementsPoints');

    //Route::post('setAchievement', 'API\UserController@setAchievement');

    //Route::get('getLiveCases/{topic_id}', 'API\UserController@getLiveCases');
});



