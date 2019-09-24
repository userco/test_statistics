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

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/import', 'ImportController@create')->name('import');
Route::post('/import', 'ImportController@store')->name('import');
Route::get('/download', 'DownloadController@index')->name('download');
//Route::post('/download', 'DownloadController@create')->name('download');
Route::get('/result', 'ResultController@create')->name('result');
Route::post('/result', 'ResultController@store')->name('result');
Route::get('/search', 'SearchController@create')->name('search');
Route::post('/search', 'SearchController@store')->name('search');