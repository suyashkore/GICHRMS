<?php

use App\Http\Controllers\Api\V1\LeaveController;
use App\Http\Controllers\Api\V1\RegularizationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/leave/apply-ci-web', [LeaveController::class, 'applyLeaveFromCI'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
    ->name('leave.apply-ci-web');

Route::get('/leave/ci-balance/{email}', [LeaveController::class, 'leaveBalanceFromCI'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
    ->name('leave.ci-balance');

Route::post('/regularization/apply-ci-web', [RegularizationController::class, 'applyRegularizationFromCI'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
    ->name('regularization.apply-ci-web');
