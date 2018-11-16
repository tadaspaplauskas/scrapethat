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

Route::get('snapshots/{snapshot}/delete', 'SnapshotController@confirmDelete')->name('snapshots.delete.confirm');

Route::get('snapshots/{snapshot}/refresh', 'SnapshotController@confirmRefresh')->name('snapshots.refresh.confirm');

Route::post('snapshots/{snapshot}/refresh', 'SnapshotController@refresh')->name('snapshots.refresh');

Route::post('snapshots/{snapshot}/stop', 'SnapshotController@stop')->name('snapshots.stop');

Route::post('snapshots/{snapshot}/retry', 'SnapshotController@retry')->name('snapshots.retry');

Route::any('snapshots/{snapshot}/query', 'SnapshotController@query')->name('snapshots.query');

Route::resource('snapshots', 'SnapshotController');

Route::resource('snapshots/{snapshot}/variables', 'VariableController')
    ->only(['store']);

Route::resource('variables', 'VariableController')
    ->only(['edit', 'update', 'destroy']);

Route::get('subscription', 'SubscriptionController@subscription')->name('subscription');
Route::post('subscription', 'SubscriptionController@subscribe')->name('subscribe');
Route::post('cancel', 'SubscriptionController@cancel')->name('cancel');

Route::get('about', 'PageController@about')->name('about');
Route::get('api_docs', 'PageController@apiDocs')->name('api-docs');
Route::get('/', 'PageController@index')->name('home');
