<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContactMessage extends Model
{
    use HasFactory;

    protected $table = 'contact_messages';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'message',
        'request_type',
        'package',
        'status',
        'reply_message',
        'reply_subject',
        'replied_at',
        'email_status',
        'seen',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
        'seen' => 'boolean',
    ];
}
