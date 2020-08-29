<?php

Route::group(['middleware' => ['api']], function () {
	Route::resource('api/h5p', "Djoudi\LaravelH5p\Http\Controllers\H5pApiController");
});

Route::group(['middleware' => ['web']], function () {
	//Route::resource('api/h5p', "Djoudi\LaravelH5p\Http\Controllers\H5pApiController");
	if (config('laravel-h5p.use_router') == 'EDITOR' || config('laravel-h5p.use_router') == 'ALL') {
		Route::group(['middleware' => ['auth']], function () {
			Route::resource('/h5p', "Djoudi\LaravelH5p\Http\Controllers\H5pController");			
//            Route::get('h5p/export', 'Djoudi\LaravelH5p\Http\Controllers\H5pController@export')->name("h5p.export");

			Route::get('library', "Djoudi\LaravelH5p\Http\Controllers\LibraryController@index")->name("h5p.library.index");
			Route::get('library/show/{id}', "Djoudi\LaravelH5p\Http\Controllers\LibraryController@show")->name("h5p.library.show");
			Route::post('library/store', "Djoudi\LaravelH5p\Http\Controllers\LibraryController@store")->name("h5p.library.store");
			Route::delete('library/destroy', "Djoudi\LaravelH5p\Http\Controllers\LibraryController@destroy")->name("h5p.library.destroy");
			Route::get('library/restrict', "Djoudi\LaravelH5p\Http\Controllers\LibraryController@restrict")->name("h5p.library.restrict");
			Route::post('library/clear', "Djoudi\LaravelH5p\Http\Controllers\LibraryController@clear")->name("h5p.library.clear");
		});

		// ajax
		Route::match(['GET', 'POST'], 'ajax/libraries', 'Djoudi\LaravelH5p\Http\Controllers\AjaxController@libraries')->name("h5p.ajax.libraries");
		Route::get('ajax', 'Djoudi\LaravelH5p\Http\Controllers\AjaxController')->name("h5p.ajax");
		Route::get('ajax/libraries', 'Djoudi\LaravelH5p\Http\Controllers\AjaxController@libraries')->name("h5p.ajax.libraries");
		Route::get('ajax/single-libraries', 'Djoudi\LaravelH5p\Http\Controllers\AjaxController@singleLibrary')->name("h5p.ajax.single-libraries");
		Route::any('ajax/content-type-cache', 'Djoudi\LaravelH5p\Http\Controllers\AjaxController@contentTypeCache')->name("h5p.ajax.content-type-cache");
		Route::any('ajax/library-install', 'Djoudi\LaravelH5p\Http\Controllers\AjaxController@libraryInstall')->name("h5p.ajax.library-install");
		Route::post('ajax/library-upload', 'Djoudi\LaravelH5p\Http\Controllers\AjaxController@libraryUpload')->name("h5p.ajax.library-upload");
		Route::post('ajax/rebuild-cache', 'Djoudi\LaravelH5p\Http\Controllers\AjaxController@rebuildCache')->name("h5p.ajax.rebuild-cache");
		Route::any('ajax/files', 'Djoudi\LaravelH5p\Http\Controllers\AjaxController@files')->name("h5p.ajax.files");
		Route::any('ajax/filter', 'Djoudi\LaravelH5p\Http\Controllers\AjaxController@filter')->name("h5p.ajax.filter");
		Route::any('ajax/finish', 'Djoudi\LaravelH5p\Http\Controllers\AjaxController@finish')->name("h5p.ajax.finish");
		Route::any('ajax/content-user-data', 'Djoudi\LaravelH5p\Http\Controllers\AjaxController@contentUserData')->name("h5p.ajax.content-user-data");
		Route::any('h5p-result/my', 'Djoudi\LaravelH5p\Http\Controllers\H5PResultController@my')->name("h5p.result.my");
	}

	// export
	//    if (config('laravel-h5p.use_router') == 'EXPORT' || config('laravel-h5p.use_router') == 'ALL') {
	Route::get('h5p/embed/{id}', 'Djoudi\LaravelH5p\Http\Controllers\EmbedController')->name("h5p.embed");
	Route::get('h5p/export/{id}', 'Djoudi\LaravelH5p\Http\Controllers\DownloadController')->name("h5p.export");
	Route::get('h5p/content-params/{id}', "Djoudi\LaravelH5p\Http\Controllers\H5pController@contentParams");
//    }
});
