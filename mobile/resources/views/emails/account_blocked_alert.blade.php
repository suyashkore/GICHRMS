<h2>Account Blocked Alert</h2>

<p>Hello {{ $user->firstname ?? $user->email }},</p>

<p>Your account has been blocked due to multiple failed login attempts.</p>

<p><strong>Email:</strong> {{ $user->email }}</p>
<p><strong>IP:</strong> {{ $requestData['ip'] ?? request()->ip() }}</p>
<p><strong>Device:</strong> {{ $requestData['device_type'] ?? 'Unknown' }}</p>
<p><strong>Blocked Until:</strong> {{ $user->blocked_until }}</p>
<p>If this was not you, please contact our support team immediately.</p>