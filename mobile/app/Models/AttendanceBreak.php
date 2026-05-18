<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceBreak extends Model
{
    protected $table = 'attendance_breaks';

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->setTimezone(new \DateTimeZone('Asia/Kolkata'))
                    ->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'staff_id',
        'attendance_daily_id',
        'break_in_time',
        'break_out_time',
        'break_minutes',
        'break_type',
    ];

    protected $casts = [
        'break_in_time' => 'datetime',
        'break_out_time' => 'datetime',
        'break_minutes' => 'integer',
    ];

    public function attendance()
    {
        return $this->belongsTo(AttendanceDaily::class, 'attendance_daily_id');
    }
}