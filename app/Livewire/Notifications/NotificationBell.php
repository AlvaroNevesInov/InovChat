<?php

namespace App\Livewire\Notifications;

use App\Models\Notification;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class NotificationBell extends Component
{
    public $unreadCount = 0;
    public $notifications = [];

    public function mount()
    {
        $this->updateUnreadCount();
        $this->loadNotifications();
    }

    public function getListeners()
    {
        $userId = Auth::id();

        return [
            "echo-private:user.{$userId},.NotificationSent" => 'notificationReceived',
        ];
    }

    public function notificationReceived($event)
    {
        $this->updateUnreadCount();
        $this->loadNotifications();
        $this->dispatch('notificationReceived', $event);
    }

    public function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);

        if (!$notification || $notification->user_id !== Auth::id()) {
            return;
        }

        $notification->lida = true;
        $notification->save();

        $this->updateUnreadCount();
        $this->loadNotifications();
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('lida', false)
            ->update(['lida' => true]);

        $this->updateUnreadCount();
        $this->loadNotifications();
    }

    protected function updateUnreadCount()
    {
        $this->unreadCount = Notification::where('user_id', Auth::id())
            ->where('lida', false)
            ->count();
    }

    protected function loadNotifications()
    {
        $this->notifications = Notification::with(['sala', 'mensagem.user'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.notifications.notification-bell');
    }
}
