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

    public function notifyNewOffer(User $from, User $to, $advertId)
    {
        $target = $data = [];
        $url = $this->urlGenerator->generate('advert_show', ['id' => $advertId]);
        if($to !== null){
            $target = ["http://monsite.com/user/{$to->getId()}"];
            $data = json_encode([
                "user" => $this->serializer->serialize($from, 'json', ["groups" => "public"]),
                "type" => "new_offer",
                "url" => $url
            ]);
        }
        $update = new Update("http://monsite.com/offer", $data, $target);
        $this->bus->dispatch($update);
    }

    public function notifyAcceptedOffer(User $from, User $to)
    {
        $target = $data = [];
        if($to !== null){
            $target = ["http://monsite.com/user/{$to->getId()}"];
            $data = json_encode([
              "user" => $this->serializer->serialize($from, 'json', ["groups" => "public"]),
              "type" => 'accepted_offer'
            ]);
        }
        $update = new Update("http://monsite.com/accepted_offer", $data, $target);
        $this->bus->dispatch($update);
    }

    public function notifyDeclinedOffer(User $from, User $to)
    {
        $target = $data = [];
        if($to !== null){
            $target = ["http://monsite.com/user/{$to->getId()}"];
            $data = json_encode([
              "user" => $this->serializer->serialize($from, 'json', ["groups" => "public"]),
              "type" => "declined_offer"
            ]);
        }
        $update = new Update("http://monsite.com/declined_offer", $data, $target);
        $this->bus->dispatch($update);
    }

}