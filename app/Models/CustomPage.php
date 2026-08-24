<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomPage extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'meta_title',
        'meta_description',
        'is_footer_link',
        'status',
    ];

    protected $casts = [
        'is_footer_link' => 'boolean',
    ];
}
