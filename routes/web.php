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

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', 'PagesController@index')->name('index');

Route::get('snapshots/{id}/restore', 'SnapshotController@restore')->name('snapshots.restore');
Route::resource('snapshots', 'SnapshotController');

Route::resource('snapshots/{snapshot}/filters', 'FilterController', ['only' => ['store']]);
