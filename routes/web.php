<?php

use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\Auth\SsoCallbackController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeImportController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\SelfServiceController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use RobbinThijssen\IdentitySsoKit\Http\Controllers\LogoutController;
use RobbinThijssen\IdentitySsoKit\Http\Controllers\RedirectToIdentityController;

Route::get('login', RedirectToIdentityController::class)->name('login');
Route::get('sso/callback', SsoCallbackController::class)->name('sso.callback');
Route::post('logout', LogoutController::class)->middleware('auth')->name('logout');

Route::get('privacy', fn () => inertia('Privacy'))->name('privacy');

Route::middleware(['auth'])->group(function () {
    Route::redirect('/', '/employer')->name('home');
    Route::redirect('dashboard', '/employer')->name('dashboard');

    Route::get('self-service', [SelfServiceController::class, 'show'])->name('self-service.show');
    Route::get('self-service/gdpr-export', [SelfServiceController::class, 'gdprExport'])->middleware('throttle:gdpr-export')->name('self-service.gdpr-export');

    Route::get('employer', [EmployerController::class, 'show'])->name('employer.show');
    Route::get('employer/employees/search', [EmployeeController::class, 'search'])->name('employees.search');
    Route::post('employer/employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('employer/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('employer/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::post('employer/employee-imports', [EmployeeImportController::class, 'store'])->name('employee-imports.store');
    Route::get('employer/employee-imports/{import}', [EmployeeImportController::class, 'status'])->name('employee-imports.show');
    Route::post('employer/absences', [AbsenceController::class, 'store'])->name('absences.store');
    Route::post('employer/absences/{case}/mutate', [AbsenceController::class, 'mutate'])->name('absences.mutate');
    Route::post('employer/absences/{case}/close', [AbsenceController::class, 'close'])->name('absences.close');

    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::put('users/{uuid}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{uuid}', [UserController::class, 'destroy'])->name('users.destroy');
});

require __DIR__.'/settings.php';
