<?php

use Illuminate\Http\Request;

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

Route::prefix('v1')->as('api.')->group(function () {

    // Route::any('/', function () { return redirect()->route('api-docs'); })->name('index');

    Route::any('snapshots/{snapshot}/query', 'API\SnapshotController@query')->name('query');

    Route::post('snapshots/{snapshot}/refresh', 'API\SnapshotController@refresh')->name('snapshots.refresh');

    Route::post('snapshots/{snapshot}/stop', 'API\SnapshotController@stop')->name('snapshots.stop');

    Route::post('snapshots/{snapshot}/retry', 'API\SnapshotController@retry')->name('snapshots.retry');

    Route::resource('snapshots', 'API\SnapshotController');

    Route::resource('variables', 'API\VariableController')
        ->only(['show', 'edit', 'update', 'destroy']);

    Route::resource('snapshots/{snapshot}/variables', 'API\VariableController')
        ->only(['index', 'store']);
});
