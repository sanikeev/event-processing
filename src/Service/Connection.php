<?php

namespace App\Service;

use App\MessageHandler\EventMessageHandler;
use Exception;
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
    private string $host;

    /**
     * @var string
     */
    private string $port;

    /**
     * @var string
     */
    private string $user;

    /**
     * @var string
     */
    private string $password;

    /**
     * Connection constructor.
     * @param string $rmqHost
     * @param string $rmqPort
     * @param string $rmqUser
     * @param string $rmqPassword
     */
    public function __construct(string $rmqHost, string $rmqPort, string $rmqUser, string $rmqPassword)
    {
        $this->host = $rmqHost;
        $this->port = $rmqPort;
        $this->user = $rmqUser;
        $this->password = $rmqPassword;
    }

    /**
     * @return AMQPConnection
     */
    public function connect(): AMQPConnection
    {
        return new AMQPConnection(
            $this->host,
            $this->port,
            $this->user,
            $this->password
        );
    }

    /**
     * @return AMQPChannel
     */
    public function channel(): AMQPChannel
    {
        return $this->connect()->channel();
    }

    /**
     * @param AMQPChannel $channel
     * @param string $name
     * @param string $type
     */
    public function exchange(AMQPChannel $channel, string $name, string $type): void
    {
        $channel->exchange_declare($name, $type, false, false, false);
    }

    /**
     * @param AMQPChannel $channel
     * @param string $message
     * @param string $exchangeName
     * @param string $routingKey
     */
    public function publish(AMQPChannel $channel, string $message, string $exchangeName, string $routingKey): void
    {
        $msg = new AMQPMessage($message);

        $channel->basic_publish($msg, $exchangeName, $routingKey);
    }

    /**
     * @param AMQPChannel $channel
     * @param string $name
     * @param string $exchangeName
     * @param string $routingKey
     */
    public function queue(AMQPChannel $channel, string $name, string $exchangeName, string $routingKey): void
    {
        $channel->queue_declare($name, false, false, true, false);
        $channel->queue_bind($name, $exchangeName, $routingKey);
    }

    /**
     * @param AMQPChannel $channel
     * @param string $queueName
     * @param EventMessageHandler $handler
     * @throws \ErrorException
     */
    public function receive(AMQPChannel $channel, string $queueName, EventMessageHandler $handler): void
    {
        $channel->basic_consume($queueName, '', false, true, false, false, $handler);

        while ($channel->is_consuming()) {
            $channel->wait();
        }
    }

    /**
     * @throws Exception
     */
    public function closeConnection(): void
    {
        $this->channel()->close();
        $this->connect()->close();
    }
}
