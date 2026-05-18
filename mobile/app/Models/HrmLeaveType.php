<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrmLeaveType extends Model
{
    protected $table = 'hrm_leave_types';

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->setTimezone(new \DateTimeZone('Asia/Kolkata'))
                    ->format('Y-m-d H:i:s');
    }

    protected $fillable = [
        'code',
        'name',
        'yearly_limit',
        'is_paid',
        'carry_forward',
        'is_active',
    ];

    public function balances()
    {
        return $this->hasMany(HrmEmployeeLeaveBalance::class, 'leave_type_id');
    }
}