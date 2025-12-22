<?php

namespace App\Livewire\Chat;

use App\Models\Sala;
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
        $salas = auth()->user()->salas()->with('ultimaMensagem.user')->get();

        return view('livewire.chat.salas-list', [
            'salas' => $salas,
        ]);
    }
}
