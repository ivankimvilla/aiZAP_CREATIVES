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
        $disk = config('filesystems.default', 'public');
        return $this->video_path ? $this->makeProtocolRelative(Storage::disk($disk)->url($this->video_path)) : null;
    }

    public function getImageUrlAttribute()
    {
        $disk = config('filesystems.default', 'public');
        return $this->image ? $this->makeProtocolRelative(Storage::disk($disk)->url($this->image)) : null;
    }

    private function makeProtocolRelative(string $url): string
    {
        return preg_replace('#^https?:#i', '', $url);
    }
}
