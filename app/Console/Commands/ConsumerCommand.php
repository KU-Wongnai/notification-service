<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\RabbitMQReceiver;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeUserEmail;

class ConsumerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:consume';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info("[ProcessRabbitMQMessage] Handling job");

        $receiver = new RabbitMQReceiver();

        echo "Connected to RabbitMQ";

        $receiver->declareExchange('events.notification', 'topic');
        $receiver->bindQueueToExchange('email', 'events.notification', 'email.*');

        $receiver->consume('email', function ($message) {
            
            Log::info("[ProcessRabbitMQMessage] Received a message");
            
            $data = json_decode($message, true);

            // Type of email to send to users
            if ($data['type'] === 'welcome.user') {
                Log::info('Received welcome.user event', $data);
                echo "Received welcome.user event" . json_encode($data);
                Mail::to($data['email'])->send(new WelcomeUserEmail());
            }

        });
    }
}