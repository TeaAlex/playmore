<?php


namespace App\Services;


use App\Entity\User;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;


    public function __construct(MessageBusInterface $bus, SerializerInterface $serializer, UrlGeneratorInterface $urlGenerator)
    {
        $this->bus = $bus;
        $this->serializer = $serializer;
        $this->urlGenerator = $urlGenerator;
    }

    public function notifyAdvert(User $from, User $to, $advertId, $type)
    {
        if(!$type){
            return "ERROR";
        }
        $target = $data = [];
        $url = $this->urlGenerator->generate('advert_show', ['id' => $advertId]);
        if($to !== null){
            $target = ["http://monsite.com/user/{$to->getId()}"];
            $data = json_encode([
                "user" => $this->serializer->serialize($from, 'json', ["groups" => "public"]),
                "type" => $type,
                "url" => $url
            ]);
        }
        $update = new Update("http://monsite.com/{$type}", $data, $target);
        $this->bus->dispatch($update);
    }



}