<?php

namespace App\Livewire\Contacts;

use App\Models\User;
use App\Models\Sala;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ContactsList extends Component
{
    public $searchTerm = '';
    public $showAddModal = false;
    public $selectedUsers = [];

    public function openAddModal()
    {
        $this->showAddModal = true;
        $this->reset('searchTerm', 'selectedUsers');
    }

    public function closeAddModal()
    {
        $this->showAddModal = false;
        $this->reset('searchTerm', 'selectedUsers');
    }

    public function toggleUser($userId)
    {
        if (in_array($userId, $this->selectedUsers)) {
            $this->selectedUsers = array_filter($this->selectedUsers, fn($id) => $id != $userId);
        } else {
            $this->selectedUsers[] = $userId;
        }
    }

    public function addContacts()
    {
        if (empty($this->selectedUsers)) {
            return;
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        foreach ($this->selectedUsers as $userId) {
            if (!$user->contacts()->where('contact_id', $userId)->exists()) {
                $user->contacts()->attach($userId);
            }
        }

        $this->closeAddModal();
        session()->flash('message', 'Contactos adicionados com sucesso!');
    }

    public function removeContact($contactId)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->contacts()->detach($contactId);
        session()->flash('message', 'Contacto removido!');
    }

    public function startDM($contactId)
    {
        $dm = Sala::findOrCreateDM(Auth::id(), $contactId);

        // Redirecionar para a página de chat e selecionar a DM
        $this->dispatch('roomCreated', salaId: $dm->id);
        return redirect()->route('chat')->with('selectedRoom', $dm->id);
    }

    public function getContactsProperty()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return $user->contacts()->get();
    }

    public function getAvailableUsersProperty()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $currentContactIds = $user->contacts()->pluck('users.id');
        $currentContactIds->push(Auth::id());

        $query = User::whereNotIn('id', $currentContactIds);

        if ($this->searchTerm) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $this->searchTerm . '%');
            });
        }

        return $query->limit(10)->get();
    }

    public function render()
    {
        return view('livewire.contacts.contacts-list', [
            'contacts' => $this->contacts,
            'availableUsers' => $this->availableUsers,
        ]);
    }
}
