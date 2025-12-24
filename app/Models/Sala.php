<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sala extends Model
{
    protected $fillable = [
        'nome',
        'avatar',
        'owner_id',
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

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
