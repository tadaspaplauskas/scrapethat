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
// override POST route to logout
Route::any('logout', 'Auth\LoginController@logout');

Route::get('snapshots/{id}/restore', 'SnapshotController@restore')->name('snapshots.restore');

Route::get('snapshots/{snapshot}/delete', 'SnapshotController@delete')->name('snapshots.delete');

Route::post('snapshots/{snapshot}/stop', 'SnapshotController@stop')->name('snapshots.stop');

Route::post('snapshots/{snapshot}/retry', 'SnapshotController@retry')->name('snapshots.retry');

Route::any('snapshots/{snapshot}/query', 'SnapshotController@query')->name('snapshots.query');

Route::resource('snapshots', 'SnapshotController');

Route::resource('snapshots/{snapshot}/variables', 'VariableController');

Route::get('about', 'PagesController@about')->name('about');

Route::get('/', 'PagesController@index')->name('home');
