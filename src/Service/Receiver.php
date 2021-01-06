<?php

namespace App\Service;

use App\MessageHandler\EventMessageHandler;

class Receiver
{
    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var EventMessageHandler
     */
    private EventMessageHandler $handler;

    /**
     * @var int
     */
    private int $workerId;

    /**
     * @var string
     */
    private string $exchangeName;

    /**
     * Receiver constructor.
     * @param Connection $connection
     * @param EventMessageHandler $handler
     * @param int $workerId
     * @param string $exchangeName
     */
    public function __construct(Connection $connection, EventMessageHandler $handler, int $workerId, string $exchangeName)
    {
        $this->connection = $connection;
        $this->handler = $handler;
        $this->workerId = $workerId;
        $this->exchangeName = $exchangeName;
    }

    /**
     * @throws \ErrorException
     */
    public function consume(): void
    {
        $channel = $this->connection->channel();

        $this->connection->exchange($channel, $this->exchangeName, Connection::EXCHANGE_TYPE_DIRECT);

        $queueName = 'worker_'.$this->workerId;
        $routingKey = 'account_'.$this->workerId;

        $this->connection->queue($channel, $queueName, $this->exchangeName, $routingKey);

        $this->connection->receive($channel, $queueName, $this->handler);
    }
}
