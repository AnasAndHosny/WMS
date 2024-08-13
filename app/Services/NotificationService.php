<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class NotificationService
{

    public function index(): array
    {
        $notifications = Auth::user()->unreadNotifications;

        $data = [];
        foreach ($notifications as $notification) {
            $data[] = [
                'message_en' => $notification->data['message_en'],
                'message_ar' => $notification->data['message_ar']
            ];
        }

        $message = __('messages.index_success', ['class' => __('notifications')]);
        $code = 200;
        return ['data' => $data, 'message' => $message, 'code' => $code];
    }

    public function markAllAsRead(): array
    {
        Auth::user()->unreadNotifications->markAsRead();

        $message = __('messages.update_success', ['class' => __('notifications')]);
        $code = 200;
        return ['data' => [], 'message' => $message, 'code' => $code];
    }
}
