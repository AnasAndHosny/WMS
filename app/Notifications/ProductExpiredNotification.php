<?php

namespace App\Notifications;

use App\Models\StoredProduct;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProductExpiredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public StoredProduct $storedProduct)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message_en' => $this->storedProduct->valid_quantity . ' pieces of the product (' . $this->storedProduct->product->name_en . ') has been expired.',
            'message_ar' => $this->storedProduct->valid_quantity . ' قطعة من المنتج (' . $this->storedProduct->product->name_ar . ') انتهت صلاحيتها.',
        ];
    }
}
