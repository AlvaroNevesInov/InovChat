<?php

namespace App\Livewire\Chat;

use App\Models\Mensagem;
use App\Models\Notification;
use App\Events\MensagemEnviada;
use App\Events\UserTyping;
use App\Events\NotificationSent;
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

    public function notifyTyping()
    {
        if (!$this->salaId || !Auth::check()) {
            return;
        }

        event(new UserTyping(
            Auth::id(),
            Auth::user()->name,
            $this->salaId
        ));
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

        // Detectar mentions e criar notificações
        $mentionedUsers = get_mentioned_users($this->conteudo);

        foreach ($mentionedUsers as $user) {
            // Não notificar o próprio usuário
            if ($user->id !== Auth::id()) {
                $notification = Notification::create([
                    'user_id' => $user->id,
                    'mensagem_id' => $mensagem->id,
                    'sala_id' => $this->salaId,
                    'type' => 'mention',
                ]);

                event(new NotificationSent($notification));
            }
        }

        event(new MensagemEnviada($mensagem));
        $this->dispatch('mensagemEnviada');
        $this->conteudo = '';
    }

    public function render()
    {
        return view('livewire.chat.send-message');
    }
}
