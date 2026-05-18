<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceAudit extends Model
{
    protected $table = 'attendance_audit';

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->setTimezone(new \DateTimeZone('Asia/Kolkata'))
                    ->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'staff_id',
        'attendance_daily_id',
        'action_type',
        'old_punch_in_time',
        'new_punch_in_time',
        'old_punch_out_time',
        'new_punch_out_time',
        'updated_by',
        'remarks',
    ];

    protected $casts = [
        'old_punch_in_time' => 'datetime',
        'new_punch_in_time' => 'datetime',
        'old_punch_out_time' => 'datetime',
        'new_punch_out_time' => 'datetime',
    ];

    public function attendance()
    {
        return $this->belongsTo(AttendanceDaily::class, 'attendance_daily_id');
    }
}