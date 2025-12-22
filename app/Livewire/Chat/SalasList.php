<?php

namespace App\Livewire\Chat;

use App\Models\Sala;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class SalasList extends Component
{
    public $salaAtiva = null;

    #[On('mensagemEnviada')]
    public function atualizarLista()
    {
        // Força atualização da lista quando nova mensagem é enviada
    }

    public function selecionarSala($salaId)
    {
        $this->salaAtiva = $salaId;
        $this->dispatch('salaSelected', salaId: $salaId);
    }

    public function render()
    {
        $salas = collect();

        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $salas = $user->salas()->with('ultimaMensagem.user')->get();
        }

        return view('livewire.chat.salas-list', [
            'salas' => $salas,
        ]);
    }
}
