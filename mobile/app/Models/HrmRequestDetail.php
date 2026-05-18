<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrmRequestDetail extends Model
{
    protected $table = 'hrm_request_details';

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->setTimezone(new \DateTimeZone('Asia/Kolkata'))
                    ->format('Y-m-d H:i:s');
    }

    protected $fillable = [
        'request_id',
        'request_data',
    ];

    protected $casts = [
        'request_data' => 'array',
    ];

    public function request()
    {
        return $this->belongsTo(HrmEmployeeRequest::class, 'request_id');
    }
}