<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', ['as' => 'home', 'uses' => 'PostController@index']);
Route::get('/tags/{tag}', ['as' => 'search.tag', 'uses' => 'PostController@searchTag']);
Route::get('/view/{post_id}', ['as' => 'post.view', 'uses' => 'PostController@view']);
Route::get('/play',['as'=>'play','uses'=>'IndexController@play']);
//后台
Route::group(['namespace' => 'Backend', 'prefix' => 'admin'], function () {
    //需要登录的Route
    Route::group(['middleware' => 'auth'], function () {
        Route::get('/', ['as' => 'admin.index', 'uses' => 'AdminController@index']);
        Route::post('upload',['as'=>'admin.upload','uses'=>'AdminController@upload']);
        Route::resource('post', 'PostController', ['except' => ['update', 'destroy']]);
        Route::get('post/delete/{post_id}', ['as' => 'admin.post.delete', 'uses' => 'PostController@delete']);
        Route::resource('links', 'LinksController', ['except' => ['update', 'destroy']]);
        Route::get('links/delete/{id}', ['as' => 'admin.links.delete', 'uses' => 'LinksController@delete']);
        Route::get('logout', ['as' => 'admin.logout', 'uses' => 'AdminController@logout']);
    });
    Route::get('login', ['as' => 'admin.login', 'uses' => 'AdminController@login']);
    Route::post('login', ['as' => 'admin.login', 'uses' => 'AdminController@postLogin']);
});
