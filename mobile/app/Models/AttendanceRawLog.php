<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceRawLog extends Model
{
    protected $table = 'attendance_raw_logs';

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->setTimezone(new \DateTimeZone('Asia/Kolkata'))
                    ->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'staff_id',
        'employee_code',
        'log_time',
        'direction',
        'device_sn',
        'device_name',
        'verification_type',
        'latitude',
        'longitude',
        'battery_level',
        'gps_status',
        'raw_payload',
    ];

    protected $casts = [
        'log_time' => 'datetime',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'battery_level' => 'integer',
    ];

    public function dailyAttendance()
    {
        return $this->belongsTo(AttendanceDaily::class, 'staff_id', 'staff_id');
    }
}