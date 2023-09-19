<?php

namespace App;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Support\Facades\Log;

class RabbitMQReceiver
{
    private $connection;
    private $channel;

    public function __construct()
    {
        Log::info("Trying to connect to RabbitMQ...");
        $this->connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST', 'rabbitmq'),
            env('RABBITMQ_PORT', 5672),
            env('RABBITMQ_USER', 'user'),
            env('RABBITMQ_PASSWORD', 'password'),
            env('RABBITMQ_VHOST', '/')
      );

        $this->channel = $this->connection->channel();

        Log::info("Connected to RabbitMQ");
    }

    public function consume(string $queueName, callable $callback)
    {
        // $channel->queue_declare($queueName, false, true, false, false);

        $this->channel->basic_consume($queueName, '', false, true, false, false, function (AMQPMessage $message) use ($callback) {
            $body = $message->getBody();
            $callback($body);
        });

        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
    }

    public function declareExchange($exchangeName, $exchangeType = 'direct')
    {
        $this->channel->exchange_declare($exchangeName, $exchangeType, false, true, false);
    }

    public function bindQueueToExchange($queueName, $exchangeName, $routingKeyPattern)
    {
        // Declare the queue
        $this->channel->queue_declare($queueName, false, true, false, false);

        // Bind the queue to the exchange with the specified routing key pattern
        $this->channel->queue_bind($queueName, $exchangeName, $routingKeyPattern);
    }

    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }
}