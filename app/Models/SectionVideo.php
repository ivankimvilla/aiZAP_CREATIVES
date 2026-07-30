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
        $disk = config('filesystems.default', 'public');
        return $this->video_path ? $this->makeProtocolRelative(Storage::disk($disk)->url($this->video_path)) : null;
    }

    public function getPosterUrlAttribute()
    {
        $disk = config('filesystems.default', 'public');
        return $this->poster_path ? $this->makeProtocolRelative(Storage::disk($disk)->url($this->poster_path)) : null;
    }

    private function makeProtocolRelative(string $url): string
    {
        return preg_replace('#^https?:#i', '', $url);
    }
}
