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

Route::get('scores', 'ScoreController@index')->name('score.list');
Route::post('scores', 'ScoreController@store')->name('send_score');
Route::get('daily_scores', 'DailyScoreController@index')->name('daily_score.list');
Route::get('influencers', 'InfluencerController@index')->name('influencers.list');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
