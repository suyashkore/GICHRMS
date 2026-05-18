<h2>Login Failed Alert</h2>

<p>Hello {{ $user->firstname ?? $user->email }},</p>

<p>Someone tried to login to your account with wrong password.</p>

<p><strong>Email:</strong> {{ $user->email }}</p>
<p><strong>IP:</strong> {{ $requestData['ip'] ?? request()->ip() }}</p>
<p><strong>Device:</strong> {{ $requestData['device_type'] ?? 'Unknown' }}</p>

<p>If this was not you, please change your password immediately.</p>