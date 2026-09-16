<?php

use App\Http\Controllers\CrmController;
use App\Http\Controllers\CrmConversionController;
use App\Http\Controllers\CrmDeskController;
use App\Http\Controllers\CrmStaffController;
use Illuminate\Support\Facades\Route;

Route::prefix('crm')->name('crm.')->group(function () {
    Route::get('/', [CrmController::class, 'loginForm'])->name('login');
    Route::post('/login', [CrmController::class, 'login'])
        ->middleware('throttle:12,1')
        ->name('login.store');
    Route::get('/register', [CrmController::class, 'registerForm'])->name('register');
    Route::post('/register', [CrmController::class, 'register'])
        ->middleware('throttle:8,1')
        ->name('register.store');

    Route::middleware('crm.auth')->group(function () {
        Route::post('/logout', [CrmController::class, 'logout'])->name('logout');

        Route::middleware('crm.admin')->group(function () {
            Route::get('/dashboard', [CrmController::class, 'dashboard'])->name('dashboard');
            Route::get('/today-attendance', [CrmController::class, 'todayAttendance'])->name('today-attendance');
            Route::get('/sessions/{session}', [CrmController::class, 'session'])
                ->whereNumber('session')
                ->name('session');
            Route::post('/assign', [CrmController::class, 'assign'])
                ->name('assign');
            Route::get('/assign-board', [CrmController::class, 'assignBoard'])->name('assign.board');
            Route::post('/assign/bulk', [CrmController::class, 'assignBulk'])->name('assign.bulk');
            Route::post('/assign/{assignment}/unassign', [CrmController::class, 'unassign'])
                ->name('assign.unassign');
            Route::post('/allocate', [CrmController::class, 'allocate'])->name('allocate');
            Route::get('/employees', [CrmStaffController::class, 'index'])->name('employees');
            Route::post('/employees', [CrmStaffController::class, 'store'])->name('employees.store');
            Route::post('/employees/{employee}', [CrmStaffController::class, 'update'])->name('employees.update');
            Route::post('/employees/{employee}/toggle', [CrmStaffController::class, 'toggle'])->name('employees.toggle');
        });

        Route::get('/desk', [CrmDeskController::class, 'index'])->name('desk');
        Route::get('/desk/members/{assignment}', [CrmDeskController::class, 'show'])->name('desk.show');
        Route::post('/desk/members/{assignment}/followups/{followup}', [CrmDeskController::class, 'saveFollowup'])
            ->whereNumber('followup')
            ->name('desk.followup');

        Route::get('/payments', [CrmConversionController::class, 'payments'])->name('payments');
        Route::get('/attendance-sync', [CrmConversionController::class, 'attendanceSync'])->name('attendance-sync');
        Route::redirect('/admissions', '/crm/payments');
    });
});
