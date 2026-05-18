<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrmEmployeeRequest extends Model
{
    protected $table = 'hrm_employee_requests';

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->setTimezone(new \DateTimeZone('Asia/Kolkata'))
                    ->format('Y-m-d H:i:s');
    }

    protected $fillable = [
        'staff_id',
        'request_type_id',
        'request_date',
        'from_date',
        'to_date',
        'from_time',
        'to_time',
        'total_days',
        'total_hours',
        'amount',
        'status',
        'reason',
        'approved_by',
        'approved_at',
        'approval_remarks',
        'attachment',
    ];

    protected $casts = [
        'request_date' => 'date',
        'from_date' => 'date',
        'to_date' => 'date',
        'approved_at' => 'datetime',
        'total_days' => 'decimal:2',
        'total_hours' => 'decimal:2',
        'amount' => 'decimal:2',
    ];

    public function requestType()
    {
        return $this->belongsTo(HrmRequestType::class, 'request_type_id');
    }

    public function details()
    {
        return $this->hasOne(HrmRequestDetail::class, 'request_id');
    }

    public function approvals()
    {
        return $this->hasMany(HrmRequestApproval::class, 'request_id');
    }
}