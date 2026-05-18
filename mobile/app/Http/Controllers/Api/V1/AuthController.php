<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// use App\Models\User;
use App\Models\Staff;
use App\Jobs\SendFailedLoginAlertJob;
use App\Jobs\SendAccountBlockedAlertJob;
use App\Jobs\SendForgotPasswordOtpJob;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
  public function __construct() {
    // 
  }

  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    // return $request->all();
    $request->validate([
      'username'     => 'required|string',
      'password'     => 'required|string|min:6',
      'device_id'    => 'required|string|max:255',
      // 'device_model' => 'required|string|max:255',
      'device_type'  => 'required|in:android,ios,web',
      // 'os_version'   => 'required|string|max:50',
      // 'app_version'  => 'required|string|max:50',
      // 'ip_address'   => 'required|ip',
    ]);

    try {
      $username = $request->username;

      // check username is email or mobile present in user table
      $user = Staff::where('email', $username)
        ->orWhere('phonenumber', $username)
        ->first();

      if (!$user) {
        return response()->json([
          'status' => false,
          'message' => 'User not found'
        ], 404);
      }

      // if account is blocked then send email to user with unblock time and ip address and device details
      if ($user->blocked_until && now()->lt($user->blocked_until)) {
        SendAccountBlockedAlertJob::dispatch($user, $request->all());

        return response()->json([
          'status' => false,
          'message' => 'Account blocked till '.$user->blocked_until
        ], 423);
      }

      // check password is correct or not (not match then track login attempt. if more than 5 then block user for 15 minutes)
      $hashedPassword = $user->password;

      // convert old CI3 bcrypt prefix
      if (strpos($hashedPassword, '$2a$') === 0) {
          $hashedPassword = '$2y$' . substr($hashedPassword, 4);
      }

      if (!Hash::check($request->password, $hashedPassword)) {
        $user->increment('login_attempts');

        SendFailedLoginAlertJob::dispatch($user, $request->all());

        if ($user->login_attempts >= 5) {
          $user->blocked_until = now()->addMinutes(1);
          $user->save();

          SendAccountBlockedAlertJob::dispatch($user, $request->all());

          return response()->json([
            'status' => false,
            'message' => 'Account blocked for 1 minute'
          ], 423);
        }

        return response()->json([
          'status' => false,
          'message' => 'Invalid password',
          'attempts_left' => 5 - $user->login_attempts
        ], 401);
      }

      $user->update([
        'login_attempts' => 0,
        'blocked_until' => null,
        'last_login_at' => now()
      ]);

      // create access token for user with device type as token name
      $token = $user->createToken($request->device_type)->plainTextToken;

      return response()->json([
        'status' => true,
        'message' => 'Login successful',
        'data' => [
          'user' => $user,
          'access_token' => $token,
          'device_details' => [
            'device_id' => $request->device_id,
            'device_model' => $request->device_model,
            'device_type' => $request->device_type,
            'os_version' => $request->os_version,
            'app_version' => $request->app_version,
          ],
          'ip_address' => $request->ip_address,
          'login_time' => now()->toDateTimeString()
        ]
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'status' => false,
        'message' => 'Something went wrong',
        'error' => $e->getMessage()
      ], 500);
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    //
  }

  public function sendForgotPasswordOtp(Request $request)
  {
    $request->validate([
      'email' => 'required|email'
    ]);

    $user = Staff::where('email', $request->email)->first();

    if (!$user) {
      return response()->json([
        'status' => false,
        'message' => 'Email not registered.'
      ], 404);
    }

    $otp = rand(100000, 999999);

    $user->forgot_otp = $otp;
    $user->forgot_otp_expires_at = Carbon::now()->addMinutes(10);
    $user->forgot_otp_verified = 0;
    $user->save();

    SendForgotPasswordOtpJob::dispatch($user, $otp);

    return response()->json([
      'status' => true,
      'message' => 'OTP sent successfully to your email.',
      'temp_otp' => $otp // Remove this in production, only for testing
    ], 200);
  }

  public function verifyForgotPasswordOtp(Request $request)
  {
    $request->validate([
      'email' => 'required|email',
      'otp' => 'required|digits:6'
    ]);

    $user = Staff::where('email', $request->email)
              ->where('forgot_otp', $request->otp)
              ->first();

    if (!$user) {
      return response()->json([
        'status' => false,
        'message' => 'Invalid OTP.'
      ], 400);
    }

    if (Carbon::now()->gt($user->forgot_otp_expires_at)) {
      return response()->json([
        'status' => false,
        'message' => 'OTP expired.'
      ], 400);
    }

    $user->forgot_otp_verified = 1;
    $user->save();

    return response()->json([
      'status' => true,
      'message' => 'OTP verified successfully.'
    ]);
  }

  public function resetPassword(Request $request)
  {
    $request->validate([
      'email' => 'required|email',
      'password' => 'required|min:6|confirmed'
    ]);

    $user = Staff::where('email', $request->email)
              ->where('forgot_otp_verified', 1)
              ->first();

    if (!$user) {
        return response()->json([
          'status' => false,
          'message' => 'OTP verification required.'
        ], 400);
    }

    $password = $request->password;
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT, [
      'cost' => 8
    ]);
    $hashedPassword = '$2a$' . substr($hashedPassword, 4);

    $user->password = $hashedPassword;
    $user->forgot_otp = null;
    $user->forgot_otp_expires_at = null;
    $user->forgot_otp_verified = 0;
    $user->save();

    return response()->json([
      'status' => true,
      'message' => 'Password reset successfully.'
    ], 200);
  }

  public function logout(Request $request)
  {
    $user = $request->user();

    if (!$user) {
      return response()->json([
        'status' => false,
        'message' => 'Unauthorized'
      ], 401);
    }

    // Delete only current token
    $user->currentAccessToken()->delete();

    return response()->json([
      'status' => true,
      'message' => 'Logout successful'
    ], 200);
  }
}
