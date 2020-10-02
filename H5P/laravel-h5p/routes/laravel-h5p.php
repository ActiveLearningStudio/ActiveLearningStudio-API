<?php

Route::group(['middleware' => ['api']], function () {
	if (config('laravel-h5p.use_router') == 'EDITOR' || config('laravel-h5p.use_router') == 'ALL') {
		Route::group(['middleware' => ['auth']], function () {
			Route::resource('h5p', 'Djoudi\LaravelH5p\Http\Controllers\H5pController', ['as' => 'h5p.library']);
			Route::get('library', 'Djoudi\LaravelH5p\Http\Controllers\LibraryController@index')->name('h5p.library.index');
			Route::get('library/show/{id}', 'Djoudi\LaravelH5p\Http\Controllers\LibraryController@show')->name('h5p.library.show');
			Route::post('library/store', 'Djoudi\LaravelH5p\Http\Controllers\LibraryController@store')->name('h5p.library.store');
			Route::delete('library/destroy', 'Djoudi\LaravelH5p\Http\Controllers\LibraryController@destroy')->name('h5p.library.destroy');
			Route::get('library/restrict', 'Djoudi\LaravelH5p\Http\Controllers\LibraryController@restrict')->name('h5p.library.restrict');
			Route::post('library/clear', 'Djoudi\LaravelH5p\Http\Controllers\LibraryController@clear')->name('h5p.library.clear');
		});
	}

	Route::get('h5p/embed/{id}', 'Djoudi\LaravelH5p\Http\Controllers\EmbedController')->name('h5p.embed');
	Route::get('h5p/content-params/{id}', 'Djoudi\LaravelH5p\Http\Controllers\H5pController@contentParams');
});
