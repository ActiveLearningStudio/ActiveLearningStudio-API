<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| CurrikiGo Integration API Routes - Brightcove Player, Safarimontage
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['prefix' => 'v1', 'namespace' => 'Api\V1'], function () {
    Route::group(['prefix' => 'brightcove'], function () {
        Route::get('{accountId}/{videoId}/{dataPlayer}/{dataEmbed}/h5p-resource-settings', 'BrightcoveController@getH5pResourceSettings');
    });

    // Kaltura Video Integration For Curriki Interactive Video
    Route::group(['prefix' => 'kaltura'], function () {
        Route::get('get-media-entry-list', 'CurrikiInteractiveVideoIntegration\Kaltura\KalturaGeneratedAPIClientController@getMediaEntryList');
    });
});
