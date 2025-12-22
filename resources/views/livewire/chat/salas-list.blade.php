<div class="overflow-y-auto">
    @forelse($salas as $sala)
        <div
            wire:click="selecionarSala({{ $sala->id }})"
            class="flex items-center p-4 hover:bg-gray-100 cursor-pointer transition {{ $salaAtiva == $sala->id ? 'bg-blue-50 border-l-4 border-blue-500' : '' }}"
        >
            <!-- Avatar da Sala -->
            <div class="flex-shrink-0 mr-3">
                @if($sala->avatar)
                    <img src="{{ $sala->avatar }}" alt="{{ $sala->nome }}" class="w-12 h-12 rounded-full">
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
    @empty
        <div class="p-4 text-center text-gray-500">
            <p>Nenhuma sala disponível</p>
        </div>
    @endforelse
</div>
