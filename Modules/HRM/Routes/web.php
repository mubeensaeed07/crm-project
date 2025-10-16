<?php

// V1.0.0.1


use Illuminate\Support\Facades\Route;
use Modules\HRM\Http\Controllers\HRMController;

Route::middleware(['auth:web,supervisor', 'prevent.back'])->prefix('hrm')->group(function () {
    Route::get('/', [HRMController::class, 'dashboard'])->name('hrm.dashboard');
    Route::get('/employees', [HRMController::class, 'employees'])->name('hrm.employees');
    Route::get('/departments', [HRMController::class, 'departments'])->name('hrm.departments');
    Route::get('/attendance', [HRMController::class, 'attendance'])->name('hrm.attendance');
    Route::get('/payroll', [HRMController::class, 'payroll'])->name('hrm.payroll');
    
    // Note: User management routes are defined in the main routes/web.php file
});
