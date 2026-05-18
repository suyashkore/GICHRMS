<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AttendanceController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\LeaveController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\RegularizationController;
use App\Http\Controllers\Api\V1\EmployeeController;
use App\Http\Controllers\Api\V1\WorkFromHomeController;
use App\Http\Controllers\Api\V1\OnDutyController;
use App\Http\Controllers\Api\V1\CompOffController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// API Version 1 routes
Route::prefix('v1')->group(function () {
    // Public routes
    Route::post('login', [AuthController::class, 'store']);
    Route::post('/forgot-password/send-otp', [AuthController::class, 'sendForgotPasswordOtp']);
    Route::post('/forgot-password/verify-otp', [AuthController::class, 'verifyForgotPasswordOtp']);
    Route::post('/forgot-password/reset', [AuthController::class, 'resetPassword']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);

        // attendance protected routes
        Route::prefix('attendance')->group(function () {
            Route::post('/punch-in', [AttendanceController::class, 'punchIn']);
            Route::post('/punch-out', [AttendanceController::class, 'punchOut']);

            Route::post('/break-in', [AttendanceController::class, 'breakIn']);
            Route::post('/break-out', [AttendanceController::class, 'breakOut']);

            Route::get('/today-status', [AttendanceController::class, 'todayStatus']);
            Route::post('/day-status', [AttendanceController::class, 'dayStatus']);
            Route::post('/calendar-view', [AttendanceController::class, 'calendarView']);
            Route::get('/monthly-list', [AttendanceController::class, 'monthlyList']);
            Route::post('/date-range-list', [AttendanceController::class, 'dateRangeList']);
            Route::post('/report-summary', [AttendanceController::class, 'reportSummary']);

            Route::post('/break-history', [AttendanceController::class, 'breakHistory']);
            Route::get('/travel-report', [AttendanceController::class, 'travelReport']);
            Route::post('/logs', [AttendanceController::class, 'rawLogs']);
        });

        Route::prefix('profile')->group(function () {
            Route::get('/', [ProfileController::class, 'getProfile']);
            Route::patch('/update', [ProfileController::class, 'updateProfile']);
            Route::post('/change-password', [ProfileController::class, 'changePassword']);
            Route::post('/change-photo', [ProfileController::class, 'changePhoto']);
        });

        Route::prefix('leave')->group(function () {
            Route::get('/types', [LeaveController::class, 'leaveTypes']);
            Route::get('/balance', [LeaveController::class, 'leaveBalance']);
            Route::get('/history', [LeaveController::class, 'leaveHistory']);
            Route::post('/apply', [LeaveController::class, 'applyLeave']);
            Route::post('/cancel', [LeaveController::class, 'cancelLeave']);

            Route::prefix('short')->group(function () {
                Route::post('/apply', [LeaveController::class, 'applyShortLeave']);
                Route::get('/history', [LeaveController::class, 'shortLeaveHistory']);
                Route::post('/cancel', [LeaveController::class, 'cancelShortLeave']);
            });
        });

        Route::prefix('regularization')->group(function () {
            Route::post('/apply', [RegularizationController::class, 'applyRegularization']);
            Route::get('/history', [RegularizationController::class, 'regularizationHistory']);
            Route::post('/cancel', [RegularizationController::class, 'cancelRegularization']);
        });

        Route::prefix('wfh')->group(function () {
            Route::get('/balance', [WorkFromHomeController::class, 'workFromHomeBalance']);
            Route::post('/apply', [WorkFromHomeController::class, 'applyWorkFromHome']);
            Route::get('/history', [WorkFromHomeController::class, 'workFromHomeHistory']);
            Route::post('/cancel', [WorkFromHomeController::class, 'cancelWorkFromHome']);
        });

        Route::prefix('onduty')->group(function () {
            Route::post('/apply', [OnDutyController::class, 'applyOnDuty']);
            Route::get('/history', [OnDutyController::class, 'onDutyHistory']);
            Route::post('/cancel', [OnDutyController::class, 'cancelOnDuty']);
        });

        Route::prefix('compoff')->group(function () {
            Route::post('/apply', [CompOffController::class, 'applyCompOff']);
            Route::get('/history', [CompOffController::class, 'compOffHistory']);
            Route::post('/cancel', [CompOffController::class, 'cancelCompOff']);
        });

        Route::prefix('employee')->group(function () {
            Route::get('/directory', [EmployeeController::class, 'directory']);
        });

        // Route::prefix('employee')->group(function () {
        //     Route::get('/employment-details', [ProfileController::class, 'employmentDetails']);
        //     Route::get('/shift-details', [ProfileController::class, 'shiftDetails']);
        //     Route::get('/manager-details', [ProfileController::class, 'managerDetails']);
        //     Route::get('/department-details', [ProfileController::class, 'departmentDetails']);
        // });

        // Route::prefix('security')->group(function () {
        //     Route::get('/login-history', [ProfileController::class, 'loginHistory']);
        //     Route::get('/active-sessions', [ProfileController::class, 'activeSessions']);
        //     Route::post('/logout-all-devices', [ProfileController::class, 'logoutAllDevices']);
        // });

        // Route::prefix('device')->group(function () {
        //     Route::post('/register', [ProfileController::class, 'registerDevice']);
        //     Route::post('/update-token', [ProfileController::class, 'updatePushToken']);
        //     Route::post('/remove', [ProfileController::class, 'removeDevice']);
        // });

        // Route::prefix('calendar')->group(function () {
        //     Route::get('/holiday-list', [ProfileController::class, 'holidayList']);
        //     Route::get('/week-offs', [ProfileController::class, 'weekOffList']);
        //     Route::get('/events', [ProfileController::class, 'eventList']);
        // });

        // Route::prefix('payroll')->group(function () {
        //     Route::get('/salary-slips', [ProfileController::class, 'salarySlipList']);
        //     Route::get('/salary-slip/{month}', [ProfileController::class, 'salarySlipDetail']);
        //     Route::get('/tax-summary', [ProfileController::class, 'taxSummary']);
        // });

        // Route::prefix('notifications')->group(function () {
        //     Route::get('/', [ProfileController::class, 'notificationList']);
        //     Route::post('/mark-read', [ProfileController::class, 'markRead']);
        //     Route::post('/mark-all-read', [ProfileController::class, 'markAllRead']);
        // });

    });
});
