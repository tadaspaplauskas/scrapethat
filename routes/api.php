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

Route::prefix('v1')->group(function () {

    Route::any('snapshots/{snapshot}/query', 'API\SnapshotController@query');

    Route::post('snapshots/{snapshot}/refresh', 'API\SnapshotController@refresh');

    Route::post('snapshots/{snapshot}/stop', 'API\SnapshotController@stop');

    Route::post('snapshots/{snapshot}/retry', 'API\SnapshotController@retry');

    Route::resource('snapshots', 'API\SnapshotController');

    Route::resource('variables', 'API\VariableController')
        ->only(['show', 'edit', 'update', 'destroy']);

});