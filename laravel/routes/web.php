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
Route::post('record', '\App\Http\Controllers\AudioController@upload');

//Login
Route::get('register/form', '\App\Http\Controllers\UserController@register');
Route::post('register/submit', '\App\Http\Controllers\UserController@registerSubmit');

Route::get('/', '\App\Http\Controllers\UserController@loginView');
Route::get('login/form', '\App\Http\Controllers\UserController@loginView');
Route::post('login/options', '\App\Http\Controllers\UserController@loginSubmit');
Route::post('login/password', '\App\Http\Controllers\UserController@loginPasswordSubmit');

Route::post('login/voice', '\App\Http\Controllers\UserController@loginVoice');
Route::post('login/voice/submit', '\App\Http\Controllers\UserController@loginVoiceSubmit');

Route::get('logout', '\App\Http\Controllers\UserController@logout');

//Dashboard
Route::get('dashboard', '\App\Http\Controllers\DashboardController@index');
Route::get('dashboard/voice/add', '\App\Http\Controllers\DashboardController@voiceNew');
Route::post('dashboard/voice/add', '\App\Http\Controllers\DashboardController@voiceNewSubmit');

Auth::routes();

URL::forceScheme('https');
