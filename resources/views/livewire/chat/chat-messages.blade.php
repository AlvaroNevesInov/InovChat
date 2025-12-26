<div class="space-y-4">
    @if($sala)
        <!-- Cabeçalho da Sala -->
        <div class="sticky top-0 bg-white border-b border-gray-200 p-4 -m-4 mb-4">
            <div class="flex items-center">
                @if($sala->avatar)
                    <img src="{{ $sala->avatar }}" alt="{{ $sala->nome }}" class="w-10 h-10 rounded-full mr-3">
                @else
                    <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center mr-3">
                        <span class="text-gray-600 font-semibold">
                            {{ strtoupper(substr($sala->nome, 0, 1)) }}
                        </span>
                    </div>
                @endif
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">{{ $sala->nome }}</h2>
                    <p class="text-sm text-gray-500">{{ $sala->users->count() }} membros</p>
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
                                <div class="w-8 h-8 rounded-full bg-gray-400 flex items-center justify-center">
                                    <span class="text-white text-xs font-semibold">
                                        {{ strtoupper(substr($mensagem->user->name, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Conteúdo da Mensagem -->
                        <div>
                            <div class="flex items-baseline {{ $mensagem->user_id === auth()->id() ? 'flex-row-reverse' : '' }}">
                                <span class="text-xs font-medium text-gray-900 {{ $mensagem->user_id === auth()->id() ? 'ml-2' : 'mr-2' }}">
                                    {{ $mensagem->user->name }}
                                </span>
                                <span class="text-xs text-gray-500" title="{{ $mensagem->created_at->format('d/m/Y H:i:s') }}">
                                    {{ contextual_timestamp($mensagem->created_at) }}
                                </span>
                            </div>
                            <div class="mt-1 px-4 py-2 rounded-lg {{ $mensagem->user_id === auth()->id() ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-900' }}">
                                <p class="text-sm break-words">{!! highlight_mentions($mensagem->conteudo) !!}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 py-8">
                    <p>Nenhuma mensagem ainda. Seja o primeiro a enviar!</p>
                </div>
            @endforelse

            <!-- Typing Indicators -->
            @if(count($usersTyping) > 0)
                <div class="flex items-center space-x-2 text-sm text-gray-500 italic px-4 py-2">
                    <div class="flex space-x-1">
                        <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0ms;"></span>
                        <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 150ms;"></span>
                        <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 300ms;"></span>
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
            // Scroll automático para a última mensagem
            document.addEventListener('livewire:initialized', () => {
                const container = document.getElementById('mensagens-container');
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            });

            Livewire.on('mensagemEnviada', () => {
                setTimeout(() => {
                    const container = document.getElementById('mensagens-container');
                    if (container) {
                        container.scrollTop = container.scrollHeight;
                    }
                }, 100);
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
