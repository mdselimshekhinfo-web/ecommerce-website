<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSection extends Model
{
    protected $fillable = [
        'section_key',
        'name',
        'is_active',
        'sort_order',
        'content',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'content' => 'array',
    ];

    public static function getContent($key, $default = [])
    {
        $section = static::where('section_key', $key)->first();
        if (!$section || !$section->is_active) {
            return null;
        }
        return array_merge($default, $section->content ?? []);
    }
}
