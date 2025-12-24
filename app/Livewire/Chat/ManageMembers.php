<?php

namespace App\Livewire\Chat;

use App\Models\Sala;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class ManageMembers extends Component
{
    public $salaId;
    public $showModal = false;
    public $searchTerm = '';
    public $selectedUsers = [];

    public function mount()
    {
        $this->showModal = false;
    }

    #[On('openManageMembersModal')]
    public function openModal($salaId)
    {
        $this->salaId = $salaId;
        $this->showModal = true;
        $this->reset('searchTerm', 'selectedUsers');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset('searchTerm', 'selectedUsers', 'salaId');
    }

    public function toggleUser($userId)
    {
        if (in_array($userId, $this->selectedUsers)) {
            $this->selectedUsers = array_filter($this->selectedUsers, fn($id) => $id != $userId);
        } else {
            $this->selectedUsers[] = $userId;
        }
    }

    public function addMembers()
    {
        if (empty($this->selectedUsers)) {
            return;
        }

        $sala = Sala::find($this->salaId);

        if (!$sala) {
            return;
        }

        foreach ($this->selectedUsers as $userId) {
            if (!$sala->users()->where('user_id', $userId)->exists()) {
                $sala->users()->attach($userId);
            }
        }

        $this->dispatch('membersAdded', salaId: $this->salaId);
        $this->closeModal();

        session()->flash('message', 'Membros adicionados com sucesso!');
    }

    public function removeMember($userId)
    {
        $sala = Sala::find($this->salaId);

        if (!$sala || $sala->owner_id == $userId) {
            return;
        }

        $sala->users()->detach($userId);
        $this->dispatch('membersUpdated', salaId: $this->salaId);
    }

    public function getAvailableUsersProperty()
    {
        $sala = Sala::find($this->salaId);

        if (!$sala) {
            return collect();
        }

        // Obter IDs dos contactos do utilizador
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $contactIds = $user->contacts()->pluck('users.id');

        // Obter IDs dos membros atuais da sala
        $currentMemberIds = $sala->users()->pluck('users.id');

        // Mostrar apenas contactos que ainda não estão na sala
        $query = User::whereIn('id', $contactIds)->whereNotIn('id', $currentMemberIds);

        if ($this->searchTerm) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $this->searchTerm . '%');
            });
        }

        return $query->limit(10)->get();
    }

    public function getCurrentMembersProperty()
    {
        $sala = Sala::find($this->salaId);

        if (!$sala) {
            return collect();
        }

        return $sala->users;
    }

    public function render()
    {
        return view('livewire.chat.manage-members', [
            'availableUsers' => $this->availableUsers,
            'currentMembers' => $this->currentMembers,
        ]);
    }
}
