<?php

namespace App\Livewire\Chat;

use App\Models\Sala;
use Livewire\Component;
use Livewire\Attributes\On;

class ChatMessages extends Component
{
    public $salaId = null;
    public $sala = null;

    public function mount($salaId = null)
    {
        $this->salaId = $salaId;
        if ($this->salaId) {
            $this->sala = Sala::find($this->salaId);
        }
    }

    #[On('salaSelected')]
    public function atualizarSala($salaId)
    {
        $this->salaId = $salaId;
        $this->sala = Sala::find($salaId);
    }

    #[On('mensagemEnviada')]
    public function atualizarMensagens()
    {
        // Força atualização das mensagens
    }

    #[On('echo:sala.{salaId},MensagemEnviada')]
    public function receberMensagem($event)
    {
        // Livewire irá re-renderizar automaticamente
    }

    public function render()
    {
        $mensagens = [];

        if ($this->sala) {
            $mensagens = $this->sala->mensagens()
                ->with('user')
                ->orderBy('created_at', 'asc')
                ->get();

        }

        return view('livewire.chat.chat-messages', [
            'mensagens' => $mensagens,
        ]);
    }
}
