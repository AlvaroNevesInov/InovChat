<?php

namespace App\Livewire\Chat;

use Livewire\Component;

class ChatContainer extends Component
{
    public $salaAtiva = null;

    protected $listeners = ['salaSelected' => 'selecionarSala'];

    public function selecionarSala($salaId)
    {
        $this->salaAtiva = $salaId;
    }

    public function render()
    {
        return view('livewire.chat.chat-container');
    }
}
