<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PackageRequest extends Model
{
    use HasFactory;

    protected $table = 'package_requests';

    protected $appends = ['display_timestamp'];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'message',
        'package',
        'status',
        'seen',
        'reply_message',
        'replied_at',
        'email_status',
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
