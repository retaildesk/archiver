<?php

use Illuminate\Support\Facades\Route;

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
//Route::get('apps/enable/{id}', 'Apps\AppsController@enable')->where('id', '[0-9]+');

Route::get('/','App\Http\Controllers\DashboardController@index' )->middleware(['auth'])->name('dashboard');
Route::get('/download','App\Http\Controllers\DownloadController@form' )->middleware(['auth'])->name('download');
Route::get('/download/startjob/{year}','App\Http\Controllers\DownloadController@startJob' )->middleware(['auth'])->where('year', '[0-9]+');
Route::get('/upload/mt940','App\Http\Controllers\UploadController@formMt940' )->middleware(['auth'])->name('uploadMt940');
Route::get('/transaction/{id}','App\Http\Controllers\TransactionController@get' )->middleware(['auth'])->where('id', '[0-9]+');
Route::post('/transaction/{id}/comment','App\Http\Controllers\TransactionController@comment' )->middleware(['auth'])->where('id', '[0-9]+');
Route::post('/transaction/{id}/status','App\Http\Controllers\TransactionController@status' )->middleware(['auth'])->where('id', '[0-9]+');
Route::post('/upload/transaction','App\Http\Controllers\UploadController@TransactionUpload' )->middleware(['auth']);
Route::get('/upload/file/{id}','App\Http\Controllers\UploadController@getFile' )->middleware(['auth'])->where('id', '[0-9]+');
Route::delete('/upload/file/{id}','App\Http\Controllers\UploadController@deleteFile' )->middleware(['auth'])->where('id', '[0-9]+');

Route::post('/upload/mt940','App\Http\Controllers\UploadController@Mt940Upload' )->middleware(['auth'])->name('postuploadMt940');
Route::post('/browser','App\Http\Controllers\BrowserController@post' )->middleware(['auth']);

Route::get('/browser/open/{type}/{base64}','App\Http\Controllers\BrowserController@openAttachment' )->middleware(['auth'])->where('type',"attachment|scan");
Route::get('/browser/connect/{transaction_id}/{base64}','App\Http\Controllers\BrowserController@connectAttachment' )->middleware(['auth']);




Route::get('/oauth/gmail', function (){
    return LaravelGmail::redirect();
});

Route::get('/oauth/gmail/callback', function (){
    LaravelGmail::makeToken();
    return redirect()->to('/');
});

Route::get('/oauth/gmail/logout', function (){
    LaravelGmail::logout(); //It returns exception if fails
    return redirect()->to('/');
});


require __DIR__.'/auth.php';
