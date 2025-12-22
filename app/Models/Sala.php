<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sala extends Model
{
    protected $fillable = [
        'nome',
        'avatar',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function mensagens()
    {
        return $this->hasMany(Mensagem::class);
    }

    public function ultimaMensagem()
    {
        return $this->hasOne(Mensagem::class)->latestOfMany();
    }
}
