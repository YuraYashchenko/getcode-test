<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RoutieServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::resource('todo', 'TodosController', ['except' => ['edit', 'show', 'create']]);

Route::post('progress', 'ProgressesController@setProgress');

Route::post('move', 'MovesController@move');