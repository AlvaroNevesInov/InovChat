<?php

namespace App\Livewire\Notifications;

use App\Models\Notification;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class NotificationBell extends Component
{
    public int $unreadCount = 0;

    public function mount(): void
    {
        $this->updateUnreadCount();
    }

    public function getListeners(): array
    {
        $userId = Auth::id();

        if (! $userId) {
            return [];
        }

        return [
            "echo:user.{$userId},NotificationSent" => 'notificationReceived',
        ];
    }

    public function notificationReceived(mixed $event): void
    {
        $this->updateUnreadCount();
        $this->dispatch('notificationReceived', $event);
    }

    public function markAsRead(int $notificationId): void
    {
        try {
            Log::info('markAsRead called', [
                'notification_id' => $notificationId,
                'user_id' => Auth::id(),
            ]);

            /** @var Authenticatable|null $user */
            $user = Auth::user();

            if (! $user) {
                Log::warning('markAsRead called without authenticated user');
                return;
            }

            $notification = Notification::find($notificationId);

            if (! $notification) {
                Log::error('Notification not found', [
                    'notification_id' => $notificationId,
                ]);
                return;
            }

            if ($notification->user_id !== $user->getAuthIdentifier()) {
                Log::error('User mismatch when marking notification as read', [
                    'notification_user_id' => $notification->user_id,
                    'auth_user_id' => $user->getAuthIdentifier(),
                ]);
                return;
            }

            Log::info('Updating notification as read', [
                'notification_id' => $notificationId,
            ]);

            $notification->lida = true;
            $saved = $notification->save();

            Log::info('Notification save result', [
                'notification_id' => $notificationId,
                'result' => $saved ? 'success' : 'failed',
            ]);

            $this->updateUnreadCount();

            Log::info('Unread count updated', [
                'unread_count' => $this->unreadCount,
            ]);
        } catch (\Throwable $e) {
            Log::error('Error marking notification as read', [
                'notification_id' => $notificationId,
                'user_id' => Auth::id(),
                'exception' => $e,
            ]);
        }
    }

    public function markAllAsRead(): void
    {
        try {
            $userId = Auth::id();

            Log::info('markAllAsRead called', [
                'user_id' => $userId,
            ]);

            if (! $userId) {
                Log::warning('markAllAsRead called without authenticated user');
                return;
            }

            $count = Notification::where('user_id', $userId)
                ->where('lida', false)
                ->count();

            Log::info('Unread notifications found', [
                'count' => $count,
            ]);

            $updated = Notification::where('user_id', $userId)
                ->where('lida', false)
                ->update(['lida' => true]);

            Log::info('Notifications marked as read', [
                'updated' => $updated,
            ]);

            $this->updateUnreadCount();

            Log::info('Unread count updated', [
                'unread_count' => $this->unreadCount,
            ]);
        } catch (\Throwable $e) {
            Log::error('Error marking all notifications as read', [
                'user_id' => Auth::id(),
                'exception' => $e,
            ]);
        }
    }

    protected function updateUnreadCount(): void
    {
        $userId = Auth::id();

        $this->unreadCount = $userId
            ? Notification::where('user_id', $userId)
                ->where('lida', false)
                ->count()
            : 0;
    }

    public function render()
    {
        $userId = Auth::id();

        $notifications = $userId
            ? Notification::with(['sala', 'mensagem.user'])
                ->where('user_id', $userId)
                ->orderByDesc('created_at')
                ->limit(10)
                ->get()
            : collect();

        return view('livewire.notifications.notification-bell', [
            'notifications' => $notifications,
        ]);
    }
}
