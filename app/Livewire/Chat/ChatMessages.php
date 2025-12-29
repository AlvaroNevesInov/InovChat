<?php

namespace App\Livewire\Chat;

use App\Models\Sala;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class ChatMessages extends Component
{
    public ?int $salaId = null;
    public ?Sala $sala = null;
    public array $usersTyping = [];

    public function mount(?int $salaId = null): void
    {
        $this->salaId = $salaId;

        if ($this->salaId) {
            $this->sala = Sala::find($this->salaId);
        }
    }

    #[On('salaSelected')]
    public function atualizarSala(int $salaId): void
    {
        $this->salaId = $salaId;
        $this->sala = Sala::find($salaId);
        $this->usersTyping = [];

        $this->marcarMensagensComoLidas();
    }

    #[On('mensagemEnviada')]
    public function atualizarMensagens(): void
    {
        $this->marcarMensagensComoLidas();
    }

    #[On('echo-private:sala.{salaId},.MensagemEnviada')]
    public function receberMensagem(array $event): void
    {
        $this->marcarMensagensComoLidas();
    }
    #[On('echo-private:sala.{salaId},.UserTyping')]

    #[On('echo:sala.{salaId},.UserTyping')]
    public function userTyping(array $event): void
    {
        // Não mostrar o próprio utilizador a digitar
        if ($event['user_id'] === Auth::id()) {
            return;
        }

        $this->usersTyping[$event['user_id']] = [
            'name' => $event['user_name'],
            'timestamp' => now()->timestamp,
        ];

        // Limpar após 3 segundos
        $this->dispatch('clearTypingIndicator', userId: $event['user_id']);
    }

    public function clearTypingUser(int $userId): void
    {
        unset($this->usersTyping[$userId]);
    }

    protected function marcarMensagensComoLidas(): void
    {
        if ($this->sala) {
            $this->sala->mensagens()
                ->where('user_id', '!=', Auth::id())
                ->where('lida', false)
                ->update(['lida' => true]);
        }
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

        // Limpar utilizadores que não digitam há mais de 3 segundos
        $currentTime = now()->timestamp;
        $this->usersTyping = array_filter(
            $this->usersTyping,
            fn ($user) => ($currentTime - $user['timestamp']) < 3
        );

        return view('livewire.chat.chat-messages', [
            'mensagens' => $mensagens,
        ]);
    }
}
