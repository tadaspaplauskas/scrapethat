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


Route::get('snapshots/{id}/restore', 'SnapshotController@restore')->name('snapshots.restore');
Route::get('snapshots/{snapshot}/delete', 'SnapshotController@delete')->name('snapshots.delete');
Route::resource('snapshots', 'SnapshotController');

Route::resource('filters', 'FilterController');

Route::get('about', 'PagesController@about')->name('about');
Route::get('/', 'PagesController@index')->name('home');
