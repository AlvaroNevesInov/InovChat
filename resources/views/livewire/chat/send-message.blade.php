<div x-data="{
    typingTimeout: null,
    showEmojiPicker: false,
    insertEmoji(emoji) {
        const textarea = $refs.messageInput;
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const text = textarea.value;
        const before = text.substring(0, start);
        const after = text.substring(end, text.length);

        $wire.conteudo = before + emoji + after;
        this.showEmojiPicker = false;

        // Refocar no textarea
        setTimeout(() => {
            textarea.focus();
            const newPos = start + emoji.length;
            textarea.setSelectionRange(newPos, newPos);
        }, 0);
    }
}">
    <!-- Preview do anexo -->
    @if ($attachment)
        <div class="mb-2 p-3 bg-gray-50 rounded-lg border border-gray-200 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                </svg>
                <span class="text-sm text-gray-700">{{ $attachment->getClientOriginalName() }}</span>
                <span class="text-xs text-gray-500">({{ number_format($attachment->getSize() / 1024, 2) }} KB)</span>
            </div>
            <button
                type="button"
                wire:click="removeAttachment"
                class="text-red-500 hover:text-red-700"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif

    <form wire:submit="enviarMensagem" class="flex items-center space-x-2">
        <div class="flex-1 space-y-1">
            <textarea
                x-ref="messageInput"
                wire:model="conteudo"
                placeholder="Escreve a tua mensagem..."
                rows="1"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                {{ $salaId ? '' : 'disabled' }}
                @keydown.enter.prevent="if (!event.shiftKey) { $wire.enviarMensagem(); }"
                @keydown="
                    clearTimeout(typingTimeout);
                    typingTimeout = setTimeout(() => {
                        if ($wire.salaId) {
                            $wire.notifyTyping();
                        }
                    }, 300);
                "
            ></textarea>
            @error('conteudo')
                <span class="text-xs text-red-500">{{ $message }}</span>
            @enderror
            @error('attachment')
                <span class="text-xs text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- Botão de Anexo -->
        <div class="relative flex items-center">
            <label class="cursor-pointer p-2.5 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition flex items-center justify-center {{ $salaId ? '' : 'opacity-50 cursor-not-allowed' }}">
                <input
                    type="file"
                    wire:model="attachment"
                    class="hidden"
                    {{ $salaId ? '' : 'disabled' }}
                >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                </svg>
                <div wire:loading wire:target="attachment" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-75 rounded-lg">
                    <svg class="animate-spin h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </label>
        </div>

        <!-- Botão de Emoji -->
        <div class="relative flex items-center" @click.away="showEmojiPicker = false">
            <button
                type="button"
                @click="showEmojiPicker = !showEmojiPicker"
                class="p-2.5 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition flex items-center justify-center {{ $salaId ? '' : 'opacity-50 cursor-not-allowed' }}"
                {{ $salaId ? '' : 'disabled' }}
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </button>

            <!-- Emoji Picker -->
            <div
                x-show="showEmojiPicker"
                x-transition
                class="absolute bottom-full mb-2 right-0 bg-white rounded-lg shadow-xl border border-gray-200 p-3 grid grid-cols-6 gap-2 z-50 max-h-96 overflow-y-auto"
                style="display: none; width: 320px;"
            >
                @foreach(['😊', '😂', '❤️', '👍', '🎉', '😍', '🔥', '✨', '👏', '💪', '🙌', '✅', '🎯', '💡', '🚀', '⭐', '😎', '🤔', '😅', '🙏', '👌', '💯', '🎊', '😉', '😢', '😭', '😱', '🤗', '😴', '🤩', '😇', '🥳'] as $emoji)
                    <button
                        type="button"
                        @click="insertEmoji('{{ $emoji }}')"
                        class="text-3xl hover:bg-gray-100 rounded-lg p-2 transition transform hover:scale-110"
                    >
                        {{ $emoji }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Botão Enviar -->
        <button
            type="submit"
            class="p-2.5 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition flex items-center justify-center"
            {{ $salaId ? '' : 'disabled' }}
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
            </svg>
        </button>
    </form>
</div>
