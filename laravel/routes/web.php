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

Route::get('/', function () {
    return view('welcome');
});

Route::get('testPythonScript', '\App\Http\Controllers\PythonTestController@testPythonScript');
Route::get('recognize', '\App\Http\Controllers\RecognitionController@upload');
Route::post('recognize', '\App\Http\Controllers\RecognitionController@recognize');

// Embeddings

Route::get('embedding', '\App\Http\Controllers\EmbeddingController@index');
Route::post('embedding', '\App\Http\Controllers\EmbeddingController@redirect');
Route::get('embedding/{userId}', '\App\Http\Controllers\EmbeddingController@upload');
Route::post('embedding/{userId}', '\App\Http\Controllers\EmbeddingController@result');

// Audio Upload Tests

Route::get('record', '\App\Http\Controllers\AudioController@record');