<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reply extends Model
{
    use HasFactory;

    protected $table = 'replies';

    protected $fillable = [
        'replyable_type',
        'replyable_id',
        'author',
        'message',
    ];

    public function replyable()
    {
        return $this->morphTo();
    }
}
