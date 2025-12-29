<div class="space-y-4" wire:poll.3s>
    @if($sala)
        <!-- Cabeçalho da Sala -->
        <div class="sticky top-0 bg-white border-b border-ash-200 p-4 -m-4 mb-4">
            <div class="flex items-center">
                @if($sala->avatar)
                    <img src="{{ $sala->avatar }}" alt="{{ $sala->nome }}" class="w-10 h-10 rounded-full mr-3">
                @else
                    <div class="w-10 h-10 rounded-full bg-campfire-100 flex items-center justify-center mr-3">
                        <span class="text-campfire-600 font-semibold">
                            {{ strtoupper(substr($sala->nome, 0, 1)) }}
                        </span>
                    </div>
                @endif
                <div>
                    <h2 class="text-lg font-semibold text-ash-900">{{ $sala->nome }}</h2>
                    <p class="text-sm text-ash-600">{{ $sala->users->count() }} membros</p>
                </div>
            </div>
        </div>

        <!-- Mensagens -->
        <div class="space-y-4" id="mensagens-container">
            @forelse($mensagens as $mensagem)
                <div class="flex {{ $mensagem->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="flex items-end max-w-[70%] {{ $mensagem->user_id === auth()->id() ? 'flex-row-reverse' : '' }}">
                        <!-- Avatar do Usuário -->
                        <div class="flex-shrink-0 {{ $mensagem->user_id === auth()->id() ? 'ml-2' : 'mr-2' }}">
                            @if($mensagem->user->avatar)
                                <img src="{{ $mensagem->user->avatar }}" alt="{{ $mensagem->user->name }}"
                                     class="w-8 h-8 rounded-full">
                            @else
                                <div class="w-8 h-8 rounded-full bg-campfire-500 flex items-center justify-center">
                                    <span class="text-white text-xs font-semibold">
                                        {{ strtoupper(substr($mensagem->user->name, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Conteúdo da Mensagem -->
                        <div>
                            <div class="flex items-baseline {{ $mensagem->user_id === auth()->id() ? 'flex-row-reverse' : '' }}">
                                <span class="text-xs font-medium text-ash-900 {{ $mensagem->user_id === auth()->id() ? 'ml-2' : 'mr-2' }}">
                                    {{ $mensagem->user->name }}
                                </span>
                                <span class="text-xs text-ash-500" title="{{ $mensagem->created_at->format('d/m/Y H:i:s') }}">
                                    {{ contextual_timestamp($mensagem->created_at) }}
                                </span>
                            </div>
                            <div class="mt-1 px-4 py-2 rounded-lg {{ $mensagem->user_id === auth()->id() ? 'bg-campfire-500 text-white' : 'bg-ash-100 text-ash-900' }}">
                                @if($mensagem->conteudo)
                                    <p class="text-sm break-words">{!! highlight_mentions($mensagem->conteudo) !!}</p>
                                @endif

                                @if($mensagem->hasAttachment())
                                    <div class="mt-2 {{ $mensagem->conteudo ? 'pt-2 border-t' : '' }} {{ $mensagem->user_id === auth()->id() ? 'border-campfire-400' : 'border-ash-300' }}">
                                        <!-- Preview para imagens -->
                                        @if(str_contains($mensagem->file_type, 'image'))
                                            <a href="{{ route('download.attachment', $mensagem->id) }}" target="_blank" class="block">
                                                <img
                                                    src="{{ $mensagem->file_url }}"
                                                    alt="{{ $mensagem->file_name }}"
                                                    class="max-w-full rounded-lg cursor-pointer hover:opacity-90 transition"
                                                    style="max-height: 300px;"
                                                >
                                            </a>
                                            <p class="text-xs mt-1 opacity-75">{{ $mensagem->file_name }} ({{ $mensagem->file_size_formatted }})</p>
                                        @else
                                            <!-- Outros tipos de ficheiro -->
                                            <a
                                                href="{{ route('download.attachment', $mensagem->id) }}"
                                                class="flex items-center space-x-2 p-2 rounded {{ $mensagem->user_id === auth()->id() ? 'bg-campfire-600 hover:bg-campfire-700' : 'bg-ash-200 hover:bg-ash-300' }} transition"
                                            >
                                                <span class="text-2xl">{{ $mensagem->file_icon }}</span>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium truncate">{{ $mensagem->file_name }}</p>
                                                    <p class="text-xs opacity-75">{{ $mensagem->file_size_formatted }}</p>
                                                </div>
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-ash-500 py-8">
                    <p>Nenhuma mensagem ainda. Seja o primeiro a enviar!</p>
                </div>
            @endforelse

            <!-- Typing Indicators -->
            @if(count($usersTyping) > 0)
                <div class="flex items-center space-x-2 text-sm text-ash-600 italic px-4 py-2">
                    <div class="flex space-x-1">
                        <span class="w-2 h-2 bg-campfire-400 rounded-full animate-bounce" style="animation-delay: 0ms;"></span>
                        <span class="w-2 h-2 bg-campfire-400 rounded-full animate-bounce" style="animation-delay: 150ms;"></span>
                        <span class="w-2 h-2 bg-campfire-400 rounded-full animate-bounce" style="animation-delay: 300ms;"></span>
                    </div>
                    <span>
                        @if(count($usersTyping) === 1)
                            {{ array_values($usersTyping)[0]['name'] }} está a escrever...
                        @elseif(count($usersTyping) === 2)
                            {{ array_values($usersTyping)[0]['name'] }} e {{ array_values($usersTyping)[1]['name'] }} estão a escrever...
                        @else
                            {{ count($usersTyping) }} pessoas estão a escrever...
                        @endif
                    </span>
                </div>
            @endif
        </div>

        <script>
            // Função para fazer scroll suave para o final
            function scrollToBottom() {
                const container = document.getElementById('mensagens-container');
                if (container) {
                    // Scroll suave para o final
                    container.scrollTo({
                        top: container.scrollHeight,
                        behavior: 'smooth'
                    });
                }
            }

            // Scroll ao carregar
            document.addEventListener('livewire:initialized', () => {
                setTimeout(scrollToBottom, 100);
            });

            // Scroll quando envia mensagem
            Livewire.on('mensagemEnviada', () => {
                setTimeout(scrollToBottom, 100);
            });

            // Scroll após polling (detecta mudança no DOM)
            const observer = new MutationObserver(() => {
                scrollToBottom();
            });

            // Observa mudanças no container de mensagens
            document.addEventListener('DOMContentLoaded', () => {
                const container = document.getElementById('mensagens-container');
                if (container) {
                    observer.observe(container, {
                        childList: true,
                        subtree: true
                    });
                }
            });

            // Limpar typing indicator após 3 segundos
            Livewire.on('clearTypingIndicator', (event) => {
                setTimeout(() => {
                    @this.clearTypingUser(event.userId);
                }, 3000);
            });
        </script>
    @endif
</div>
