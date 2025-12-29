<?php

namespace App\Livewire\Chat;

use App\Models\Sala;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class SalasList extends Component
{
    public $salaAtiva = null;

    /**
     * Define os listeners dinamicamente para todas as salas do usuário
     */
    protected function getListeners()
    {
        $listeners = [
            'mensagemEnviada' => 'atualizarLista',
            'mensagensLidas' => 'atualizarBadges',
            'roomCreated' => 'handleRoomCreated',
            'membersAdded' => 'handleMembersUpdated',
            'membersUpdated' => 'handleMembersUpdated',
        ];

        // Adiciona listener para cada sala do usuário
        if (Auth::check()) {
            $user = Auth::user();
            foreach ($user->salas as $sala) {
                $listeners["echo-private:sala.{$sala->id},MensagemEnviada"] = 'receberNovaMensagem';
            }
        }

        return $listeners;
    }

    public function atualizarLista()
    {
        // Força atualização da lista quando nova mensagem é enviada
    }

    public function receberNovaMensagem($event)
    {
        // Atualiza a lista quando recebe mensagem via Echo
        // O Livewire vai re-renderizar e recalcular unread_count
    }

    public function atualizarBadges()
    {
        // Atualiza badges após marcar mensagens como lidas
    }

    public function handleRoomCreated($salaId)
    {
        $this->salaAtiva = $salaId;
        $this->dispatch('salaSelected', salaId: $salaId);
    }

    public function handleMembersUpdated()
    {
        // Força atualização da lista
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
