<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionExpiringNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public $daysLeft)
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
                    ->subject('Aboneliğiniz Sona Eriyor')
                    ->greeting('Merhaba ' . $notifiable->name . '!')
                    ->line('Aboneliğinizin sona ermesine ' . $this->daysLeft . ' gün kaldı.')
                    ->line('Hizmetinizin kesintiye uğramaması için lütfen aboneliğinizi yenileyin.')
                    ->action('Aboneliği Yenile', url('/coach/subscription'))
                    ->line('Teşekkür ederiz!');
    }
}
