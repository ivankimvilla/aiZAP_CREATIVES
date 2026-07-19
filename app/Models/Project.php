<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->video_path ? asset('storage/' . $this->video_path) : null;
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
