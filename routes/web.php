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
    Route::group(['prefix'=>'trip'], function () {
        Route::group(['prefix'=>'follow'], function () {
            Route::get('/', 'User\TripFollowController@index');
            Route::get('/follow/{id}','User\TripFollowController@flow');
        });
        Route::group(['prefix'=>'join'], function () {
            Route::get('/', 'User\TripFollowController@index');
        });
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
