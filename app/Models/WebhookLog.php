<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebhookLog extends Model
{
    use HasFactory;

    /**
     * Atribut yang diizinkan untuk diisi secara massal.
     */
    protected $fillable = [
        'session_id',
        'message_id',
        'sender_jid',
        'group_jid',
        'sender_name',
        'message_text',
        'message_timestamp',
        'full_payload',
    ];

    /**
     * Mengubah tipe data atribut secara otomatis.
     */
    protected $casts = [
        // Otomatis mengubah string JSON menjadi array PHP saat diakses
        'full_payload' => 'array',
        // Otomatis mengubah timestamp menjadi objek Carbon
        'message_timestamp' => 'datetime',
    ];
}
