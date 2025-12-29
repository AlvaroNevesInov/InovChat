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
                class="flex items-center p-4 hover:bg-campfire-50 transition {{ $salaAtiva == $sala->id ? 'bg-campfire-100 border-l-4 border-campfire-500' : '' }}"
            >
                <div
                    wire:click="selecionarSala({{ $sala->id }})"
                    class="flex items-center flex-1 cursor-pointer"
                >
                    <!-- Avatar da Sala -->
                    <div class="flex-shrink-0 mr-3 relative">
                        @if($sala->avatar)
                            <img src="{{ Storage::url($sala->avatar) }}" alt="{{ $sala->nome }}" class="w-12 h-12 rounded-full object-cover">
                        @else
                            <div class="w-12 h-12 rounded-full bg-campfire-100 flex items-center justify-center">
                                <span class="text-campfire-600 font-semibold text-lg">
                                    {{ strtoupper(substr($sala->nome, 0, 1)) }}
                                </span>
                            </div>
                        @endif

                        <!-- Badge de Unread -->
                        @if($sala->unread_count > 0)
                            <span class="absolute -top-1 -right-1 bg-ember-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center shadow-lg">
                                {{ $sala->unread_count > 9 ? '9+' : $sala->unread_count }}
                            </span>
                        @endif
                    </div>

                    <!-- Info da Sala -->
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-baseline">
                            <div class="flex items-center space-x-1 flex-1 min-w-0 mr-2">
                                @if($sala->isDM())
                                    <svg class="w-4 h-4 text-campfire-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-ash-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                @endif
                                <h3 class="text-sm font-semibold text-ash-900 truncate {{ $sala->unread_count > 0 ? 'font-bold' : '' }}">
                                    @if($sala->isDM() && $sala->getOtherUserInDM())
                                        {{ $sala->getOtherUserInDM()->name }}
                                    @else
                                        {{ $sala->nome }}
                                    @endif
                                </h3>
                            </div>
                            @if($sala->ultimaMensagem)
                                <span class="text-xs text-ash-500 flex-shrink-0" title="{{ $sala->ultimaMensagem->created_at->format('d/m/Y H:i:s') }}">
                                    {{ contextual_timestamp($sala->ultimaMensagem->created_at) }}
                                </span>
                            @endif
                        </div>
                        @if($sala->ultimaMensagem)
                            <p class="text-sm {{ $sala->unread_count > 0 ? 'text-ash-900 font-medium' : 'text-ash-600' }} truncate">
                                <span class="font-medium">{{ $sala->ultimaMensagem->user->name }}:</span>
                                {{ $sala->ultimaMensagem->conteudo }}
                            </p>
                        @else
                            <p class="text-sm text-ash-400 italic">Sem mensagens</p>
                        @endif
                    </div>
                </div>

                <!-- Botão Gerir Membros -->
                <button
                    onclick="Livewire.dispatch('openManageMembersModal', { salaId: {{ $sala->id }} })"
                    class="ml-2 p-2 text-ash-500 hover:text-campfire-600 transition"
                    title="Gerir membros"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </button>
            </div>
        @empty
            <div class="p-4 text-center text-ash-500">
                <p>Nenhuma sala disponível</p>
                <p class="text-sm mt-2">Crie uma nova sala para começar</p>
            </div>
        @endforelse
    </div>

    <!-- Componente de Gerir Membros -->
    <livewire:chat.manage-members />
</div>
