<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\User;
use Illuminate\Notifications\Messages\BroadcastMessage;

class WelcomeNewUser extends Notification
{
    use Queueable;

    private $user;
    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        // $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable)
    {
        return [ 'type' => 'User',
                'message' => 'Welcome to KU Wongnai. Share your favorite food with our community and inspire others.',];
        // return 'Welcome to KU Wongnai. Share your favorite food with our community and inspire others.';
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        // return new BroadcastMessage($this->toArray($notifiable));
        return (new BroadcastMessage([
            'message' => 'Welcome to KU Wongnai. Share your favorite food with our community and inspire others.',
         
        ]));
    }

    /**
     * Get the type of the notification being broadcast.
     */
    
    // อันนี้เปลี่ยนชื่อ Type ของ Notification ที่จะส่งไปให้ Frontend
    // public function broadcastType(): string
    // {
    //     // return 'broadcast.message';
    //     return 'welcome-new-user';

    // }
}
