<div x-data="{ open: false }" @click.away="open = false" class="relative">
    <!-- Bell Icon with Badge -->
    <button
        @click="open = !open"
        type="button"
        class="relative p-2 text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg transition"
        title="Notificações"
    >
        <!-- Bell Icon -->
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>

        <!-- Unread Badge -->
        @if($unreadCount > 0)
            <span class="absolute top-0 right-0 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Dropdown -->
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-xl border border-gray-200 z-50"
    >
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200" @click.stop>
            <h3 class="text-lg font-semibold text-gray-900">Notificações</h3>
            @if($unreadCount > 0)
                <button
                    wire:click="markAllAsRead"
                    type="button"
                    class="text-sm text-blue-600 hover:text-blue-700 font-medium"
                >
                    Marcar todas como lidas
                </button>
            @endif
        </div>

        <!-- Notifications List -->
        <div class="max-h-96 overflow-y-auto">
            @forelse($notifications as $notification)
                <div
                    wire:key="notification-{{ $notification->id }}"
                    class="p-4 border-b border-gray-100 transition {{ !$notification->lida ? 'bg-blue-50' : 'hover:bg-gray-50' }}"
                >
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-sm {{ !$notification->lida ? 'font-semibold' : '' }} text-gray-900">
                                <span class="text-blue-600">{{ $notification->mensagem->user->name }}</span>
                                mencionou-o(a) em
                                <span class="font-medium">{{ $notification->sala->nome }}</span>
                            </p>
                            <p class="text-sm text-gray-600 mt-1 truncate">
                                "{{ $notification->mensagem->conteudo }}"
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ contextual_timestamp($notification->created_at) }}
                            </p>
                        </div>

                        <!-- Mark as read button -->
                        @if(!$notification->lida)
                            <button
                                wire:click="markAsRead({{ $notification->id }})"
                                type="button"
                                class="ml-2 p-1 text-blue-600 hover:text-blue-800 hover:bg-blue-100 rounded transition"
                                title="Marcar como lida"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                        @else
                            <div class="ml-2">
                                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Loading indicator -->
                    <div wire:loading wire:target="markAsRead" class="text-xs text-blue-600 mt-1">
                        A marcar como lida...
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <p>Nenhuma notificação</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
