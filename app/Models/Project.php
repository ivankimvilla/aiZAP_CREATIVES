<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'image',
        'video_path',
        'featured',
        'categories',
    ];

    protected $casts = [
        'featured' => 'boolean',
        'categories' => 'array',
    ];

    public function getVideoUrlAttribute()
    {
        return $this->video_path ? $this->makeProtocolRelative(Storage::disk('public')->url($this->video_path)) : null;
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? $this->makeProtocolRelative(Storage::disk('public')->url($this->image)) : null;
    }

    private function makeProtocolRelative(string $url): string
    {
        return preg_replace('#^https?:#i', '', $url);
    }
}
