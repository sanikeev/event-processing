<?php

namespace App\Service;

use App\Message\EventMessage;

class Sender
{
    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var int
     */
    private int $maxWorkers;

    /**
     * @var string
     */
    private string $exchangeName;

    /**
     * Sender constructor.
     * @param Connection $connection
     * @param string $maxWorkers
     * @param string $exchangeName
     */
    public function __construct(Connection $connection, string $maxWorkers, string $exchangeName)
    {
        $this->connection = $connection;
        $this->maxWorkers = (int) $maxWorkers;
        $this->exchangeName = $exchangeName;
    }

    /**
     * @param EventMessage $eventMessage
     */
    public function send(EventMessage $eventMessage): void
    {
        $channel = $this->connection->channel();

        $this->connection->exchange($channel, $this->exchangeName, Connection::EXCHANGE_TYPE_DIRECT);

        $msgSerialized = serialize($eventMessage);

        $accountId = $eventMessage->getAccountId();

        $key = $accountId % $this->maxWorkers;

        $routingKey = 'account_'.$key;

        $this->connection->publish($channel, $msgSerialized, $this->exchangeName, $routingKey);
    }
}
