<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelReport extends Model
{
    protected $table = 'travel_reports';

    protected $fillable = [
        'staff_id',
        'travel_date',
        'latitude',
        'longitude',
        'location_name',
        'gps_status',
        'battery_level',
        'device_information',
    ];

    protected $casts = [
        'travel_date' => 'date',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'battery_level' => 'integer',
    ];
}