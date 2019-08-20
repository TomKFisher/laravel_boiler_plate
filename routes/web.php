<?php

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
Auth::routes(['verify' => true]);
Route::get('/logout', ['as' => 'logout', 'uses' => 'Auth\LoginController@logout']);

Route::group(['middleware' => ['auth','verified']], function(){
    Route::get('/', 'DashboardController@index')->name('dashboard');
    
    Route::model('role', 'App\Models\Role');
    Route::group(['prefix' => 'roles'], function(){
        Route::post('{role}/permissions', [ 'as' => 'roles.edit_permissions', 'uses' => 'RoleController@add_remove_permission' ]);
        Route::get('archive', ['as' => 'roles.archive', 'uses' => 'RoleController@archive']);
        Route::post('{role_id}/restore', ['as' => 'roles.restore', 'uses' => 'RoleController@restore']);
        Route::post('{role_id}/delete', ['as' => 'roles.delete', 'uses' => 'RoleController@delete']);
    });
    Route::resource('roles', 'RoleController');
    
    Route::model('user', 'App\Models\User');
    Route::group(['prefix' => 'users'], function(){
        Route::get('search', ['as' => 'users.search', 'uses' => 'UserController@search']);
        Route::post('{user}/re-invite', ['as' => 'users.re_invite', 'uses' => 'UserController@re_invite']);
        Route::post('{user}/reset-password', ['as' => 'users.password_reset', 'uses' => 'UserController@password_reset']);
        Route::get('archive', ['as' => 'users.archive', 'uses' => 'UserController@archive']);
        Route::post('{user_id}/restore', ['as' => 'users.restore', 'uses' => 'UserController@restore']);
        Route::post('{user_id}/delete', ['as' => 'users.delete', 'uses' => 'UserController@delete']);
    });
    Route::resource('users', 'UserController');

    Route::model('log', 'App\Models\AuditLog');
    Route::group(['prefix' => 'logs'], function(){
        Route::post('{log}/restore', ['as'=>'logs.restore', 'uses'=>'AuditController@restore_audit']);
    });
    Route::resource('logs', 'AuditController');
    
    Route::get('/media/{path}', '\Hyn\Tenancy\Controllers\MediaController')
        ->where('path', '.+')
        ->name('tenant.media');
});
