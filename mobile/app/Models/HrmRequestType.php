<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrmRequestType extends Model
{
    protected $table = 'hrm_request_types';

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->setTimezone(new \DateTimeZone('Asia/Kolkata'))
                    ->format('Y-m-d H:i:s');
    }

    protected $fillable = [
        'code',
        'name',
        'icon',
        'color_code',
        'sort_order',
        'is_active',
    ];

    public function requests()
    {
        return $this->hasMany(HrmEmployeeRequest::class, 'request_type_id');
    }
}