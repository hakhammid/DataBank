<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationBell extends Component
{
    public $unreadCount = 0;

    protected $listeners = [
        'logout' => 'handleLogout',
    ];

    public function mount()
    {
        $this->updateUnreadCount();
    }

    public function updateUnreadCount()
    {
        if (!Auth::check()) {
            $this->unreadCount = 0;
            return;
        }

        $this->unreadCount = Notification::where('user_id', Auth::id())
            ->unread()
            ->count();
    }

    public function markAsRead($notificationId)
    {
        if (!Auth::check()) return;

        $notification = Notification::where('id', $notificationId)
            ->where('user_id', Auth::id())
            ->first();

        if ($notification) {
            $notification->markAsRead();
            $this->updateUnreadCount();
        }
    }

    public function markAllAsRead()
    {
        if (!Auth::check()) return;

        Notification::where('user_id', Auth::id())
            ->unread()
            ->update(['read_at' => now()]);

        $this->unreadCount = 0;
    }

    public function handleLogout()
    {
        $this->reset();
    }

    public function render()
    {
        $notifications = collect();

        if (Auth::check()) {
            $notifications = Notification::where('user_id', Auth::id())
                ->with(['module' => fn($q) => $q->select('id', 'course_code', 'title'), 'module.courses:id'])
                ->latest()
                ->take(20)
                ->get();
        }

        return view('livewire.notification-bell', [
            'notifications' => $notifications,
        ]);
    }
}
