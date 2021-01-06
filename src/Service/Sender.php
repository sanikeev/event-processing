<?php


namespace App\Service;


use App\Message\EventMessage;

class Sender
{

    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var int
     */
    private $maxWorkers;
    /**
     * @var string
     */
    private $exchangeName;

    public function __construct(Connection $connection, string $maxWorkers, string $exchangeName)
    {
        $this->connection = $connection;
        $this->maxWorkers = (int) $maxWorkers;
        $this->exchangeName = $exchangeName;
    }

    public function send(EventMessage $eventMessage)
    {
        $channel = $this->connection->channel();

        $this->connection->exchange($channel, $this->exchangeName, Connection::EXCHANGE_TYPE_DIRECT);

        $msgSerialized = serialize($eventMessage);

        $accountId = $eventMessage->getAccountId();

        $key = $accountId % $this->maxWorkers;

        $routingKey = 'account_' . $key;

        $this->connection->publish($channel, $msgSerialized, $this->exchangeName, $routingKey);
    }
}