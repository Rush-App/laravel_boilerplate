<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'core.set-language'], function () {
    Route::post('login', 'Users\\AuthController@login');
    Route::post('register', 'Users\\AuthController@register');
    Route::post('refresh-token', 'Users\\AuthController@refreshToken');

    Route::post('change-forgotten-password', 'Users\\ChangePasswordController@passwordResetProcess');
    Route::post('password-reset-request', 'Users\\ChangePasswordController@setNewPassword');

    Route::post('consultation', 'Users\\MessageController@consultationRequest');

    Route::group(['middleware' => 'jwt-auth:user'], function () {
        Route::post('logout', 'Users\\AuthController@logout');
        Route::post('change-password', 'Users\\AuthController@changePassword');

        Route::get('get-personal-data', 'Users\\UserController@getPersonalData');
        Route::post('update-personal-data', 'Users\\UserController@updatePersonalData');

        Route::group(['middleware' => 'core.check-user-action'], function () {
            Route::resource('roles', 'Admins\\RoleController');
            Route::resource('user-management', 'Admins\\UserManagementController');
            Route::resource('access-level-control', 'Admins\\AccessLevelControlController')
                ->only(['index', 'update', 'store', 'destroy']);

            Route::post('archive-account', 'Admins\\UserManagementController@archiveAccount')
                ->name('archive-account.store');
            Route::post('restore-account', 'Admins\\UserManagementController@restoreAccount')
                ->name('restore-account.store');

            Route::resource('css-variable-categories', 'Admins\\CssVariableCategoryController');
            Route::resource('css-variables', 'Admins\\CssVariableController');
        });
    });
});
