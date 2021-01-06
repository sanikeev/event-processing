<?php

namespace App\MessageHandler;

use App\Message\EventMessage;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class EventMessageHandler implements MessageHandlerInterface
{
    public function __invoke(AMQPMessage $message)
    {
        sleep(1);
        /** @var EventMessage $data */
        $data = unserialize($message->body);
        echo '==============' . PHP_EOL;
        echo 'Account ID: ' . $data->getAccountId() . PHP_EOL;
        echo 'Message: ' . $data->getMsg() . PHP_EOL;
        echo 'Timestamp: ' . $data->getCreatedAt()->format(\DateTime::RFC3339_EXTENDED) . PHP_EOL;
        echo 'Processed timestamp: ' . (new \DateTime())->format(\DateTime::RFC3339_EXTENDED) . PHP_EOL;
    }
}
