<?php

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\User\RoleComponent;
use App\Livewire\User\UserComponent;
use App\Livewire\Auth\ForgetPassword;
use App\Livewire\Dashboard\Dashboard;
use Illuminate\Support\Facades\Route;
use App\Livewire\Asset\AssetComponent;
use App\Livewire\Loan\LoanTypeComponent;
use App\Livewire\Tax\TaxTablesComponent;
use App\Livewire\Asset\AssetTypeComponent;
use App\Livewire\Leave\LeaveTypeComponent;
use App\Livewire\Nasfund\NasfundComponent;
use App\Livewire\Payroll\PayslipComponent;
use App\Livewire\Loan\LoanRequestComponent;
use App\Livewire\Business\BusinessComponent;
use App\Livewire\Employee\EmployeeComponent;
use App\Http\Controllers\ApproveLeaveRequest;
use App\Livewire\Holiday\SetHolidayComponent;
use App\Livewire\Leave\LeaveRequestComponent;
use App\Livewire\Workshift\WorkshiftComponent;
use App\Http\Controllers\AbaGenerateController;
use App\Livewire\Department\DepartmentComponent;
use App\Livewire\Employee\EmployeeInfoComponent;
use App\Livewire\Designation\DesignationComponent;
use App\Livewire\Employee\CreateEmployeeComponent;
use App\Livewire\Employee\ImportEmployeeComponent;
use App\Livewire\Attendance\AttendanceLogComponent;
use App\Livewire\AbaGenerator\AbaGeneratorComponent;
use App\Livewire\CompanyDetails\BankDetailsComponent;
use App\Livewire\Attendance\AttendanceImportComponent;
use App\Livewire\EmailTemplate\EmailTemplateComponent;
use App\Livewire\EmailTemplate\EmailVariableComponent;
use App\Livewire\Fortnight\FortnightGeneratorComponent;
use App\Livewire\EmployeeStatus\EmployeeStatusComponent;
use App\Livewire\Attendance\AttendanceAdjustmentComponent;
use App\Livewire\EmailTemplate\EmailTemplateTypeComponent;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Livewire\Payroll\PayrunComponent;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::group(['middleware' => ['auth', 'verified', 'is_active']], function () {
    // Dashboard routes
    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('/', Dashboard::class)->name('dashboard');
    });

    // Apps routes
    Route::group(['prefix' => 'apps'], function () {
        Route::get('/business', BusinessComponent::class)->name('business');
        Route::get('/department', DepartmentComponent::class)->name('department');
        Route::get('/fortnight-generator', FortnightGeneratorComponent::class)->name('fortnight-generator');
        Route::get('/workshift', WorkshiftComponent::class)->name('workshift');
        Route::get('/users', UserComponent::class)->name('users');
        Route::get('/roles', RoleComponent::class)->name('roles');
        Route::get('/bank-details', BankDetailsComponent::class)->name('bank-details');
        Route::get('/tax-table', TaxTablesComponent::class)->name('tax-table');
        Route::get('/set-holiday', SetHolidayComponent::class)->name('set-holiday');
        Route::get('/nasfund', NasfundComponent::class)->name('nasfund');
    });

    // Employee routes
    Route::group(['prefix' => 'employee'], function () {
        Route::get('/{label}/employees', EmployeeComponent::class)->name('employee.index');
        Route::get('/{label}/create', CreateEmployeeComponent::class)->name('employee.create');
        Route::get('/{label}/info/{id}', EmployeeInfoComponent::class)->name('employee.info');
        Route::get('/designation', DesignationComponent::class)->name('designation');
        Route::get('/status', EmployeeStatusComponent::class)->name('employee-status');
        Route::get('/import', ImportEmployeeComponent::class)->name('import-employee');
    });

    // Attendance routes
    Route::group(['prefix' => 'attendance'], function () {
        Route::get('/logs', AttendanceLogComponent::class)->name('attendance-logs');
        Route::get('/import', AttendanceImportComponent::class)->name('attendance-import');
        Route::get('/adjustment', AttendanceAdjustmentComponent::class)->name('attendance-adjustment');
    });

    // Payroll routes
    Route::group(['prefix' => 'payroll'], function () {
        Route::get('/payslip', PayslipComponent::class)->name('payslip');
        Route::get('/aba-generate', AbaGeneratorComponent::class)->name('aba-generate');
        Route::get('/payrun', PayrunComponent::class)->name('payrun');
    });

    // Leave routes
    Route::group(['prefix' => 'leave'], function () {
        Route::get('/request', LeaveRequestComponent::class)->name('leave-request');
        Route::get('/types', LeaveTypeComponent::class)->name('leave-types');
    });

    // Loan routes
    Route::group(['prefix' => 'loan'], function () {
        Route::get('/request', LoanRequestComponent::class)->name('loans');
        Route::get('/type', LoanTypeComponent::class)->name('loan-type');
    });

    // General Settings

    // Email Template routes
    Route::group(['prefix' => 'email-template'], function () {

        Route::get('/type', EmailTemplateTypeComponent::class)->name('email-template-type');
        Route::get('/variable', EmailVariableComponent::class)->name('email-variable');
        Route::get('/', EmailTemplateComponent::class)->name('email-template');
    });

    // Asset routes
    Route::group(['prefix' => 'asset'], function () {
        Route::get('/', AssetComponent::class)->name('asset');
        Route::get('/type', AssetTypeComponent::class)->name('asset-type');
    });
});

// Authentication routes
// Route::group(['prefix' => 'auth'], function () {
//     Route::get('login', Login::class)->name('login');
//     Route::get('forgot-password', ForgetPassword::class)->name('forgot-password');
// });

//Leave Approve Route
Route::get('/leave-request/approve/{id}', [ApproveLeaveRequest::class, 'approve'])->name('approve-leave-request');
Route::get('/leave-request/success', [ApproveLeaveRequest::class, 'success'])->name('success-leave-request');

// Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');

Route::get('/aba-download/{businessId}/{selectedFN}/{employeeIds}', [AbaGenerateController::class, 'generate'])->name('aba-download');
// Route::post('login', [AuthenticatedSessionController::class, 'store']);
require __DIR__ . '/auth.php';
