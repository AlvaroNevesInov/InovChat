<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Sala;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('sala.{salaId}', function ($user, $salaId) {
    $sala = Sala::find($salaId);
    return $sala && $sala->users()->where('users.id', $user->id)->exists();
});
