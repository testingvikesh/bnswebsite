<?php

use App\Http\Controllers\ReportingController;
use Illuminate\Support\Facades\Route;

Route::prefix('reporting')->name('reporting.')->group(function () {
    Route::get('/', [ReportingController::class, 'index'])->name('index');
    Route::post('/login', [ReportingController::class, 'login'])->name('login');

    Route::middleware(['auth', 'sop.admin'])->group(function () {
        Route::get('/export', [ReportingController::class, 'export'])->name('export');
        Route::get('/payments', [ReportingController::class, 'payments'])->name('payments');
        Route::get('/payments/export', [ReportingController::class, 'exportPayments'])->name('payments.export');
        Route::get('/membership', [ReportingController::class, 'membership'])->name('membership');
        Route::get('/membership/export', [ReportingController::class, 'exportMembership'])->name('membership.export');
        Route::post('/membership/{membershipUpload}/trustee-verify', [ReportingController::class, 'trusteeVerifyMembership'])
            ->name('membership.trustee-verify');
        Route::post('/membership/{membershipUpload}/bns-verify', [ReportingController::class, 'bnsVerifyMembership'])
            ->name('membership.bns-verify');
        Route::post('/membership/{membershipUpload}/refund-otp', [ReportingController::class, 'sendMembershipRefundOtp'])
            ->name('membership.refund-otp');
        Route::post('/membership/{membershipUpload}/refund', [ReportingController::class, 'refundMembershipPayment'])
            ->name('membership.refund');
        Route::post('/membership/{membershipUpload}/refund-status', [ReportingController::class, 'checkMembershipRefundStatus'])
            ->name('membership.refund-status');
        Route::get('/attendance', [ReportingController::class, 'attendance'])->name('attendance');
        Route::get('/attendance/export', [ReportingController::class, 'exportAttendance'])->name('attendance.export');
        Route::get('/submissions/{inquiry}', [ReportingController::class, 'show'])->name('submissions.show');
        Route::post('/logout', [ReportingController::class, 'logout'])->name('logout');
    });
});
