<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];
    protected $casts = [
        'value' => 'array',
    ];

    public static function get($key, $default = null)
    {
        $row = static::where('key', $key)->first();
        if (!$row || $row->value === null) return $default;
        return $row->value;
    }

    public static function set($key, $value)
    {
        $row = static::updateOrCreate(['key' => $key], ['value' => $value]);
        return $row;
    }
}
