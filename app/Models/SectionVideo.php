<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SectionVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'video_path',
        'poster_path',
    ];

    public function getVideoUrlAttribute()
    {
        return $this->video_path ? Storage::disk('public')->url($this->video_path) : null;
    }

    public function getPosterUrlAttribute()
    {
        return $this->poster_path ? Storage::disk('public')->url($this->poster_path) : null;
    }
}
