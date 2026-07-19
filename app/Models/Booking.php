<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'message',
        'booking_utc',
        'booking_local',
        'booking_timezone',
        'booking_date',
        'booking_time',
        'service',
        'notes',
        'rescheduled_from',
        'status',
        'admin_notes',
        'meeting_duration',
    ];

    protected $casts = [
        'booking_utc' => 'datetime',
    ];
}
