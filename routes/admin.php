<?php

Route::get('login', 'Admin\IndexController@login')->name('admin_login')->middleware('web');
Route::post('login_in', 'Admin\IndexController@login_in')->name('admin_login_in')->middleware('web');

Route::group(["prefix" => "admin", "namespace" => "Admin", 'middleware' => ['web', 'checkLogin']], function () {
    Route::get('/', 'HomeController@home')->name('admin_home');
    Route::post('login_out', 'HomeController@login_out')->name('admin_login_out');
    Route::get('init', 'HomeController@init')->name('admin_init');
    Route::get('clear', 'HomeController@clear')->name('admin_clear');

    // 管理员
    Route::get('admin', 'AdminController@admin')->name('admin_admin');
    Route::get('admin_api', 'AdminController@admin_api')->name('admin_admin_api');
    Route::match(['get', 'post'], 'admin_edit', 'AdminController@admin_edit')->name('admin_admin_edit');
    Route::post('admin_delete', 'AdminController@admin_delete')->name('admin_admin_delete');

    // 操作
    Route::get('rbac_action', 'RbacController@action')->name('admin_rbac_action');
    Route::get('rbac_action_api', 'RbacController@action_api')->name('admin_rbac_action_api');
    Route::match(['get', 'post'], 'rbac_action_edit', 'RbacController@action_edit')->name('admin_rbac_action_edit');

    // 角色
    Route::get('rbac_role', 'RbacController@role')->name('admin_rbac_role');
    Route::get('rbac_role_api', 'RbacController@role_api')->name('admin_rbac_role_api');
    Route::match(['get', 'post'], 'rbac_role_edit', 'RbacController@role_edit')->name('admin_rbac_role_edit');
    Route::post('rbac_role_delete', 'RbacController@role_delete')->name('admin_rbac_role_delete');
    Route::match(['get', 'post'], 'rbac_role_actions', 'RbacController@role_actions')->name('admin_rbac_role_actions');


    Route::get('menu', 'HomeController@menu')->name('admin_menu');
    Route::get('menu_api', 'HomeController@menu_api')->name('admin_menu_api');
    Route::get('table', 'HomeController@table')->name('admin_table');
    Route::get('tableSelect', 'HomeController@tableSelect')->name('admin_tableSelect');
    Route::get('upload', 'HomeController@upload')->name('admin_upload');
});
