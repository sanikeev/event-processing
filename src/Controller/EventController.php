<?php

namespace App\Controller;

use App\Message\EventMessage;
use App\Service\Sender;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    /**
     * @Route("/event", name="event")
     */
    public function index(Sender $sender, Request $request): Response
    {
        $accountId = $request->get('account_id', 1);
        $message = $request->get('message', 'Hello world');

        $sender->send(new EventMessage($accountId, $message, new DateTime()));

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/EventController.php',
        ]);
    }
}
