<h2>Forgot Password OTP</h2>

<p>Hello {{ $user->firstname ?? $user->email }},</p>

<p>You have requested a password reset. Please use the following OTP to reset your password:</p>
<p><strong>OTP:</strong> {{ $otp }}</p>
<p>This OTP is valid for 15 minutes. If you did not request a password reset, please ignore this email.</p>
<p>If you have any questions or need assistance, please contact our support team.</p>