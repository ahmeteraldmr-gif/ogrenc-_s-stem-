<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeStudentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public $coachName)
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
                    ->subject('Öğrenci Takip Sistemine Hoş Geldiniz')
                    ->greeting('Merhaba ' . $notifiable->name . '!')
                    ->line($this->coachName . ' sizi sisteme ekledi.')
                    ->line('Artık koçunuzun size atadığı konuları takip edebilir, soru çözüm ve deneme sonuçlarınızı kaydedebilirsiniz.')
                    ->action('Panele Git', url('/student/dashboard'))
                    ->line('Başarılar dileriz!');
    }
}
