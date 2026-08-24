<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatSession extends Model
{
    protected $fillable = [
        'session_id',
        'user_id',
        'customer_name',
        'customer_phone',
        'current_page',
        'cart_summary',
        'is_assigned_to_human',
        'agent_id',
        'status',
        'last_activity_at',
    ];

    protected $casts = [
        'cart_summary' => 'array',
        'is_assigned_to_human' => 'boolean',
        'last_activity_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'session_id', 'session_id')->orderBy('created_at', 'asc');
    }

    public function latestMessage()
    {
        return $this->hasOne(ChatMessage::class, 'session_id', 'session_id')->latestOfMany();
    }
}
