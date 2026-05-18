<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrmEmployeeLeaveBalance extends Model
{
    protected $table = 'hrm_employee_leave_balances';

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->setTimezone(new \DateTimeZone('Asia/Kolkata'))
                    ->format('Y-m-d H:i:s');
    }
    
    protected $fillable = [
        'staff_id',
        'leave_type_id',
        'leave_year',
        'allocated',
        'used',
        'remaining',
    ];

    protected $casts = [
        'allocated' => 'decimal:2',
        'used' => 'decimal:2',
        'remaining' => 'decimal:2',
    ];

    public function leaveType()
    {
        return $this->belongsTo(HrmLeaveType::class, 'leave_type_id');
    }
}