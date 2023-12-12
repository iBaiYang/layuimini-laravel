<?php

Route::get('login', 'Admin\IndexController@login')->name('admin_login')->middleware('web');
Route::post('login_in', 'Admin\IndexController@login_in')->name('admin_login_in')->middleware('web');

Route::group(["prefix" => "admin", "namespace" => "Admin", 'middleware' => ['web','checkLogin']], function () {
    Route::get('/', 'HomeController@home')->name('admin_home');
    Route::post('login_out', 'HomeController@login_out')->name('admin_login_out');
    Route::get('init', 'HomeController@init')->name('admin_init');

    Route::get('clear', 'HomeController@clear')->name('admin_clear');
    Route::get('menu', 'HomeController@menu')->name('admin_menu');
    Route::get('table', 'HomeController@table')->name('admin_table');
    Route::get('tableSelect', 'HomeController@tableSelect')->name('admin_tableSelect');
    Route::get('upload', 'HomeController@upload')->name('admin_upload');
});
