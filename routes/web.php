<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@index')->name('home');

Route::group(['prefix' => 'auth'], function () {
    Route::get('logout', 'Auth\LoginController@destroy')->name('logout');
});

Route::group(['prefix' => 'admin'], function () {
    Route::resource('users', 'Auth\UserController')->except('show');
    Route::resource('roles', 'Auth\RoleController')->except('show');
});

Route::group(['prefix' => 'tracking'], function () {

    Route::get('/plans', 'PlanController@index')->name('plans.index');
    Route::get('/plans/edit', 'PlanController@edit')->name('plans.edit');
    Route::put('/plans/{id}/update', 'PlanController@update')->name('plans.update');

    Route::get('/objectives/view/{objective}', 'ObjectiveController@index')->name('objectives.index');
});