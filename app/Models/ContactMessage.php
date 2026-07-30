<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContactMessage extends Model
{
    use HasFactory;

    protected $table = 'contact_messages';

    protected $appends = ['display_timestamp'];

    protected $fillable = [
        'name',
        'email',
        'company',
        'subject',
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

    public function getDisplayTimestampAttribute(): \Illuminate\Support\Carbon|null
    {
        return $this->updated_at ?? $this->created_at;
    }

    public function replies()
    {
        return $this->morphMany(Reply::class, 'replyable')->orderBy('created_at');
    }
}
