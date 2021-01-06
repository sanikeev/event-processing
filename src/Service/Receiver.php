<?php


namespace App\Service;


use App\MessageHandler\EventMessageHandler;

class Receiver
{

    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var EventMessageHandler
     */
    private $handler;
    /**
     * @var int|string
     */
    private $workerId;

    public function __construct(Connection $connection, EventMessageHandler $handler, int $workerId)
    {
        $this->connection = $connection;
        $this->handler = $handler;
        $this->workerId = $workerId;
    }

    public function consume()
    {
        $exchangeName = 'event';

        $channel = $this->connection->channel();

        $this->connection->exchange($channel, $exchangeName, Connection::EXCHANGE_TYPE_DIRECT);

        $queueName = 'worker_' . $this->workerId;
        $routingKey = 'account_' . $this->workerId;

        $this->connection->queue($channel, $queueName, $exchangeName, $routingKey);

        $this->connection->receive($channel, $queueName, $this->handler);
    }
}