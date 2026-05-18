<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class Staff extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'tblstaff';

    protected $primaryKey = 'staffid';

    public $timestamps = false;

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->setTimezone(new \DateTimeZone('Asia/Kolkata'))
                    ->format('Y-m-d H:i:s');
    }

    protected $fillable = [
        'email',
        'username',
        'firstname',
        'lastname',
        'phonenumber',
        'password',
        'last_ip',
        'last_login',
        'last_activity',
        'admin',
        'role',
        'active',
        'token',
        'login_tokan',
        'app_access',
        'login_attempts',
        'blocked_until',
        'last_login_at'
    ];

    protected $hidden = [
        'password',
        'token',
        'login_tokan',
        'google_auth_secret',
        'two_factor_auth_code',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            // 'password' => 'hashed',
        ];
    }
}
