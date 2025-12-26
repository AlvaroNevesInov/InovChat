<?php

namespace App\Livewire\Notifications;

use App\Models\Notification;
use Livewire\Component;

class NotificationBell extends Component
{
    public $unreadCount = 0;

    public function mount()
    {
        $this->updateUnreadCount();
    }

    public function getListeners()
    {
        $userId = auth()->id();
        return [
            "echo:user.{$userId},NotificationSent" => 'notificationReceived',
        ];
    }

    public function notificationReceived($event)
    {
        $this->updateUnreadCount();
        $this->dispatch('notificationReceived', $event);
    }

    public function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);

        if (!$notification || $notification->user_id !== auth()->id()) {
            return;
        }

        $notification->lida = true;
        $notification->save();

        $this->updateUnreadCount();
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())
            ->where('lida', false)
            ->update(['lida' => true]);

        $this->updateUnreadCount();
    }

    protected function updateUnreadCount()
    {
        $this->unreadCount = Notification::where('user_id', auth()->id())
            ->where('lida', false)
            ->count();
    }

    public function render()
    {
        $notifications = Notification::with(['sala', 'mensagem.user'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('livewire.notifications.notification-bell', [
            'notifications' => $notifications,
        ]);
    }
}
