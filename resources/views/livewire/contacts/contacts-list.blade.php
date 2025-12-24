<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-900">Os Meus Contactos</h2>
            <button
                wire:click="openAddModal"
                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg shadow transition"
            >
                + Adicionar Contacto
            </button>
        </div>

        <!-- Mensagem de sucesso -->
        @if (session()->has('message'))
            <div class="mx-6 mt-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('message') }}
            </div>
        @endif

        <!-- Lista de Contactos -->
        <div class="divide-y divide-gray-200">
            @forelse($contacts as $contact)
                <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-blue-500 flex items-center justify-center text-white text-lg font-semibold mr-4">
                            {{ strtoupper(substr($contact->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-base font-medium text-gray-900">{{ $contact->name }}</p>
                            <p class="text-sm text-gray-500">{{ $contact->email }}</p>
                        </div>
                    </div>
                    <button
                        wire:click="removeContact({{ $contact->id }})"
                        wire:confirm="Tem a certeza que quer remover este contacto?"
                        class="px-3 py-1 text-sm text-red-600 hover:text-red-800 hover:bg-red-50 rounded transition"
                    >
                        Remover
                    </button>
                </div>
            @empty
                <div class="px-6 py-12 text-center text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <p class="mt-4 text-lg font-medium">Ainda não tem contactos</p>
                    <p class="mt-2 text-sm">Clique em "Adicionar Contacto" para começar</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Modal Adicionar Contactos -->
    @if($showAddModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeAddModal"></div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            Adicionar Contactos
                        </h3>

                        <!-- Pesquisa -->
                        <input
                            type="text"
                            wire:model.live="searchTerm"
                            placeholder="Pesquisar utilizadores..."
                            class="w-full mb-3 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        >

                        <!-- Lista de utilizadores -->
                        <div class="max-h-96 overflow-y-auto border rounded-lg">
                            @forelse($availableUsers as $user)
                                <div
                                    wire:click="toggleUser({{ $user->id }})"
                                    class="flex items-center p-3 hover:bg-gray-50 cursor-pointer {{ in_array($user->id, $selectedUsers) ? 'bg-blue-50' : '' }}"
                                >
                                    <div class="flex items-center flex-1">
                                        <div class="w-10 h-10 rounded-full bg-gray-400 flex items-center justify-center text-white text-sm font-semibold mr-3">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                    @if(in_array($user->id, $selectedUsers))
                                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    @endif
                                </div>
                            @empty
                                <p class="p-4 text-center text-gray-500">
                                    @if($searchTerm)
                                        Nenhum utilizador encontrado
                                    @else
                                        Todos os utilizadores já são seus contactos
                                    @endif
                                </p>
                            @endforelse
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button
                            wire:click="addContacts"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                            {{ empty($selectedUsers) ? 'disabled' : '' }}
                        >
                            Adicionar Selecionados
                        </button>
                        <button
                            wire:click="closeAddModal"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
