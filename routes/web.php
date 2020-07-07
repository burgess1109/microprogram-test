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

Route::get('currency', 'Currency\ListAction');
Route::get('currency/{id}', 'Currency\ShowAction');
Route::post('currency', 'Currency\StoreAction');
Route::patch('currency/{id}', 'Currency\UpdateAction');
Route::delete('currency/{id}', 'Currency\DeleteAction');

