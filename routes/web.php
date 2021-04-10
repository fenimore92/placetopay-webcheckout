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

Route::prefix('orders')->group(function () {
    Route::view('/', 'orders.create')->name('order.create');    
    Route::post('/', 'OrdersController@store');
    Route::get('/show/{reference}', 'OrdersController@show')->name('order.show');
    Route::get('/list', 'OrdersController@index')->name('order.list');
});