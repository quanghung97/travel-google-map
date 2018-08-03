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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/dashboard', 'HomeController@index')->name('dashboard');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/auth/google', 'SocialAuthController@redirectToProvider')->name('loginWithGoogle');
Route::get('/auth/google/callback', 'SocialAuthController@handleProviderCallback');

Route::group(['prefix'=>'user', 'middleware'=>'auth'], function () {
    Route::group(['prefix'=>'userProfile'], function () {
        Route::resource('profile', 'User\ProfileController')->only(['show', 'update']);
    });
    //@show Trip in TripController filter follow, owner, join, verify
    //@update Trip in TripController filter follow, owner, join, verify
    Route::resource('trip', 'User\TripController');
    Route::resource('comment', 'CommentController', ['only'=>['update','destroy']]);
    Route::group(['prefix'=>'trip'], function () {
        Route::group(['prefix'=>'follow'], function () {
            Route::get('/index/{id}', 'User\TripFollowController@index');
            Route::get('/follow/{id}', 'User\TripFollowController@flow');
            Route::get('/unfollow/{id}', 'User\TripFollowController@unflow');
        });
        Route::group(['prefix'=>'join'], function () {
            Route::get('/index/{id}', 'User\TripJoinController@index');
            Route::get('/unjoin/{id}', 'User\TripJoinController@unjoin');
            Route::get('/out/{a}/{b}', 'User\TripJoinController@out');
        });
        Route::group(['prefix'=>'verify'], function () {
            Route::get('/verify/{id}', 'User\TripVerifyController@verify');
            Route::get('/unverify/{id}', 'User\TripVerifyController@unverify');
            Route::get('/deny/{a}/{b}', 'User\TripVerifyController@deny');
            Route::get('/accept/{a}/{b}', 'User\TripVerifyController@accept');
        });
        Route::post('comment/create/{trip}', 'CommentController@addTripComment')->name('tripcomment.store');
        Route::post('reply/create/{comment}', 'CommentController@addReplyComment')->name('replycomment.store');
    });
    Route::group(['prefix'=>'home'], function () {
        Route::get('newest', 'User\Home\ListController@newest');
        Route::get('hotest', 'User\Home\ListController@hotest');
        Route::get('newestmem', 'User\Home\ListController@newestmem');
    });
});

Route::group(['prefix'=>'admin'], function () {
    Route::group(['prefix'=>'admin', 'middleware'=>['role:admin', 'auth']], function () {
        Route::resource('permission', 'Admin\\PermissionController');
        Route::resource('role', 'Admin\\RoleController');
        Route::resource('user', 'Admin\\UserController');
    });
    Route::group(['prefix'=>'sub-admin', 'middleware'=>['role:sub_admin', 'auth']], function () {
    });
});
