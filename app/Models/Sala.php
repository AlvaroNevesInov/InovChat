<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Sala extends Model
{
    protected $fillable = [
        'nome',
        'avatar',
        'owner_id',
        'tipo',
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

    public function mensagensNaoLidas()
    {
        return $this->hasMany(Mensagem::class)
            ->where('lida', false)
            ->where('user_id', '!=', Auth::id());
    }

    public function getUnreadCountAttribute()
    {
        return $this->mensagensNaoLidas()->count();
    }

    public function isDM()
    {
        return $this->tipo === 'dm';
    }

    public function isGroup()
    {
        return $this->tipo === 'group';
    }

    /**
     * Retorna o outro usuário em uma DM (não o usuário logado)
     */
    public function getOtherUserInDM()
    {
        if (!$this->isDM()) {
            return null;
        }

        return $this->users()->where('users.id', '!=', Auth::id())->first();
    }

    /**
     * Busca ou cria uma DM entre dois usuários
     */
    public static function findOrCreateDM($userId1, $userId2)
    {
        // Buscar DM existente entre os dois usuários
        $dm = self::where('tipo', 'dm')
            ->whereHas('users', function ($query) use ($userId1) {
                $query->where('users.id', $userId1);
            })
            ->whereHas('users', function ($query) use ($userId2) {
                $query->where('users.id', $userId2);
            })
            ->first();

        if ($dm) {
            return $dm;
        }

        // Criar nova DM
        $user1 = User::find($userId1);
        $user2 = User::find($userId2);

        $dm = self::create([
            'nome' => $user1->name . ' & ' . $user2->name,
            'tipo' => 'dm',
            'owner_id' => $userId1,
        ]);

        $dm->users()->attach([$userId1, $userId2]);

        return $dm;
    }
}
