<?php

use Illuminate\Http\Request;
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

Route::prefix('auth')->group(function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::get('refresh', 'AuthController@refresh');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('user', 'AuthController@user');
        Route::post('logout', 'AuthController@logout');
    });
});

Route::group(['middleware' => 'auth:api'], function(){
    // Users
    Route::get('users', 'UserController@index')->middleware('isAdmin');
    Route::get('users/{id}', 'UserController@show')->middleware('isAdminOrSelf');

    Route::get('getGroups', 'StudentController@group');
    Route::get('showGroup/{id}', 'StudentController@students');
    Route::post('student/save', 'StudentController@edit');
    Route::post('student/create', 'StudentController@create');
    Route::get('student/getPosts/{id}', 'StudentController@getPosts');
    Route::post('student/delete', 'StudentController@delete');

    Route::group(['prefix' => 'group'], function (){
        Route::get('getCurators','GroupController@groups');
        Route::post('create','GroupController@create');
        Route::post('delete', 'GroupController@delete');
    });
});


Route::group(['prefix' => 'vk'], function (){
    Route::get('auth', 'VkController@authVk');
    Route::get('accessToken', 'VkController@getCode');
    Route::get('collectData/{idGroup}', 'VkController@collectData')->middleware('checkVkToken');
    Route::post('saveLink', 'VkController@saveLink')->middleware('checkVkToken');
});

Route::get('test', function (){
   echo 'asdasd';
})->middleware('checkVkToken');
