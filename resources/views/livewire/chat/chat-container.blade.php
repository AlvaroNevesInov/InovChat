<div class="flex h-full">
    <!-- Lista de Salas (Sidebar) -->
    <div class="w-1/4 border-r border-ash-200 bg-white">
        <div class="p-4 border-b border-ash-200">
            <h2 class="text-lg font-semibold text-ash-900">Salas de Chat</h2>
        </div>
        <livewire:chat.salas-list />
    </div>

    <!-- Área de Chat -->
    <div class="flex-1 flex flex-col bg-white">
        @if($salaAtiva)
            <!-- Mensagens -->
            <div class="flex-1 overflow-y-auto p-4">
                <livewire:chat.chat-messages :key="'messages-' . $salaAtiva" :salaId="$salaAtiva" />
            </div>

            <!-- Formulário de Envio -->
            <div class="border-t border-ash-200 bg-white p-4">
                <livewire:chat.send-message :key="'send-' . $salaAtiva" :salaId="$salaAtiva" />
            </div>
        @else
            <div class="flex-1 flex items-center justify-center text-ash-500">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-campfire-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <p class="mt-4 text-lg">Seleciona uma sala para começar a conversar</p>
                </div>
            </div>
        @endif
    </div>
</div>
