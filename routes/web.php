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

Auth::routes();

//,'middleware' => 'auth'
Route::group(['prefix' => '/api/v1','middleware' => 'auth'], function() {
    Route::get('/getAuthorized', 'UserController@getAuthorized')->name('getAuthorized');
    Route::get('/getCategories', 'ProductController@getCategories')->name('getCategories');
    Route::post('/addCategory', 'ProductController@addCategory')->name('addCategory');
    Route::post('/deleteProduct', 'ProductController@deleteProduct')->name('deleteProduct');
    Route::get('/showProducts', 'ProductController@showProducts')->name('showProducts');
    Route::get('/searchItems', 'ProductController@searchItems')->name('searchItems');
    Route::post('/insertProduct', 'ProductController@insertProduct')->name('insertProduct');
    Route::get('/getPageEditProduct/{id}', 'ProductController@getPageEditProduct')->name('getPageEditProduct');
    Route::post('/editProduct', 'ProductController@editProduct')->name('editProduct');
    Route::get('/getProduct/{id}', 'ProductController@getProduct')->name('getProduct');
});

Route::get('/{all}', 'HomeController@index')->where(['all' => '.*']);;

