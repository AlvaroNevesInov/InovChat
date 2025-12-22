<div>
<form wire:submit="enviarMensagem" class="flex items-center space-x-2">
        <div class="flex-1">
            <input
                type="text"
                wire:model="conteudo"
                placeholder="Escreve a tua mensagem..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                {{ $salaId ? '' : 'disabled' }}
            >
            @error('conteudo')
                <span class="text-xs text-red-500 mt-1">{{ $message }}</span>
            @enderror
        </div>
        <button
            type="submit"
            class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition"
            {{ $salaId ? '' : 'disabled' }}
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
            </svg>
        </button>
    </form>
</div>
