<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\EmployeeTypeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\TransportSettingController;
use App\Http\Controllers\TransportAllowanceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/refresh-captcha', [AuthController::class, 'refreshCaptcha']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')
        ->middleware('permission:dashboard.index');

    Route::prefix('departments')->name('departments.')->group(function () {
        Route::get('/', [DepartmentController::class, 'index'])->name('index')->middleware('permission:departments.index');
        Route::post('/', [DepartmentController::class, 'store'])->name('store')->middleware('permission:departments.create');
        Route::put('/{department}', [DepartmentController::class, 'update'])->name('update')->middleware('permission:departments.edit');
        Route::delete('/{department}', [DepartmentController::class, 'destroy'])->name('destroy')->middleware('permission:departments.delete');
    });

    Route::prefix('positions')->name('positions.')->group(function () {
        Route::get('/', [PositionController::class, 'index'])->name('index')->middleware('permission:positions.index');
        Route::post('/', [PositionController::class, 'store'])->name('store')->middleware('permission:positions.create');
        Route::put('/{position}', [PositionController::class, 'update'])->name('update')->middleware('permission:positions.edit');
        Route::delete('/{position}', [PositionController::class, 'destroy'])->name('destroy')->middleware('permission:positions.delete');
    });

    Route::prefix('employee-types')->name('employee-types.')->group(function () {
        Route::get('/', [EmployeeTypeController::class, 'index'])->name('index')->middleware('permission:employee_types.index');
        Route::post('/', [EmployeeTypeController::class, 'store'])->name('store')->middleware('permission:employee_types.create');
        Route::put('/{employee_type}', [EmployeeTypeController::class, 'update'])->name('update')->middleware('permission:employee_types.edit');
        Route::delete('/{employee_type}', [EmployeeTypeController::class, 'destroy'])->name('destroy')->middleware('permission:employee_types.delete');
    });

    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index')->middleware('permission:roles.index');
        Route::get('/create', [RoleController::class, 'create'])->name('create')->middleware('permission:roles.create');
        Route::post('/', [RoleController::class, 'store'])->name('store')->middleware('permission:roles.create');
        Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit')->middleware('permission:roles.edit');
        Route::put('/{role}', [RoleController::class, 'update'])->name('update')->middleware('permission:roles.edit');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy')->middleware('permission:roles.delete');
    });

    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index')
        ->middleware('permission:activity_logs.index');

    Route::prefix('transport')->group(function () {
        Route::prefix('settings')->name('transport-settings.')->group(function () {
            Route::get('/', [TransportSettingController::class, 'index'])->name('index')->middleware('permission:transport_settings.index');
            Route::post('/', [TransportSettingController::class, 'store'])->name('store')->middleware('permission:transport_settings.create');
            Route::put('/{transportSetting}', [TransportSettingController::class, 'update'])->name('update')->middleware('permission:transport_settings.edit');
            Route::delete('/{transportSetting}', [TransportSettingController::class, 'destroy'])->name('destroy')->middleware('permission:transport_settings.delete');
        });

        Route::prefix('allowances')->name('transport-allowances.')->group(function () {
            Route::get('/', [TransportAllowanceController::class, 'index'])->name('index')->middleware('permission:transport_allowances.index');
            Route::post('/', [TransportAllowanceController::class, 'store'])->name('store')->middleware('permission:transport_allowances.create');
            Route::put('/{transportAllowance}', [TransportAllowanceController::class, 'update'])->name('update')->middleware('permission:transport_allowances.edit');
            Route::delete('/{transportAllowance}', [TransportAllowanceController::class, 'destroy'])->name('destroy')->middleware('permission:transport_allowances.delete');
        });
    });
});
