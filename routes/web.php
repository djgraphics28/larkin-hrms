<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ForgetPassword;
use App\Livewire\Dashboard\Dashboard;
use Illuminate\Support\Facades\Route;
use App\Livewire\Business\BusinessComponent;
use App\Livewire\Employee\EmployeeComponent;
use App\Livewire\Department\DepartmentComponent;
use App\Livewire\Designation\DesignationComponent;
use App\Livewire\Employee\CreateEmployeeComponent;
use App\Livewire\EmployeeStatus\EmployeeStatusComponent;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::group(['middleware' => ['auth', 'verified']], function () {
    // Dashboard routes
    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('/', Dashboard::class)->name('dashboard');
    });

    // Apps routes
    Route::group(['prefix' => 'apps'], function () {
        Route::get('/business', BusinessComponent::class)->name('business');
        Route::get('/department', DepartmentComponent::class)->name('department');
    });

    // Employee routes
    Route::group(['prefix' => 'employee'], function () {
        Route::get('/{label}/employees', EmployeeComponent::class)->name('employee.index');
        Route::get('/{label}/create', CreateEmployeeComponent::class)->name('employee.create');
        Route::get('/designation', DesignationComponent::class)->name('designation');
        Route::get('/status', EmployeeStatusComponent::class)->name('employee-status');
    });


});

require __DIR__.'/auth.php';
