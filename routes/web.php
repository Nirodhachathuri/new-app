<?php

use App\Http\Controllers\ExcelImportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route for showing the image upload form
Route::get('/upload', 'App\Http\Controllers\ImageController@showUploadForm')->name('image.upload.form');

// Route for handling the image upload and redirecting to the Excel import page
Route::post('/upload', 'App\Http\Controllers\ImageController@uploadImage')->name('image.upload');

Route::get('/excel-upload', 'App\Http\Controllers\ExcelImportController@index')->name('excel-upload');
Route::post('/excel-upload', 'App\Http\Controllers\ExcelImportController@import')->name('import.import');

Route::get('/process-data', 'App\Http\Controllers\DataInsertController@insertData')->name('process-data');


Route::view('/show', 'show');

Route::get('/images/{filename}', 'App\Http\Controllers\ImageController@showImage')->name('image.show');
