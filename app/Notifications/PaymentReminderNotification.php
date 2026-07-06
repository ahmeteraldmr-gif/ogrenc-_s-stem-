<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public $amount, public $dueDate)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Ödeme Hatırlatması')
                    ->greeting('Merhaba ' . $notifiable->name . '!')
                    ->line('Yaklaşan ödemeniz hakkında hatırlatma.')
                    ->line('Ödeme Tutarı: ₺' . $this->amount)
                    ->line('Ödeme Tarihi: ' . $this->dueDate->format('d.m.Y'))
                    ->action('Ödeme Yap', url('/coach/payment'))
                    ->line('Teşekkür ederiz!');
    }
}
