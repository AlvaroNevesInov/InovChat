<?php

namespace App\Livewire\Chat;

use App\Models\Mensagem;
use App\Events\MensagemEnviada;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class SendMessage extends Component
{
    public $salaId = null;
    public $conteudo = '';

    #[On('salaSelected')]

    public function atualizarSala($salaId)
    {
        $this->salaId = $salaId;
    }

    public function enviarMensagem()
    {
        $this->validate([
            'conteudo' => 'required|string|max:1000',
        ]);

        if (!$this->salaId) {
            return;
        }

        $mensagem = Mensagem::create([

            'user_id' => Auth::id(),
            'sala_id' => $this->salaId,
            'conteudo' => $this->conteudo,
        ]);

        event(new MensagemEnviada($mensagem));
        $this->dispatch('mensagemEnviada');
        $this->conteudo = '';
    }

    public function render()
    {
        return view('livewire.chat.send-message');
    }
}
