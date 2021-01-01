<?php

namespace App\MessageHandler;

use App\Message\EventMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class EventMessageHandler implements MessageHandlerInterface
{
    public function __invoke(EventMessage $message)
    {
        echo $message->getName();
    }
}
