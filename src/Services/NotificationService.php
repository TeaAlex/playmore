<?php


namespace App\Services;


use App\Entity\User;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;

class NotificationService
{
    /**
     * @var MessageBusInterface
     */
    private $bus;
    /**
     * @var SerializerInterface
     */
    private $serializer;


    public function __construct(MessageBusInterface $bus, SerializerInterface $serializer)
    {

        $this->bus = $bus;
        $this->serializer = $serializer;
    }

    public function notify(User $from, User $to)
    {
        $target = [];
        if($to !== null){
            $target = ["http://monsite.com/user/{$to->getId()}"];
        }
        $update = new Update("http://monsite.com/ping", $this->serializer->serialize($from, 'json', ["groups" => "public"]), $target);
        $this->bus->dispatch($update);
    }
}