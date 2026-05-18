<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceDaily extends Model
{
    protected $table = 'attendance_daily';

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->setTimezone(new \DateTimeZone('Asia/Kolkata'))
                    ->format('Y-m-d H:i:s');
    }

    protected $fillable = [
        'staff_id',
        'employee_code',
        'attendance_date',
        'punch_in_time',
        'punch_out_time',
        'total_work_minutes',
        'total_break_minutes',
        'late_minutes',
        'overtime_minutes',
        'attendance_status',
        'remarks',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'punch_in_time' => 'datetime',
        'punch_out_time' => 'datetime',
        'total_work_minutes' => 'integer',
        'total_break_minutes' => 'integer',
        'late_minutes' => 'integer',
        'overtime_minutes' => 'integer',
    ];

    public function breaks()
    {
        return $this->hasMany(AttendanceBreak::class, 'attendance_daily_id');
    }

    public function audits()
    {
        return $this->hasMany(AttendanceAudit::class, 'attendance_daily_id');
    }
}