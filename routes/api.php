<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::group(['prefix' => 'v1', 'middleware' => 'auth:api'], function () {
Route::group(['prefix' => 'v1'], function () {
    Route::get('/party/{party}', 'PartyController@getResults');

    Route::get('/nationalityhouse_region/{region}/{year}', 'NationalityResultController@getResultsByRegion');
    Route::get('/nationalityhouse_total/{year}', 'NationalityResultController@getResultsTotal');
    Route::get('/nationalityhouse_compare/{type}', 'NationalityResultController@compareTotal');
    Route::get('/nationalityhouse_country/{year}', 'NationalityResultController@getResultsCountry');
    Route::get('/nationalityhouse_seat/{nationalityhouse}', 'NationalityResultController@getResultsSeat');
    Route::get('/nationalityhouse_candidates/{region}/{year}', 'NationalityResultController@getCandidatesRegion');

    Route::apiResource('region', 'RegionController');
    Route::get('/region/{region}/{year}', 'RegionController@getHouses');
    Route::get('/census/{level}', 'CensusController@censusVote');
});
