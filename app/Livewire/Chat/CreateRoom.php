<?php

namespace App\Livewire\Chat;

use App\Models\Sala;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateRoom extends Component
{
    use WithFileUploads;

    public $nome = '';
    public $avatar;
    public $showModal = false;

    protected $rules = [
        'nome' => 'required|min:3|max:50',
        'avatar' => 'nullable|image|max:2048',
    ];

    protected $messages = [
        'nome.required' => 'O nome da sala é obrigatório',
        'nome.min' => 'O nome deve ter no mínimo 3 caracteres',
        'nome.max' => 'O nome deve ter no máximo 50 caracteres',
        'avatar.image' => 'O arquivo deve ser uma imagem',
        'avatar.max' => 'A imagem não pode ser maior que 2MB',
    ];

    public function mount()
    {
        $this->showModal = false;
    }

    public function openModal()
    {
        $this->showModal = true;
        $this->reset('nome', 'avatar');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset('nome', 'avatar');
        $this->resetValidation();
    }

    public function createRoom()
    {
        $this->validate();

        $avatarPath = null;
        if ($this->avatar) {
            $avatarPath = $this->avatar->store('room-avatars', 'public');
        }

        $sala = Sala::create([
            'nome' => $this->nome,
            'avatar' => $avatarPath,
            'owner_id' => Auth::id(),
        ]);

        $sala->users()->attach(Auth::id());

        $this->dispatch('roomCreated', salaId: $sala->id);
        $this->closeModal();

        session()->flash('message', 'Sala criada com sucesso!');
    }

    public function render()
    {
        return view('livewire.chat.create-room');
    }
}
