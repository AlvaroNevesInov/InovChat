<div>

    <!-- Botão Criar Nova Sala -->
    <div class="p-4">

        <!-- Componente Livewire -->
        <livewire:chat.create-room />
    </div>

    <!-- Lista de Salas -->
    <div class="overflow-y-auto">
        @forelse($salas as $sala)
            <div
                class="flex items-center p-4 hover:bg-gray-100 transition {{ $salaAtiva == $sala->id ? 'bg-blue-50 border-l-4 border-blue-500' : '' }}"
            >
                <div
                    wire:click="selecionarSala({{ $sala->id }})"
                    class="flex items-center flex-1 cursor-pointer"
                >
                    <!-- Avatar da Sala -->
                    <div class="flex-shrink-0 mr-3">
                        @if($sala->avatar)
                            <img src="{{ Storage::url($sala->avatar) }}" alt="{{ $sala->nome }}" class="w-12 h-12 rounded-full object-cover">
                        @else
                            <div class="w-12 h-12 rounded-full bg-gray-300 flex items-center justify-center">
                                <span class="text-gray-600 font-semibold text-lg">
                                    {{ strtoupper(substr($sala->nome, 0, 1)) }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Info da Sala -->
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-baseline">
                            <h3 class="text-sm font-semibold text-gray-900 truncate">
                                {{ $sala->nome }}
                            </h3>
                            @if($sala->ultimaMensagem)
                                <span class="text-xs text-gray-500">
                                    {{ $sala->ultimaMensagem->created_at->diffForHumans() }}
                                </span>
                            @endif
                        </div>
                        @if($sala->ultimaMensagem)
                            <p class="text-sm text-gray-600 truncate">
                                <span class="font-medium">{{ $sala->ultimaMensagem->user->name }}:</span>
                                {{ $sala->ultimaMensagem->conteudo }}
                            </p>
                        @else
                            <p class="text-sm text-gray-400 italic">Sem mensagens</p>
                        @endif
                    </div>
                </div>

                <!-- Botão Gerir Membros -->
                <button
                    onclick="Livewire.dispatch('openManageMembersModal', { salaId: {{ $sala->id }} })"
                    class="ml-2 p-2 text-gray-500 hover:text-blue-600 transition"
                    title="Gerir membros"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </button>
            </div>
        @empty
            <div class="p-4 text-center text-gray-500">
                <p>Nenhuma sala disponível</p>
                <p class="text-sm mt-2">Crie uma nova sala para começar</p>
            </div>
        @endforelse
    </div>

    <!-- Componente de Gerir Membros -->
    <livewire:chat.manage-members />
</div>
