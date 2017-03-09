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

Route::get('/home', 'MemoController@index');
Route::get('/', 'MemoController@index');

Route::resource('memo', 'MemoController');

Route::post('/memo/create', 'MemoController@create');
Route::post('/memo/update', 'MemoController@update');
Route::post('/memo/remove', 'MemoController@remove');

Route::post('/memo/error', function()
{
    return view('memo/error');
});
