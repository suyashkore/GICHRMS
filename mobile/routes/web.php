<?php

use App\Http\Controllers\Api\V1\LeaveController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/leave/apply-ci-web', [LeaveController::class, 'applyLeaveFromCI'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
    ->name('leave.apply-ci-web');
