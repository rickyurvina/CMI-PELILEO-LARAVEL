<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::get('login', 'Auth\LoginController@create')->name('login');
    Route::post('login', 'Auth\LoginController@store');
});
