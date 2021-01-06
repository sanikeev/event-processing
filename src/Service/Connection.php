<?php

namespace App\Service;

use App\MessageHandler\EventMessageHandler;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Connection
{
    public const EXCHANGE_TYPE_DIRECT = 'direct';
    public const EXCHANGE_TYPE_FANOUT = 'fanout';
    public const EXCHANGE_TYPE_TOPIC = 'topic';

    /**
     * @var string
     */
    private $host;
    /**
     * @var string
     */
    private $port;
    /**
     * @var string
     */
    private $user;
    /**
     * @var string
     */
    private $password;

    public function __construct(string $rmqHost, string $rmqPort, string $rmqUser, string $rmqPassword)
    {
        $this->host = $rmqHost;
        $this->port = $rmqPort;
        $this->user = $rmqUser;
        $this->password = $rmqPassword;
    }

    public function connect(): AMQPConnection
    {
        return new AMQPConnection(
            $this->host,
            $this->port,
            $this->user,
            $this->password
        );
    }

    public function channel(): AMQPChannel
    {
        return $this->connect()->channel();
    }

    public function exchange(AMQPChannel $channel, string $name, string $type): void
    {
        $channel->exchange_declare($name, $type, false, false, false);
    }

    public function publish(AMQPChannel $channel, string $message, string $exchangeName, string $routingKey)
    {
        $msg = new AMQPMessage($message);

        $channel->basic_publish($msg, $exchangeName, $routingKey);
    }

    public function queue(AMQPChannel $channel, string $name, string $exchangeName, string $routingKey)
    {
        $channel->queue_declare($name, false, false, true, false);
        $channel->queue_bind($name, $exchangeName, $routingKey);
    }

    public function receive(AMQPChannel $channel, string $queueName, EventMessageHandler $handler)
    {
        $channel->basic_consume($queueName, '', false, true, false, false, $handler);

        while ($channel->is_consuming()) {
            $channel->wait();
        }
    }

    public function closeConnection()
    {
        $this->channel()->close();
        $this->connect()->close();
    }
}
