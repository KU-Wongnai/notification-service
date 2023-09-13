<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\RabbitMQReceiver;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeUserEmail;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        $receiver = new RabbitMQReceiver();
        $receiver->declareExchange('events.notification', 'topic');
        $receiver->bindQueueToExchange('email', 'events.notification', 'email.*');
        
        $receiver->consume('email', function ($message) {
            $data = json_decode($message, true);

            // Type of email to send to users
            if ($data['type'] === 'welcome.user') {
                Log::info('Received welcome.user event', $data);
                Mail::to($data['email'])->send(new WelcomeUserEmail());
            }

        });

    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}