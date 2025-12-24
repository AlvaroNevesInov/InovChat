<div>
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                            Gerir Membros
                        </h3>

                        <!-- Membros Atuais -->
                        <div class="mb-6">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Membros Atuais</h4>
                            <div class="max-h-40 overflow-y-auto border rounded-lg">
                                @forelse($currentMembers as $member)
                                    <div class="flex items-center justify-between p-3 hover:bg-gray-50">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-sm font-semibold mr-3">
                                                {{ strtoupper(substr($member->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $member->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $member->email }}</p>
                                            </div>
                                        </div>
                                        @if($salaId && $member->id != Auth::id())
                                            @php
                                                $sala = \App\Models\Sala::find($salaId);
                                            @endphp
                                            @if($sala && $sala->owner_id != $member->id)
                                                <button
                                                    wire:click="removeMember({{ $member->id }})"
                                                    class="text-red-600 hover:text-red-800 text-sm"
                                                >
                                                    Remover
                                                </button>
                                            @else
                                                <span class="text-xs text-gray-500">Criador</span>
                                            @endif
                                        @endif
                                    </div>
                                @empty
                                    <p class="p-3 text-sm text-gray-500">Nenhum membro na sala</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Adicionar Novos Membros -->
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Adicionar Membros</h4>
                            <input
                                type="text"
                                wire:model.live="searchTerm"
                                placeholder="Pesquisar utilizadores..."
                                class="w-full mb-3 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            >

                            <div class="max-h-60 overflow-y-auto border rounded-lg">
                                @forelse($availableUsers as $user)
                                    <div
                                        wire:click="toggleUser({{ $user->id }})"
                                        class="flex items-center p-3 hover:bg-gray-50 cursor-pointer {{ in_array($user->id, $selectedUsers) ? 'bg-blue-50' : '' }}"
                                    >
                                        <div class="flex items-center flex-1">
                                            <div class="w-8 h-8 rounded-full bg-gray-400 flex items-center justify-center text-white text-sm font-semibold mr-3">
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
                                    <p class="p-3 text-sm text-gray-500">
                                        @if($searchTerm)
                                            Nenhum utilizador encontrado
                                        @else
                                            Todos os utilizadores já estão na sala
                                        @endif
                                    </p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button
                            wire:click="addMembers"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                            {{ empty($selectedUsers) ? 'disabled' : '' }}
                        >
                            Adicionar Selecionados
                        </button>
                        <button
                            wire:click="closeModal"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Fechar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
