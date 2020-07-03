<?php

use Illuminate\Support\Facades\Route;

Route::get('api/access-control/roles', 'pierresilva\AccessControl\Controllers\RolesController@index');
Route::get('api/access-control/roles/{roleSlug}', 'pierresilva\AccessControl\Controllers\RolesController@show');
Route::post('api/access-control/roles', 'pierresilva\AccessControl\Controllers\RolesController@store');
Route::put('api/access-control/roles/{roleSlug}', 'pierresilva\AccessControl\Controllers\RolesController@update');
Route::delete('api/access-control/roles/{roleSlug}', 'pierresilva\AccessControl\Controllers\RolesController@destroy');

Route::get('api/access-control/permissions', 'pierresilva\AccessControl\Controllers\PermissionsController@index');
Route::get('api/access-control/permissions/{permissionSlug}', 'pierresilva\AccessControl\Controllers\PermissionsController@show');
Route::post('api/access-control/permissions', 'pierresilva\AccessControl\Controllers\PermissionsController@store');
Route::put('api/access-control/permissions/{permissionSlug}', 'pierresilva\AccessControl\Controllers\PermissionsController@update');
Route::delete('api/access-control/permissions/{permissionSlug}', 'pierresilva\AccessControl\Controllers\PermissionsController@destroy');
