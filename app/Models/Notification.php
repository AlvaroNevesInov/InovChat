<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'mensagem_id',
        'sala_id',
        'type',
        'lida',
    ];

    protected $casts = [
        'lida' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mensagem()
    {
        return $this->belongsTo(Mensagem::class);
    }

    public function sala()
    {
        return $this->belongsTo(Sala::class);
    }
}
