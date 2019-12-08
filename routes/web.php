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

Route::get('/', 'HomeController@welcome')->name('home');

Route::get('config', function () {
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('cache:forget spatie.permission.cache');
});

Auth::routes();

Route::group(['prefix' => 'admin', 'middleware' => ['auth','check_block']], function () {
    Route::get('/home', 'HomeController@index')->name('admin.dashboard');
    Route::match(['get','post'],'/profile', 'HomeController@profile')->name('profile.manage');
    Route::group(['prefix' => 'advanced'], function () {
        Route::resource('settings', 'SettingController');
        Route::resource('custom-fields', 'CustomFieldController', ['names' => 'customFields']);
        Route::resource('file-types', 'FileTypeController', ['names' => 'fileTypes']);
    });
    Route::resource('users', 'UserController');
    Route::get('/users-block/{user}', 'UserController@blockUnblock')->name('users.blockUnblock');
    Route::resource('tags', 'TagController');

    Route::resource('documents', 'DocumentController');
    Route::post('document-verify/{id}','DocumentController@verify')->name('documents.verify');
    Route::post('document-store-permission/{id}','DocumentController@storePermission')->name('documents.store-permission');
    Route::post('document-delete-permission/{document_id}/{user_id}','DocumentController@deletePermission')->name('documents.delete-permission');
    Route::group(['prefix' => '/files-upload', 'as' => 'documents.files.'], function () {
        Route::get('/{id}', 'DocumentController@showUploadFilesUi')->name('create');
        Route::post('/{id}', 'DocumentController@storeFiles')->name('store');
        Route::delete('/{id}', 'DocumentController@deleteFile')->name('destroy');
    });

    Route::get('/_files/{dir?}/{file?}','HomeController@showFile')->name('files.showfile');
    Route::get('/_zip/{id}/{dir?}','HomeController@downloadZip')->name('files.downloadZip');
    Route::post('/_pdf','HomeController@downloadPdf')->name('files.downloadPdf');
});
