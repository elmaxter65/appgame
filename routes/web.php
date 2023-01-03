<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/password-changed', function () {
    return view('password-changed');
});

Route::post('password/post_expired', 'ExpiredPasswordController@postExpired')->name('password.post_expired');

Auth::routes(['register' => false]);

Route::get('userApp/password/reset/{token}/{email}', 'API\Auth\ResetPasswordController@showResetForm')->name('userApp.password.reset');

Route::get('user/password/reset/{token}/{email}', 'Auth\ResetPasswordController@showResetForm')->name('user.password.reset');

Route::post('userApp/password/reset', 'API\Auth\ResetPasswordController@updatePass')->name('userApp.password.update');

Route::post('user/password/reset', 'Auth\ResetPasswordController@updatePass')->name('user.password.update');

Route::group(['middleware' => ['auth:web']], function () {


    Route::get('/', 'HomeController@index');

    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('/topic', 'TopicController@index')->name('topic');
    Route::get('/topic/{id}', 'TopicController@getJson');
    Route::post('/topic', 'TopicController@create');
    Route::put('/topic/{id}', 'TopicController@update')->name('topic.update');
    Route::delete('/topic', 'TopicController@delete');

    Route::get('/lesson', 'LessonController@index')->name('lesson');
    Route::get('/lesson/{id}', 'LessonController@getJson');
    Route::get('/lesson-topic', 'LessonController@getLessons')->name('lesson.get');
    Route::post('/lesson', 'LessonController@create');
    Route::put('/lesson/{id}', 'LessonController@update')->name('lesson.update');
    Route::delete('/lesson', 'LessonController@delete');


    Route::get('/topic-content', 'TopicContentController@index')->name('topic.content');
    Route::get('/topic-content/{id}', 'TopicContentController@getJson');
    Route::post('/topic-content', 'TopicContentController@create')->name('topic.content.create');
    Route::put('/topic-content/{id}', 'TopicContentController@update')->name('topic.content.update');
    Route::delete('/topic-content', 'TopicContentController@delete');


    Route::get('/game', 'GameController@index')->name('game');
    Route::get('/game/{id}', 'GameController@getJson');
    Route::post('/game', 'GameController@create');
    Route::post('/game-add-content', 'GameController@addContent')->name('game.add.content');
    Route::get('/game-show-content/{id}', 'GameController@showContent')->name('game.show.content');;
    Route::put('/game/{id}', 'GameController@update')->name('game.update');
    Route::delete('/game', 'GameController@delete');


    Route::get('/liveCase', 'LiveCaseController@index')->name('live.case');
    Route::get('/liveCase/{id}', 'LiveCaseController@getJson');
    Route::post('/liveCase', 'LiveCaseController@create');
    Route::post('/liveCase-add-content', 'LiveCaseController@addContent')->name('live.case.add.content');
    Route::get('/liveCase-show-content/{id}', 'LiveCaseController@showContent')->name('live.case.show.content');;
    Route::put('/liveCase/{id}', 'LiveCaseController@update')->name('live-case.update');
    Route::delete('/liveCase', 'LiveCaseController@delete');


    Route::get('/user-app', 'UserAppController@index')->name('user.app');
    Route::post('/user-app', 'UserAppController@search')->name('user.app.search');
    Route::get('/user-app/{id}', 'UserAppController@getJson');
    Route::delete('/user-app', 'UserAppController@delete')->name('user.app.delete');

    Route::get('/notification', 'NotificationController@index')->name('notification');
    Route::get('/notification/{id}', 'NotificationController@getJson');
    Route::post('/notification', 'NotificationController@create');
    Route::put('/notification/{id}', 'NotificationController@update')->name('notification.update');
    Route::delete('/notification', 'NotificationController@delete');
    Route::post('/notification-send', 'NotificationController@send')->name('notification.send');


    Route::get('/settings', 'SettingsController@index')->name('settings');
    Route::get('/settings/status/{id}', 'SettingsController@getJsonStatus');
    Route::put('/settings/status/{id}', 'SettingsController@updateStatus')->name('settings.status.update');

    Route::get('/changePass', function () {
        return view('changepass.index');
    })->name('change.pass');

    Route::get('/statistics', function () {
        return view('statistics.index');
    })->name('statistics');

    Route::post('/downloadExcel', 'ExcelController@downloadExcel')->name('dowload.excel');
    Route::post('/emailExcel', 'ExcelController@emailExcel')->name('email.excel');

    Route::post('/changePass', 'UserController@changePass')->name('update.pass');

    Route::get('/logout', 'UserController@logout')->name('user.logout');

});


Route::get('/user-app/userConfirm/{id}', 'UserAppController@confirm')->name('userConfirm');





