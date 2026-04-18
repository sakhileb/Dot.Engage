<?php

namespace App\Livewire\Notifications;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationBell extends Component
{
    public int $unreadCount = 0;

    public function refresh(): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $this->unreadCount = $user->unreadNotifications()->count();
    }

    public function render()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $this->unreadCount = $user->unreadNotifications()->count();
        return view('livewire.notifications.notification-bell');
    }
}
