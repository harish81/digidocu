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

use App\Http\Controllers\CustomFieldController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\FileTypeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;

Route::get('/', [HomeController::class,'welcome'])->name('home');

Route::get('config', function () {
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('cache:forget spatie.permission.cache');
});

Auth::routes();

Route::group(['prefix' => 'admin', 'middleware' => ['auth','check_block']], function () {
    Route::get('/home', [HomeController::class,'index'])->name('admin.dashboard');
    Route::match(['get','post'],'/profile', [HomeController::class,'profile'])->name('profile.manage');
    Route::group(['prefix' => 'advanced'], function () {
        Route::resource('settings', SettingController::class);
        Route::resource('custom-fields', CustomFieldController::class, ['names' => 'customFields']);
        Route::resource('file-types', FileTypeController::class, ['names' => 'fileTypes']);
    });
    Route::resource('users', UserController::class);
    Route::get('/users-block/{user}',[UserController::class,'blockUnblock'])->name('users.blockUnblock');
    Route::resource('tags', TagController::class);

    Route::resource('documents', DocumentController::class);
    Route::post('document-verify/{id}',[DocumentController::class,'verify'])->name('documents.verify');
    Route::post('document-store-permission/{id}',[DocumentController::class,'storePermission'])->name('documents.store-permission');
    Route::post('document-delete-permission/{document_id}/{user_id}',[DocumentController::class,'deletePermission'])->name('documents.delete-permission');
    Route::group(['prefix' => '/files-upload', 'as' => 'documents.files.'], function () {
        Route::get('/{id}', [DocumentController::class,'showUploadFilesUi'])->name('create');
        Route::post('/{id}', [DocumentController::class,'storeFiles'])->name('store');
        Route::delete('/{id}', [DocumentController::class,'deleteFile'])->name('destroy');
    });

    Route::get('/_files/{dir?}/{file?}',[HomeController::class,'showFile'])->name('files.showfile');
    Route::get('/_zip/{id}/{dir?}',[HomeController::class,'downloadZip'])->name('files.downloadZip');
    Route::post('/_pdf',[HomeController::class,'downloadPdf'])->name('files.downloadPdf');
});
