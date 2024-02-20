<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| C2E API Routes - Publisher
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['prefix' => 'v1', 'namespace' => 'Api\V1'], function () {
    Route::middleware(['auth:api', 'verified'])->group(function () {
        Route::group(['prefix' => 'c2e', 'namespace' => 'C2E'], function () {
            // Publisher APIs
            Route::group(['prefix' => 'publishers', 'namespace' => 'Publisher'], function () {
                Route::apiResource('suborganizations/{suborganization}/settings', 'PublisherController');
                Route::get('{publisher}/stores', 'PublisherController@getStores');
                Route::post('{publisher}/independent-activities/{independent_activity}/publish', 'PublisherController@publish');
                Route::get('{publisher}/independent-activities/{independent_activity}/publish-media', 'PublisherController@getPublishMedia');
            });

            // Media Catalog APIs
            Route::group(['prefix' => 'media-catalog', 'namespace' => 'MediaCatalog'], function () {
                Route::apiResource('suborganizations/{suborganization}/settings', 'MediaCatalogAPISettingsController');
            });
        });
    });
});
