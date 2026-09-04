<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['institute.api', 'throttle:30,1'])->prefix('institute')->group(function () {
    Route::get('sessions', [\App\Http\Controllers\Api\InstituteAttendanceController::class, 'sessions']);
    Route::post('attendance', [\App\Http\Controllers\Api\InstituteAttendanceController::class, 'mark']);
});
