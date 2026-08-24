<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $fillable = [
        'session_id',
        'sender_type',
        'sender_name',
        'message',
        'message_type',
        'payload',
        'is_read',
    ];

    protected $casts = [
        'payload' => 'array',
        'is_read' => 'boolean',
    ];

    public function session()
    {
        return $this->belongsTo(ChatSession::class, 'session_id', 'session_id');
    }
}
